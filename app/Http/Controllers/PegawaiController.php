<?php

namespace App\Http\Controllers;

use App\Models\PersonnelArea;
use App\Models\PersonnelEmployee;
use Illuminate\Support\Facades\Request;

class PegawaiController extends Controller
{
    public function index()
    {
        return view('pegawai');
    }

    public function getData()
    {
        $strSQL = PersonnelEmployee::with('department', 'position', 'area_pegawai.area')->orderBy('emp_code', 'asc')->get();

        return response()->json($strSQL);
    }
}
