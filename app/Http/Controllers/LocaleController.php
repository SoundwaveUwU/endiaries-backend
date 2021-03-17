<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;

class LocaleController extends Controller
{
    function show(Request $request, $locale)
    {
        if (!File::exists(resource_path("lang/{$locale}.json"))) {
            abort(404);
        }

        $mtime = filemtime(resource_path("lang/{$locale}.json"));

        if ($request->hasHeader('If-Modified-Since'))
        {
            if (Carbon::parse($request->header('If-Modified-Since'))->timestamp <= $mtime)
            {
                return response(null, Response::HTTP_NOT_MODIFIED);
            }
        }

        return response()
            ->download(resource_path("lang/{$locale}.json"), null, [
                'Last-Modified' => Carbon::createFromTimestamp($mtime)->toRfc7231String(),
            ]);
    }
}
