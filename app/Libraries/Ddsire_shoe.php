<?php

namespace App\Libraries;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class Ddsire_shoe
{
    protected string $endpoint = 'https://neuromayankag.com/shoes/api/products';
    protected string $cookie = 'ci_session=a910b1ac00bb2de96664f06b12ca537d';

    public function fetchProducts(array $query = []): array
    {
        $response = Http::acceptJson()
            ->withHeaders([
                'Cookie' => $this->cookie,
            ])
            ->timeout(60)
            ->get($this->endpoint, $query);

        if (!$response->successful()) {
            throw new \RuntimeException('Failed to fetch products from remote API.');
        }

        return $response->json();
    }

    public function downloadImage(?string $url, string $directory = 'product'): ?string
    {
        if (empty($url)) {
            return null;
        }

        $url = $this->normalizeUrl($url);

        $response = Http::timeout(60)->get($url);

        if (!$response->successful()) {
            return null;
        }

        $extension = pathinfo(parse_url($url, PHP_URL_PATH) ?? '', PATHINFO_EXTENSION) ?: 'jpg';
        $fileName = time() . '_' . Str::random(12) . '.' . strtolower($extension);
        $targetDirectory = public_path($directory);

        if (!is_dir($targetDirectory)) {
            mkdir($targetDirectory, 0777, true);
        }

        file_put_contents($targetDirectory . DIRECTORY_SEPARATOR . $fileName, $response->body());

        return $directory . '/' . $fileName;
    }

    protected function normalizeUrl(string $url): string
    {
        return preg_replace('#(?<!:)/{2,}#', '/', trim($url)) ?? trim($url);
    }
}
