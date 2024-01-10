<?php

namespace App\Services;

use Illuminate\Support\Str;
use App\Models\File as FileModel;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class FileService
{
    public function uploadFile($file, $path)
    {
        $fileName = Str::random(60);
        $extension = $file->getClientOriginalExtension();
        $size = round($file->getSize() / 1024);
        $pathName = '/storage/file/' . $path . '/' . $fileName . '.' . $extension;

        Storage::put('/public/file/' . $path . '/' . $fileName . '.' . $extension, File::get($file));

        $data = FileModel::create([
            'url' => config('app.file_upload_endpoint'),
            'path_name' => $pathName,
            'file_name' => $fileName,
            'extension' => $extension,
            'size' => $size
        ]);

        return $data;
    }

    public function storeFile($file, $path)
    {
        $fileName = time() . '-' . Str::random(60);
        $extension = File::extension($file);
        $size = round(File::size($file) / 1024);
        $pathName = '/storage/file/' . $path . '/' . $fileName . '.' . $extension;

        Storage::put('/public/file/' . $path . '/' . $fileName . '.' . $extension, File::get($file));

        $data = FileModel::create([
            'url' => config('app.file_upload_endpoint'),
            'path_name' => $pathName,
            'file_name' => $fileName,
            'extension' => $extension,
            'size' => $size
        ]);

        return $data;
    }

    public function getFileById($id)
    {
        $file = FileModel::findOrFail($id);

        return $file;
    }

    public function deleteFileById($id)
    {
        $file = FileModel::findOrFail($id);

        Storage::delete(str_replace('storage', 'public', $file->path_name));

        $file->delete();

        return $file;
    }
}
