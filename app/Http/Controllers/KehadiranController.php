<?php

namespace App\Http\Controllers;

use App\Models\IClockTransaction;
use App\Models\PersonnelDepartment;
use App\Models\PersonnelEmployee;
use Carbon\Carbon;
use Illuminate\Http\Request;

class KehadiranController extends Controller
{
    public function index()
    {
        $unit = PersonnelDepartment::where('id', '!=', 1)->orderBy('dept_name', 'asc')->get();

        return view('kehadiran', compact('unit'));
    }

    public function getData()
    {
        $punches = IClockTransaction::orderBy('punch_time', 'desc')
            ->orderBy('emp_code')
            ->get();

        $employeeData = [];

        foreach ($punches as $punch) {
            $empCode = $punch->emp_code;
            $punchTime = Carbon::parse($punch->punch_time);
            $dateKey = $punchTime->toDateString();

            // Ambil data pegawai berdasarkan kode pegawai (emp_code)
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
                        'jam_keluar' => $punchTime->format('H:i:s') . ' WIB', // Tambahkan ' WIB' di ujung format jam keluar
                        'jam_masuk' => $punchTime->format('H:i:s') . ' WIB', // Tambahkan ' WIB' di ujung format jam masuk
                        'total_waktu' => 0,
                    ];
                } else {
                    $employeeData[$dateKey][$empCode]['jam_masuk'] = $punchTime->format('H:i:s') . ' WIB'; // Tambahkan ' WIB' di ujung format jam masuk
                }
            }
        }

        foreach ($employeeData as &$dateData) {
            foreach ($dateData as &$data) {
                $jamMasuk = Carbon::createFromFormat('H:i:s', substr($data['jam_masuk'], 0, -4)); // Kurangi 4 karakter dari akhir untuk menghilangkan ' WIB'
                $jamKeluar = Carbon::createFromFormat('H:i:s', substr($data['jam_keluar'], 0, -4)); // Kurangi 4 karakter dari akhir untuk menghilangkan ' WIB'

                $totalMenit = $jamMasuk->diffInMinutes($jamKeluar);

                if ($totalMenit >= 1440) { // Jika total waktu di atas 24 jam
                    $hari = floor($totalMenit / 1440);
                    $sisaMenit = $totalMenit % 1440;
                    $jam = floor($sisaMenit / 60);
                    $menit = $sisaMenit % 60;
                    $data['total_waktu'] = $hari . ' Hari ' . $jam . ' Jam ' . $menit . ' Menit';
                } elseif ($totalMenit >= 60) { // Jika total waktu di atas 1 jam
                    $jam = floor($totalMenit / 60);
                    $menit = $totalMenit % 60;
                    $data['total_waktu'] = $jam . ' Jam ' . $menit . ' Menit';
                } elseif ($totalMenit >= 1) { // Jika total waktu di atas 1 menit
                    $data['total_waktu'] = $totalMenit . ' Menit';
                } else { // Jika total waktu di bawah 1 menit
                    $totalDetik = $totalMenit * 60;
                    $data['total_waktu'] = $totalDetik . ' Detik';
                }
            }
        }

        return response()->json($employeeData);
    }
}
