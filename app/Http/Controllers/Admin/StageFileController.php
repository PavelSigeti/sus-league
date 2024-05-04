<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Admin\StageFileAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\StageFileRequest;
use App\Models\StageFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StageFileController extends Controller
{
    public function store(StageFileRequest $request, StageFileAction $action)
    {
        $path = $action($request->file, $request->id);

        $file = StageFile::query()->create([
            'path' => $path,
            'stage_id' => $request->id,
            'title' => $request->title,
            'status' => 'edit',
        ]);

        return ['result' => true, 'file' => $file];
    }

    public function getStageFiles($id)
    {
        $files = StageFile::query()->where('stage_id', $id)->get();

        return [
            'result' => true,
            'files' => $files,
        ];
    }

    public function destroy($id)
    {
        $file = StageFile::query()->find($id);

        if($file) {
            Storage::disk('public')->delete($file->path);
            $file->delete();

            return ['result' => true];
        }

        return ['result' => false];
    }
}
