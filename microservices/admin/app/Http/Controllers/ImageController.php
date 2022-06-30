<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImageUploadRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageController
{
    /**
     * @param ImageUploadRequest $request
     *
     * @return array
     */
    public function upload(ImageUploadRequest $request): array
    {
        $file = $request->file('image');
        $name = sprintf('%s.%s', Str::random(10), $file->extension());

        return [
            'url' => sprintf('%s/%s', env('APP_URL'), Storage::putFileAs('images', $file, $name)),
        ];
    }
}
