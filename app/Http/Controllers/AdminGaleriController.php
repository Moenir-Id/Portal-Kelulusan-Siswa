<?php

namespace App\Http\Controllers;

use App\Models\Momen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminGaleriController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $momens = Momen::with('siswaAccount.siswa')->latest()->paginate(16);
        return view('admin.galeri', compact('momens'));
    }

    public function destroy(Momen $momen)
    {
        Storage::disk('public')->delete($momen->foto);
        $momen->delete();
        return back()->with('success', 'Foto dihapus.');
    }
}
