<?php

namespace App\Http\Controllers;


use App\Models\Foto;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;

class FotoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (auth()->user()->id == 1) {
            // Admin: ambil semua foto
            $foto = Foto::all();
        } else {
            // User biasa: hanya foto milik sendiri
            $foto = Foto::where('user_id', auth()->id())->get();
        }

        $title = 'Data Foto';
        return view('foto.index', compact('title', 'foto'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $foto = Foto::all();
        $title = 'Tambah Foto';
        return view('foto.create', compact('foto', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'file_foto' => 'required|mimes:jpg,jpeg,png,gif,raw,tiff',
            'harga_foto' => 'required|numeric|min:0',
        ]);


        $file = $request->file('file_foto');
        $namafile = auth()->user()->name . date('Ymdhis') . '.' . $file->getClientOriginalExtension();
        $file->move('file/foto_album/', $namafile);

        $foto = new Foto();
        $foto->nama_foto = 'Foto ' . auth()->user()->name;
        $foto->file_foto = $namafile;
        $foto->slug_foto = Str::slug($foto->file_foto);
        $foto->user_id = auth()->id();
        $foto->harga_foto = $request->harga_foto ?? 0; // tambahkan ini
        $foto->save();



        return redirect()->route('foto.index')->with('Sukses', 'Berhasil Tambah Foto');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $foto = DB::table('foto')
            ->where('foto.id_foto', $id)
            ->first();
        $title = 'Detail Foto';
        return view('foto.show', compact('title', 'foto'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $foto = Foto::findOrFail($id);

        // Jika bukan admin dan bukan pemilik foto, abort
        if (auth()->user()->id != 1 && $foto->user_id != auth()->id()) {
            abort(403, 'Anda tidak punya akses ke foto ini');
        }

        $title = 'Edit Foto';
        return view('foto.edit', compact('foto', 'title'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $foto = Foto::findOrFail($id);
        $namafile = $foto->file_foto; // default pakai nama file lama

        // Jika ada file baru diupload
        if ($request->hasFile('file_foto')) {
            $request->validate([
                'file_foto' => 'mimes:jpg,jpeg,png,gif,raw,tiff',
                'harga_foto' => 'required|numeric|min:0',
            ]);

            // Hapus file lama (opsional, jika mau bersih)
            $oldPath = public_path('file/foto_album/' . $namafile);
            if (file_exists($oldPath)) {
                @unlink($oldPath);
            }

            // Simpan file baru
            $file = $request->file('file_foto');
            $namafile = auth()->user()->name . date('Ymdhis') . '.' . $file->getClientOriginalExtension();
            $file->move('file/foto_album', $namafile);
        }

        // Update data
        $foto->update([
            'nama_foto' => $request->nama_foto,
            'file_foto' => $namafile,
            'slug_foto' => Str::slug(pathinfo($namafile, PATHINFO_FILENAME)), // update slug dari nama file (tanpa ekstensi)
            'harga_foto' => $request->harga_foto,
        ]);

        return redirect()->route('foto.index')->with('Sukses', 'Berhasil Edit Foto');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $foto = Foto::findOrFail($id);
        $namafile = $foto->file_foto;
        $filepath = public_path('file/foto_album/' . $namafile);

        // Cek apakah file ada di folder
        if (file_exists($filepath)) {
            @unlink($filepath); // hapus file dari folder
        }

        // Hapus dari database
        $foto->delete();

        return redirect()->back()->with('Sukses', 'Berhasil Hapus Data Foto');
    }


    public function print($id)
    {
        $foto = DB::table('foto')
            ->where('foto.id_foto', $id)
            ->first();
        return view('foto.cetak-kartu', compact('foto'));
    }

    public function keranjang($slug)
    {
        $konf = DB::table('setting')->first();
        $foto = Foto::where('slug_foto', $slug)->firstOrFail();
        $title = 'Keranjang Foto';
        return view('foto.show-keranjang', compact('foto', 'title', 'konf'));
    }

    
}
