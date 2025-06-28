<?php

use App\Models\Foto;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

Route::get('/label-images', function () {
    $data = [];

    $fotoList = Foto::all();

    foreach ($fotoList as $foto) {
        $filename = $foto->file_foto;
        $slug = $foto->slug_foto;
        $path = public_path("file/foto_album/{$filename}");

        // Lewati jika file tidak ada di folder
        if (!File::exists($path)) {
            continue;
        }

        // Label: gunakan nama file tanpa ekstensi, lowercase
        $label = strtolower(pathinfo($filename, PATHINFO_FILENAME));
        $url = asset("file/foto_album/{$filename}");

        $data[$label][] = [
            'url' => $url,
            'slug' => $slug
        ];
    }

    return response()->json($data);
});
