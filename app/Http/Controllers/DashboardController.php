<?php

namespace App\Http\Controllers;

use App\Models\PersonnelEmployee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $UPT_Bahasa = DB::table('personnel_employee')->where('department_id', 33)->count();
        $UPT_TIK = DB::table('personnel_employee')->where('department_id', 34)->count();
        $BAK = DB::table('personnel_employee')->where('department_id', 37)->count();
        $BUK_Umum = DB::table('personnel_employee')->where('department_id', 35)->count();
        $BUK_Keuangan = DB::table('personnel_employee')->where('department_id', 38)->count();
        $BUK_Kumpeg = DB::table('personnel_employee')->where('department_id', 39)->count();
        $LPPKM = DB::table('personnel_employee')->where('department_id', 40)->count();
        $BPKHM = DB::table('personnel_employee')->where('department_id', 36)->count();
        $LPPPM = DB::table('personnel_employee')->where('department_id', 41)->count();
        $Lab_Terpadu = DB::table('personnel_employee')->where('department_id', 42)->count();
        $Rumkit = DB::table('personnel_employee')->where('department_id', 43)->count();

        $countDept = [
            'upt_bahasa' => $UPT_Bahasa,
            'upt_tik' => $UPT_TIK,
            'bak' => $BAK,
            'buk_umum' => $BUK_Umum,
            'buk_keuangan' => $BUK_Keuangan,
            'buk_kumpeg' => $BUK_Kumpeg,
            'lppkm' => $LPPKM,
            'bpkhm' => $BPKHM,
            'lpppm' => $LPPPM,
            'lab_terpadu' => $Lab_Terpadu,
            'rumkit' => $Rumkit,
            'klinik' => 0,
        ];

        return view('dashboard', compact('countDept'));
    }
}
