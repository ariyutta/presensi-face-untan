<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\IClockTransaction;
use App\Models\PersonnelEmployee;
use Carbon\Carbon;
use Illuminate\Http\Request;

class KehadiranController extends Controller
{
    function index(Request $request)
    {
        $periode = explode(' - ', $request->periode);

        if (count($periode) == 2) {
            $startDate = $periode[0];
            $endDate = $periode[1];
        } else if (count($periode) == 1) {
            $startDate = $periode[0];
            $endDate = $periode[0];
        } else {
            $startDate = date('Y-m-d') . ' 00:00:00';
            $endDate =  date('Y-m-d') . ' 23:59:59';
        }

        $punches = IClockTransaction::orderBy('punch_time', 'desc')->orderBy('emp_code');

        if ($request->username) {
            $punches = $punches->whereHas('pegawai', function ($q) use ($request) {
                $q->whereRaw('LOWER(first_name) LIKE ?', ['%' . strtolower($request->username) . '%'])
                    ->orWhere('last_name', 'LIKE', '%' . $request->username . '%');
            });
        }

        $punches = $punches->whereBetween('punch_time', [$startDate, $endDate])->get();

        $employeeData = [];

        foreach ($punches as $punch) {
            $empCode = $punch->emp_code;
            $punchTime = Carbon::parse($punch->punch_time);
            $dateKey = $punchTime->toDateString();

            $employee = PersonnelEmployee::where('emp_code', $empCode)->first();

            if ($employee) {
                $employeeName = $employee->first_name;
                $employeeUsername = $employee->last_name;
                $department = $employee->department->dept_name;

                if (!isset($employeeData[$dateKey][$empCode])) {
                    $employeeData[$dateKey][$empCode] = [
                        'username' => $employeeUsername,
                        'nama_pegawai' => $employeeName,
                        'unit_departement' => $department,
                        'tanggal' => $punchTime->format('d/m/Y'),
                        'jam_keluar' => $punchTime->format('H:i:s') . ' WIB',
                        'jam_masuk' => $punchTime->format('H:i:s') . ' WIB',
                        'total_waktu' => 0,
                    ];
                } else {
                    $employeeData[$dateKey][$empCode]['jam_masuk'] = $punchTime->format('H:i:s') . ' WIB';
                }
            }
        }

        foreach ($employeeData as &$dateData) {
            foreach ($dateData as &$data) {
                $jamMasuk = Carbon::createFromFormat('H:i:s', substr($data['jam_masuk'], 0, -4));
                $jamKeluar = Carbon::createFromFormat('H:i:s', substr($data['jam_keluar'], 0, -4));

                $totalMenit = $jamMasuk->diffInMinutes($jamKeluar);

                if ($totalMenit >= 1440) {
                    $hari = floor($totalMenit / 1440);
                    $sisaMenit = $totalMenit % 1440;
                    $jam = floor($sisaMenit / 60);
                    $menit = $sisaMenit % 60;
                    $data['total_waktu'] = $hari . ' Hari ' . $jam . ' Jam ' . $menit . ' Menit';
                } elseif ($totalMenit >= 60) {
                    $jam = floor($totalMenit / 60);
                    $menit = $totalMenit % 60;
                    $data['total_waktu'] = $jam . ' Jam ' . $menit . ' Menit';
                } elseif ($totalMenit >= 1) {
                    $data['total_waktu'] = $totalMenit . ' Menit';
                } else {
                    $totalDetik = $totalMenit * 60;
                    $data['total_waktu'] = $totalDetik . ' Detik';
                }
            }
        }

        return response()->json($employeeData);
    }

    function transaksi_kehadiran()
    {
        $strSQL = IClockTransaction::select('id', 'emp_code', 'punch_time', 'terminal_alias', 'area_alias')->groupBy('punch_time')->get();

        return response()->json($strSQL);
    }
}
