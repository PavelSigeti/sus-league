<?php

namespace App\Actions\Admin;

use Illuminate\Support\Facades\Storage;

class StageFileAction
{
    public function __invoke($file, $id)
    {
        if (!($file->storeAs('/stage/'.$id, $file->getClientOriginalName(), 'public'))) {
            return abort(500, 'Не можем сохранить файл');
        }

        $newPath = '/stage/'.$id.'/'.uniqid().'.'.$file->getClientOriginalExtension();

        Storage::disk('public')->move('/stage/'.$id.'/'.$file->getClientOriginalName(), $newPath);

        return $newPath;
    }
}
