<?php

namespace App\Http\Controllers\User;

use App\Actions\User\FileUploadAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserFileUploadRequest;
use App\Models\UserFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserFileController extends Controller
{
    public function upload(UserFileUploadRequest $request, FileUploadAction $action) {
        $user = Auth::user();
        $path = $action($request->file, $user['id']);

        if(UserFile::query()->where('user_id', $user['id'])->where('type', $request->type)->exists()) {
            return response()->json(['result' => false, 'msg' => 'Файл уже существует, перезагрузите страницу'], 420);
        }

        $file = UserFile::query()->create([
            'path' => $path,
            'user_id' => $user['id'],
            'type' => $request->type,
            'status' => 'edit',
        ]);

        return [
            'result' => true,
            'file' => $file,
        ];
    }

    public function docs()
    {
        $user = Auth::user();
        $files = UserFile::query()->where('user_id', $user['id'])->get();

        return [
            'result' => true,
            'files' => $files,
        ];
    }

    public function destroy($type)
    {
        $user = Auth::user();
        $file = UserFile::query()->where('user_id', $user['id'])->where('type', $type)->first();

        if($file && $file->status !== 'approve') {
            Storage::disk('public')->delete($file->path);
            $file->delete();

            return ['result' => true];
        }

        return ['result' => false];
    }
}
