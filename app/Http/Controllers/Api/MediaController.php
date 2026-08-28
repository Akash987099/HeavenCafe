<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class MediaController extends Controller
{
    public function show($path)
    {
        $decodedPath = rawurldecode($path);
        $relativePath = ltrim(str_replace('\\', '/', $decodedPath), '/');
        $publicRoot = realpath(public_path());
        $absolutePath = realpath(public_path($relativePath));

        if (!$publicRoot || !$absolutePath || !str_starts_with($absolutePath, $publicRoot) || !is_file($absolutePath)) {
            abort(404, 'File not found');
        }

        return response()->file($absolutePath, [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, OPTIONS',
            'Access-Control-Allow-Headers' => 'Origin, X-Requested-With, Content-Type, Accept, Authorization',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }
}
