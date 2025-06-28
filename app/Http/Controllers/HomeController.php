<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Konsentrasi;
use App\Models\Project;
use App\Models\Setting;
use App\Models\Kegiatan;
use App\Models\Diskon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = "Welcome";
        $konf = DB::table('setting')->first();

        return view('welcome', compact('title', 'konf'));
    }
}
