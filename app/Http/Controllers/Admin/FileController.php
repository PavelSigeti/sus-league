<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function show($status)
    {
        $files = UserFile::query()->where('status', $status)->with('user')->orderBy('user_id')->get();

        return [
            'result' => true,
            'files' => $files,
        ];
    }

    public function approve($id)
    {
        $file = UserFile::query()->find($id);
        $file->update([
            'status' => 'approve',
        ]);

        return ['result' => true];
    }

    public function cancel($id)
    {
        $file = UserFile::query()->find($id);
        $file->update([
            'status' => 'cancel',
        ]);

        return ['result' => true];
    }

    public function destroy($id)
    {
        $file = UserFile::query()->find($id);

        if($file) {
            Storage::disk('public')->delete($file->path);
            $file->delete();

            return ['result' => true];
        }

        return ['result' => false];
    }
}
