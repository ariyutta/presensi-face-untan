<?php

namespace App\Http\Controllers;

use App\Models\IClockTransaction;
use App\Models\PersonnelDepartment;
use App\Models\PersonnelEmployee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class KehadiranController extends Controller
{
    public function index()
    {
        $unit = PersonnelDepartment::where('id', '!=', 1)->orderBy('dept_name', 'asc')->get();

        return view('kehadiran', compact('unit'));
    }

    function newIndex()
    {
        $unit = PersonnelDepartment::where('id', '!=', 1)->orderBy('dept_name', 'asc')->get();

        return view('kehadiran.index', compact('unit'));
    }

    function newData(Request $request)
    {
        $nama_pegawai = $request->nama_pegawai;
        $unit_kerja = $request->unit_kerja;
        $tanggal = $request->tanggal;

        // Buat query utk filter

        if ($nama_pegawai == '' && $unit_kerja == '' && $tanggal == '') {
            $data = [];
        }

        if ($nama_pegawai == 'arbi') {
            for ($i = 0; $i < 100; $i++) {
                $data[$i] = ['id_pegawai' => 1, 'username' => 'arbi.wibu', 'nama_pegawai' => 'Arbi Yudh', 'unit' => 'UPT TIK', 'tanggal' => '12-12-2023', 'jam_masuk' => '07.00', 'jam_keluar' => '16.00', 'total_waktu' => 7];
            }
            // $data = ['id_pegawai' => 1, 'username' => 'arbi.wibu', 'nama_pegawai' => 'Arbi Yudh', 'unit' => 'UPT TIK', 'tanggal' => '12-12-2023', 'jam_masuk' => '07.00', 'jam_keluar' => '16.00', 'total_waktu', 7];
        }
        if ($unit_kerja == 1) {
            for ($i = 0; $i < 100; $i++) {
                $data[$i] = ['id_pegawai' => 2, 'username' => 'arbi.cabul', 'nama_pegawai' => 'Arbi Cabul', 'unit' => 'UPT Perpustakaan', 'tanggal' => '01-02-2023', 'jam_masuk' => '07.59', 'jam_keluar' => '16.00', 'total_waktu' => 9];
            }
            // $data = ['id_pegawai' => 2, 'username' => 'arbi.cabul', 'nama_pegawai' => 'Arbi Cabul', 'unit' => 'UPT Perpustakaan', 'tanggal' => '01-02-2023', 'jam_masuk' => '07.59', 'jam_keluar' => '16.00', 'total_waktu', 9];
        }
        if ($tanggal == '2024-03-06') {
            for ($i = 0; $i < 100; $i++) {
                $data[$i] = ['id_pegawai' => 1, 'username' => 'arbi.wibu', 'nama_pegawai' => 'Arbi Yudh', 'unit' => 'UPT TIK', 'tanggal' => '12-12-2023', 'jam_masuk' => '07.00', 'jam_keluar' => '16.00', 'total_waktu' => 7];
            }
            // $data = ['id_pegawai' => 1, 'username' => 'arbi.wibu', 'nama_pegawai' => 'Arbi Yudh', 'unit' => 'UPT TIK', 'tanggal' => '12-12-2023', 'jam_masuk' => '07.00', 'jam_keluar' => '16.00', 'total_waktu', 7];
        }


        return response()->json($data);
    }

    public function getData(Request $request)
    {

        // $periode = explode(' - ', $request->periode);

        // if (count($periode) == 2) {
        //     $startDate = $periode[0];
        //     $endDate = $periode[1];
        // } else if (count($periode) == 1) {
        //     $startDate = $periode[0];
        //     $endDate = $periode[0];
        // } else {
        //     $startDate = date('Y-m-d') . ' 00:00:00';
        //     $endDate =  date('Y-m-d') . ' 23:59:59';
        // }

        $punches = IClockTransaction::orderBy('punch_time', 'desc')->orderBy('emp_code');

        // if ($request->username) {
        //     $punches = $punches->whereHas('pegawai', function ($q) use ($request) {
        //         $q->whereRaw('LOWER(first_name) LIKE ?', ['%' . strtolower($request->username) . '%'])
        //             ->orWhere('last_name', 'LIKE', '%' . $request->username . '%');
        //     });
        // }

        // $punches = $punches->get();

        $employeeData = [];

        $punches->chunk(100, function ($punchChunk) use (&$employeeData) {
            foreach ($punchChunk as $punch) {
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
        });

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

        if ($request->ajax()) {
            $formattedData = [];

            foreach ($employeeData as $tanggal => $pegawai) {
                foreach ($pegawai as $kodePegawai => $data) {
                    $formattedData[] = [
                        'tanggal' => $tanggal,
                        'kode_pegawai' => $kodePegawai,
                        'username' => $data['username'],
                        'nama_pegawai' => $data['nama_pegawai'],
                        'unit_departement' => $data['unit_departement'],
                        'jam_keluar' => $data['jam_keluar'],
                        'jam_masuk' => $data['jam_masuk'],
                        'total_waktu' => $data['total_waktu'],
                    ];
                }
            }

            return DataTables::of($formattedData)->make(true);
        }
    }
}
