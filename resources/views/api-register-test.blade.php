<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Login Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://accounts.google.com/gsi/client" async defer></script>
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-semibold">Google Login Test</h1>
            <p class="mt-2 text-sm text-slate-600">Yahan se Google OAuth code flow test hoga. Is flow se backend `access_token` aur `refresh_token` store kar sakta hai, aur user data bhi Google profile se sync hota hai.</p>
        </div>

        <div class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="rounded-2xl border border-sky-100 bg-sky-50 p-4 text-sm text-sky-900">
                    <p class="font-medium">Required setup</p>
                    <p class="mt-1">`.env` me `GOOGLE_CLIENT_ID` aur `GOOGLE_CLIENT_SECRET` add karo. Google consent ke baad backend auth code exchange karke tokens store karega.</p>
                </div>

                <div class="mt-5 space-y-4">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Configured Client ID</label>
                        <input type="text" value="{{ config('services.google.client_id') }}" readonly class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600 outline-none">
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Configured Client Secret</label>
                        <input type="text" value="{{ config('services.google.client_secret') ? 'Configured' : '' }}" readonly class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600 outline-none" placeholder="Not configured">
                    </div>

                    <div id="google-config-warning" class="hidden rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                        `GOOGLE_CLIENT_ID` ya `GOOGLE_CLIENT_SECRET` missing hai. Pehle `.env` me add karo, phir page refresh karo.
                    </div>

                    <div id="google-button-wrapper" class="rounded-2xl border border-slate-200 bg-white p-4">
                        <button id="google-auth-btn" type="button" class="inline-flex rounded-xl bg-sky-600 px-5 py-3 text-sm font-medium text-white transition hover:bg-sky-700">
                            Continue with Google
                        </button>
                    </div>

                    <div>
                        <label for="code-preview" class="mb-1 block text-sm font-medium text-slate-700">Last Google Authorization Code</label>
                        <textarea id="code-preview" rows="5" readonly class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-xs text-slate-600 outline-none" placeholder="Google consent ke baad auth code yahan preview hoga."></textarea>
                    </div>
                </div>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-4 flex items-center justify-between gap-3">
                    <h2 class="text-lg font-semibold">Response</h2>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">POST /api/register/google</span>
                </div>

                <div id="status-box" class="mb-4 hidden rounded-xl border px-4 py-3 text-sm"></div>
                <pre id="response-box" class="min-h-[420px] overflow-auto rounded-2xl bg-slate-950 p-4 text-xs leading-6 text-slate-100">{}</pre>
            </section>
        </div>
    </div>

    <script>
        (() => {
            const clientId = @json(config('services.google.client_id'));
            const clientSecretConfigured = @json((bool) config('services.google.client_secret'));
            const googleRegisterUrl = @json(url('api/register/google'));
            const configWarning = document.getElementById('google-config-warning');
            const statusBox = document.getElementById('status-box');
            const responseBox = document.getElementById('response-box');
            const codePreview = document.getElementById('code-preview');
            const googleAuthButton = document.getElementById('google-auth-btn');

            const setStatusBase = () => {
                statusBox.className = 'mb-4 hidden rounded-xl border px-4 py-3 text-sm';
            };

            const renderResponse = async (authorizationCode) => {
                setStatusBase();
                codePreview.value = authorizationCode;

                try {
                    const response = await fetch(googleRegisterUrl, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: JSON.stringify({
                            code: authorizationCode,
                        }),
                    });

                    const responseText = await response.text();
                    let result;

                    try {
                        result = JSON.parse(responseText);
                    } catch (parseError) {
                        throw new Error(`Expected JSON but received: ${responseText.substring(0, 200)}`);
                    }

                    statusBox.classList.remove('hidden');
                    statusBox.classList.add(...(response.ok
                        ? ['border-emerald-200', 'bg-emerald-50', 'text-emerald-700']
                        : ['border-rose-200', 'bg-rose-50', 'text-rose-700']));
                    statusBox.textContent = result.message || (response.ok ? 'Request successful.' : 'Request failed.');
                    responseBox.textContent = JSON.stringify(result, null, 2);
                } catch (error) {
                    statusBox.classList.remove('hidden');
                    statusBox.classList.add('border-rose-200', 'bg-rose-50', 'text-rose-700');
                    statusBox.textContent = 'Network or server error.';
                    responseBox.textContent = JSON.stringify({
                        status: false,
                        message: error.message
                    }, null, 2);
                }
            };

            if (!clientId || !clientSecretConfigured) {
                configWarning.classList.remove('hidden');
                return;
            }

            const initializeGoogle = () => {
                if (!window.google?.accounts?.oauth2) {
                    window.setTimeout(initializeGoogle, 200);
                    return;
                }

                const codeClient = window.google.accounts.oauth2.initCodeClient({
                    client_id: clientId,
                    scope: 'openid email profile',
                    ux_mode: 'popup',
                    redirect_uri: window.location.origin,
                    prompt: 'consent',
                    access_type: 'offline',
                    callback: (response) => {
                        if (!response.code) {
                            setStatusBase();
                            statusBox.classList.remove('hidden');
                            statusBox.classList.add('border-rose-200', 'bg-rose-50', 'text-rose-700');
                            statusBox.textContent = 'Google authorization code receive nahi hua.';
                            return;
                        }

                        renderResponse(response.code);
                    },
                });

                googleAuthButton.addEventListener('click', () => codeClient.requestCode());
            };

            initializeGoogle();
        })();
    </script>
</body>
</html>
