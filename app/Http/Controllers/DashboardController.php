<?php

namespace App\Http\Controllers;

use App\Models\IClockTransaction;
use App\Models\PersonnelEmployee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Kesekretariat
        $UPT_Bahasa = DB::table('personnel_employee')->where('department_id', 33)->count();
        $UPT_TIK = DB::table('personnel_employee')->where('department_id', 34)->count();
        $UPT_Perpus = DB::table('personnel_employee')->where('department_id', 44)->count();
        $BAK = DB::table('personnel_employee')->where('department_id', 37)->count();
        $BUK_Umum = DB::table('personnel_employee')->where('department_id', 35)->count();
        $BUK_Keuangan = DB::table('personnel_employee')->where('department_id', 38)->count();
        $BUK_Kumpeg = DB::table('personnel_employee')->where('department_id', 39)->count();
        $LPPKM = DB::table('personnel_employee')->where('department_id', 40)->count();
        $BPKHM = DB::table('personnel_employee')->where('department_id', 36)->count();
        $LPPPM = DB::table('personnel_employee')->where('department_id', 41)->count();
        $Lab_Terpadu = DB::table('personnel_employee')->where('department_id', 42)->count();
        $Rumkit = DB::table('personnel_employee')->where('department_id', 43)->count();
        $Klinik = DB::table('personnel_employee')->where('department_id', 47)->count();

        // Fakultas
        $Fak_Hukum = DB::table('personnel_employee')->where('department_id', 50)->count();
        $Fak_Ekonomi = DB::table('personnel_employee')->where('department_id', 45)->count();
        $Fak_Pertanian = DB::table('personnel_employee')->where('department_id', 51)->count();
        $Fak_Teknik = DB::table('personnel_employee')->where('department_id', 52)->count();
        $Fak_ISIP = DB::table('personnel_employee')->where('department_id', 53)->count();
        $Fak_IKIP = DB::table('personnel_employee')->where('department_id', 46)->count();
        $Fak_Kehutanan = DB::table('personnel_employee')->where('department_id', 54)->count();
        $Fak_MIPA = DB::table('personnel_employee')->where('department_id', 55)->count();
        $Fak_Kedokteran = DB::table('personnel_employee')->where('department_id', 48)->count();

        $countDept = [
            // Kesekretariat
            'upt_bahasa' => $UPT_Bahasa,
            'upt_tik' => $UPT_TIK,
            'upt_perpus' => $UPT_Perpus,
            'bak' => $BAK,
            'buk_umum' => $BUK_Umum,
            'buk_keuangan' => $BUK_Keuangan,
            'buk_kumpeg' => $BUK_Kumpeg,
            'lppkm' => $LPPKM,
            'bpkhm' => $BPKHM,
            'lpppm' => $LPPPM,
            'lab_terpadu' => $Lab_Terpadu,
            'rumkit' => $Rumkit,
            'klinik' => $Klinik,
            'rektorat' => 0,

            // Fakultas
            'fak_hukum' => $Fak_Hukum,
            'fak_ekonomi' => $Fak_Ekonomi,
            'fak_pertanian' => $Fak_Pertanian,
            'fak_teknik' => $Fak_Teknik,
            'fak_isip' => $Fak_ISIP,
            'fak_kip' => $Fak_IKIP,
            'fak_kehutanan' => $Fak_Kehutanan,
            'fak_mipa' => $Fak_MIPA,
            'fak_kedokteran' => $Fak_Kedokteran
        ];

        // $datenow = Carbon::now()->format('Y-m-d');
        // return $absen_today = IClockTransaction::with('')->whereBetween('punch_time', [$datenow . ' 00:00:00', $datenow . ' 23:59:59'])->get();

        return view('dashboard', compact('countDept'));
    }
}
