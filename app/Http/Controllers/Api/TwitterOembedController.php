<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Client\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TwitterOembedController extends Controller
{
    /**
     * Twitter Oembed-Informationen abrufen
     */
    public function __invoke(Request $request): Response|JsonResponse
    {
        $apiUrl = 'https://publish.twitter.com/oembed?url='.$request->url;
        $apiUrl .= '&theme='.$request->theme;
        $apiUrl .= '&omit_script=true';

        $response = Http::get($apiUrl);

        return $response->successful()
            ? $response
            : response()->json(['html' => '<p style="font-size:1.5em;">' . __('Twitter link encountered an error...'). ' ??' . '</p>'], 400);
    }
}
