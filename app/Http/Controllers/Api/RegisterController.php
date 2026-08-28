<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $payload = $request->all();

        if (! isset($payload['password_confirmation']) && isset($payload['confirmed'])) {
            $payload['password_confirmation'] = $payload['confirmed'];
        }

        $validator = Validator::make($payload, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $validated = $validator->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]);

        return $this->successResponse(
            'User registered successfully.',
            [
                'user' => $user,
                'token' => $this->createApiToken($user, 'api-token'),
            ],
            201
        );
    }

    public function facebook(Request $request): JsonResponse
    {
        return $this->registerSocial($request, 'facebook');
    }

    public function google(Request $request): JsonResponse
    {
        if ($request->filled('code')) {
            return $this->googleWithAuthorizationCode($request);
        }

        $validator = Validator::make($request->all(), [
            'id_token' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $googleUser = $this->verifyGoogleIdToken($request->string('id_token')->toString());

        if (! $googleUser['status']) {
            return response()->json([
                'status' => false,
                'message' => $googleUser['message'],
                'errors' => $googleUser['errors'] ?? null,
            ], $googleUser['code']);
        }

        $userData = $googleUser['data'];

        $user = DB::transaction(function () use ($userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make(Str::random(32)),
                ]
            );

            $this->syncGoogleUserData($user, $userData);

            SocialAccount::updateOrCreate(
                [
                    'provider' => 'google',
                    'provider_id' => $userData['provider_id'],
                ],
                [
                    'user_id' => $user->id,
                    'access_token' => null,
                    'refresh_token' => null,
                    'avatar' => $userData['avatar'] ?? null,
                ]
            );

            return $user->fresh('socialAccounts');
        });

        return $this->successResponse(
            'Google login successful.',
            [
                'user' => $user,
                'token' => $this->createApiToken($user, 'google-api-token'),
            ],
            201
        );
    }

    protected function googleWithAuthorizationCode(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'code' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        if ($request->header('X-Requested-With') !== 'XMLHttpRequest') {
            return response()->json([
                'status' => false,
                'message' => 'Invalid Google authorization request.',
                'errors' => [
                    'google' => [
                        'Missing expected request header for popup authorization flow.',
                    ],
                ],
            ], 400);
        }

        $tokenResponse = $this->exchangeGoogleAuthorizationCode(
            $request->string('code')->toString(),
            $request->getSchemeAndHttpHost()
        );

        if (! $tokenResponse['status']) {
            return response()->json([
                'status' => false,
                'message' => $tokenResponse['message'],
                'errors' => $tokenResponse['errors'] ?? null,
            ], $tokenResponse['code']);
        }

        $userData = $this->fetchGoogleUserFromAccessToken($tokenResponse['data']['access_token']);

        if (! $userData['status']) {
            return response()->json([
                'status' => false,
                'message' => $userData['message'],
                'errors' => $userData['errors'] ?? null,
            ], $userData['code']);
        }

        $googleProfile = $userData['data'];
        $oauthTokens = $tokenResponse['data'];

        $user = DB::transaction(function () use ($googleProfile, $oauthTokens) {
            $user = User::firstOrCreate(
                ['email' => $googleProfile['email']],
                [
                    'name' => $googleProfile['name'],
                    'password' => Hash::make(Str::random(32)),
                ]
            );

            $this->syncGoogleUserData($user, $googleProfile);

            $existingSocialAccount = SocialAccount::query()
                ->where('provider', 'google')
                ->where('provider_id', $googleProfile['provider_id'])
                ->first();

            SocialAccount::updateOrCreate(
                [
                    'provider' => 'google',
                    'provider_id' => $googleProfile['provider_id'],
                ],
                [
                    'user_id' => $user->id,
                    'access_token' => $oauthTokens['access_token'],
                    'refresh_token' => $oauthTokens['refresh_token'] ?? $existingSocialAccount?->refresh_token,
                    'avatar' => $googleProfile['avatar'] ?? null,
                ]
            );

            return $user->fresh('socialAccounts');
        });

        return $this->successResponse(
            'Google login successful.',
            [
                'user' => $user,
                'token' => $this->createApiToken($user, 'google-api-token'),
                'google_tokens' => [
                    'access_token' => $oauthTokens['access_token'],
                    'refresh_token' => $oauthTokens['refresh_token'] ?? null,
                    'expires_in' => $oauthTokens['expires_in'] ?? null,
                    'scope' => $oauthTokens['scope'] ?? null,
                    'token_type' => $oauthTokens['token_type'] ?? null,
                ],
            ],
            201
        );
    }

    public function instagram(Request $request): JsonResponse
    {
        return $this->registerSocial($request, 'instagram');
    }

    protected function registerSocial(Request $request, string $provider): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'provider_id' => [
                'required',
                'string',
                'max:255',
                Rule::unique('social_accounts', 'provider_id')->where(
                    fn ($query) => $query->where('provider', $provider)
                ),
            ],
            'access_token' => ['nullable', 'string'],
            'refresh_token' => ['nullable', 'string'],
            'avatar' => ['nullable', 'string', 'max:2048'],
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $validated = $validator->validated();

        $user = DB::transaction(function () use ($validated, $provider) {
            $user = User::firstOrCreate(
                ['email' => $validated['email']],
                [
                    'name' => $validated['name'],
                    'password' => Hash::make(Str::random(32)),
                ]
            );

            if (! $user->name && ! empty($validated['name'])) {
                $user->update(['name' => $validated['name']]);
            }

            SocialAccount::updateOrCreate(
                [
                    'provider' => $provider,
                    'provider_id' => $validated['provider_id'],
                ],
                [
                    'user_id' => $user->id,
                    'access_token' => $validated['access_token'] ?? null,
                    'refresh_token' => $validated['refresh_token'] ?? null,
                    'avatar' => $validated['avatar'] ?? null,
                ]
            );

            return $user->fresh('socialAccounts');
        });

        return $this->successResponse(
            ucfirst($provider) . ' registration successful.',
            [
                'user' => $user,
                'token' => $this->createApiToken($user, $provider . '-api-token'),
            ],
            201
        );
    }

    protected function verifyGoogleIdToken(string $idToken): array
    {
        $clientId = (string) config('services.google.client_id');
        $verifyOption = $this->googleHttpVerifyOption();

        if ($clientId === '') {
            return [
                'status' => false,
                'message' => 'Google client ID is not configured on the server.',
                'code' => 500,
            ];
        }

        try {
            $response = Http::timeout(10)
                ->withOptions(['verify' => $verifyOption])
                ->acceptJson()
                ->get('https://oauth2.googleapis.com/tokeninfo', [
                    'id_token' => $idToken,
                ])
                ->throw();
        } catch (RequestException | ConnectionException $exception) {
            return [
                'status' => false,
                'message' => 'Google token verification failed.',
                'errors' => [
                    'google' => [
                        $this->googleVerificationErrorMessage($exception),
                    ],
                ],
                'code' => 422,
            ];
        }

        $payload = $response->json();

        if (($payload['aud'] ?? null) !== $clientId) {
            return [
                'status' => false,
                'message' => 'Google token audience mismatch.',
                'errors' => [
                    'id_token' => [
                        'This Google token was not issued for the configured client.',
                    ],
                ],
                'code' => 422,
            ];
        }

        if (($payload['email_verified'] ?? 'false') !== 'true') {
            return [
                'status' => false,
                'message' => 'Google email is not verified.',
                'errors' => [
                    'email' => [
                        'Google account email must be verified.',
                    ],
                ],
                'code' => 422,
            ];
        }

        if (empty($payload['sub']) || empty($payload['email'])) {
            return [
                'status' => false,
                'message' => 'Incomplete Google profile data received.',
                'errors' => [
                    'google' => [
                        'Google did not return the required account details.',
                    ],
                ],
                'code' => 422,
            ];
        }

        return [
            'status' => true,
            'data' => [
                'provider_id' => $payload['sub'],
                'email' => $payload['email'],
                'name' => $payload['name'] ?? strtok($payload['email'], '@'),
                'avatar' => $payload['picture'] ?? null,
            ],
        ];
    }

    protected function exchangeGoogleAuthorizationCode(string $code, string $redirectUri): array
    {
        $clientId = (string) config('services.google.client_id');
        $clientSecret = (string) config('services.google.client_secret');
        $verifyOption = $this->googleHttpVerifyOption();

        if ($clientId === '' || $clientSecret === '') {
            return [
                'status' => false,
                'message' => 'Google OAuth client credentials are not configured on the server.',
                'errors' => [
                    'google' => [
                        'Set GOOGLE_CLIENT_ID and GOOGLE_CLIENT_SECRET before using Google OAuth token exchange.',
                    ],
                ],
                'code' => 500,
            ];
        }

        try {
            $response = Http::timeout(10)
                ->withOptions(['verify' => $verifyOption])
                ->asForm()
                ->acceptJson()
                ->post('https://oauth2.googleapis.com/token', [
                    'code' => $code,
                    'client_id' => $clientId,
                    'client_secret' => $clientSecret,
                    'redirect_uri' => $redirectUri,
                    'grant_type' => 'authorization_code',
                ])
                ->throw();
        } catch (RequestException | ConnectionException $exception) {
            return [
                'status' => false,
                'message' => 'Google authorization code exchange failed.',
                'errors' => [
                    'google' => [
                        $this->googleVerificationErrorMessage($exception),
                    ],
                ],
                'code' => 422,
            ];
        }

        $payload = $response->json();

        if (empty($payload['access_token'])) {
            return [
                'status' => false,
                'message' => 'Google token response was incomplete.',
                'errors' => [
                    'google' => [
                        'Google did not return an access token for this authorization code.',
                    ],
                ],
                'code' => 422,
            ];
        }

        return [
            'status' => true,
            'data' => [
                'access_token' => $payload['access_token'],
                'refresh_token' => $payload['refresh_token'] ?? null,
                'expires_in' => $payload['expires_in'] ?? null,
                'scope' => $payload['scope'] ?? null,
                'token_type' => $payload['token_type'] ?? null,
                'id_token' => $payload['id_token'] ?? null,
            ],
        ];
    }

    protected function fetchGoogleUserFromAccessToken(string $accessToken): array
    {
        try {
            $response = Http::timeout(10)
                ->withOptions(['verify' => $this->googleHttpVerifyOption()])
                ->acceptJson()
                ->withToken($accessToken)
                ->get('https://openidconnect.googleapis.com/v1/userinfo')
                ->throw();
        } catch (RequestException | ConnectionException $exception) {
            return [
                'status' => false,
                'message' => 'Unable to fetch Google user profile.',
                'errors' => [
                    'google' => [
                        $this->googleVerificationErrorMessage($exception),
                    ],
                ],
                'code' => 422,
            ];
        }

        $payload = $response->json();

        if (empty($payload['sub']) || empty($payload['email'])) {
            return [
                'status' => false,
                'message' => 'Incomplete Google profile data received.',
                'errors' => [
                    'google' => [
                        'Google did not return the required account details.',
                    ],
                ],
                'code' => 422,
            ];
        }

        return [
            'status' => true,
            'data' => [
                'provider_id' => $payload['sub'],
                'email' => $payload['email'],
                'name' => $payload['name'] ?? strtok($payload['email'], '@'),
                'avatar' => $payload['picture'] ?? null,
            ],
        ];
    }

    protected function googleHttpVerifyOption(): bool|string
    {
        $caBundle = (string) config('services.google.ca_bundle');

        if ($caBundle !== '') {
            return $caBundle;
        }

        $verifySsl = config('services.google.verify_ssl');

        if ($verifySsl === null || $verifySsl === '') {
            return ! app()->environment('local');
        }

        return filter_var($verifySsl, FILTER_VALIDATE_BOOL);
    }

    protected function googleVerificationErrorMessage(RequestException | ConnectionException $exception): string
    {
        $message = $exception->getMessage();

        if (str_contains($message, 'cURL error 60')) {
            return 'SSL certificate verify nahi ho raha. Local test ke liye GOOGLE_VERIFY_SSL=false set karo ya valid CA bundle path GOOGLE_CA_BUNDLE me do.';
        }

        return 'Unable to verify Google token. Please try again.';
    }

    protected function createApiToken(User $user, string $tokenName): ?string
    {
        
        if (! Schema::hasTable('personal_access_tokens')) {
            return null;
        }

        $user->tokens()->delete();

        return $user->createToken($tokenName)->plainTextToken;
    }

    protected function syncGoogleUserData(User $user, array $userData): void
    {
        $updates = [
            'name' => $userData['name'] ?? $user->name,
            'email' => $userData['email'] ?? $user->email,
            'email_verified_at' => now(),
            'last_login_at' => now(),
        ];

        if (Schema::hasColumn('users', 'profile_image')) {
            $updates['profile_image'] = $userData['avatar'] ?? $user->profile_image;
        }

        if (Schema::hasColumn('users', 'login_provider')) {
            $updates['login_provider'] = 'google';
        }

        if (Schema::hasColumn('users', 'provider_id')) {
            $updates['provider_id'] = $userData['provider_id'];
        }

        if (Schema::hasColumn('users', 'google_id')) {
            $updates['google_id'] = $userData['provider_id'];
        }

        $user->forceFill($updates)->save();
    }

    protected function validationErrorResponse($errors): JsonResponse
    {
        return response()->json([
            'status' => false,
            'message' => 'Validation failed.',
            'errors' => $errors,
        ], 422);
    }

    protected function successResponse(string $message, array $data = [], int $statusCode = 200): JsonResponse
    {
        if (array_key_exists('token', $data) && $data['token'] === null) {
            $message .= ' User created, but API token was not generated because the personal_access_tokens table is missing.';
        }

        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }
}
