<?php

namespace App\Actions\User;

use Illuminate\Support\Facades\Storage;

class FileUploadAction
{
    public function __invoke($file, $userId)
    {
        if (!($file->storeAs('/users/'.$userId, $file->getClientOriginalName(), 'public'))) {
            return abort(500, 'Не можем сохранить файл');
        }

        $newPath = '/users/'.$userId.'/'.uniqid().'.'.$file->getClientOriginalExtension();

        Storage::disk('public')->move('/users/'.$userId.'/'.$file->getClientOriginalName(), $newPath);

        return $newPath;
    }
}
