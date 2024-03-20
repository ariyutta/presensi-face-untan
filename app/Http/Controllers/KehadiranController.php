<?php

namespace App\Http\Controllers;

use App\Exports\ExportExcelTIK;
use App\Exports\KehadiranExport;
use App\Models\IClockTransaction;
use App\Models\PersonnelDepartment;
use App\Models\PersonnelEmployee;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
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
            for ($i = 0; $i < 1000; $i++) {
                $data[$i] = ['id_pegawai' => 1, 'username' => 'arbi.wibu', 'nama_pegawai' => 'Arbi Yudh', 'unit' => 'UPT TIK', 'tanggal' => '12-12-2023', 'jam_masuk' => '07.00', 'jam_keluar' => '16.00', 'total_waktu' => 7];
            }
            // $data = ['id_pegawai' => 1, 'username' => 'arbi.wibu', 'nama_pegawai' => 'Arbi Yudh', 'unit' => 'UPT TIK', 'tanggal' => '12-12-2023', 'jam_masuk' => '07.00', 'jam_keluar' => '16.00', 'total_waktu', 7];
        }
        if ($unit_kerja == 1) {
            for ($i = 0; $i < 1000; $i++) {
                $data[$i] = ['id_pegawai' => 2, 'username' => 'arbi.cabul', 'nama_pegawai' => 'Arbi Cabul', 'unit' => 'UPT Perpustakaan', 'tanggal' => '01-02-2023', 'jam_masuk' => '07.59', 'jam_keluar' => '16.00', 'total_waktu' => 9];
            }
            // $data = ['id_pegawai' => 2, 'username' => 'arbi.cabul', 'nama_pegawai' => 'Arbi Cabul', 'unit' => 'UPT Perpustakaan', 'tanggal' => '01-02-2023', 'jam_masuk' => '07.59', 'jam_keluar' => '16.00', 'total_waktu', 9];
        }
        if ($tanggal == '2024-03-06') {
            for ($i = 0; $i < 1000; $i++) {
                $data[$i] = ['id_pegawai' => 1, 'username' => 'arbi.wibu', 'nama_pegawai' => 'Arbi Yudh', 'unit' => 'UPT TIK', 'tanggal' => '12-12-2023', 'jam_masuk' => '07.00', 'jam_keluar' => '16.00', 'total_waktu' => 7];
            }
            // $data = ['id_pegawai' => 1, 'username' => 'arbi.wibu', 'nama_pegawai' => 'Arbi Yudh', 'unit' => 'UPT TIK', 'tanggal' => '12-12-2023', 'jam_masuk' => '07.00', 'jam_keluar' => '16.00', 'total_waktu', 7];
        }


        return response()->json($data);
    }

    public function getData(Request $request)
    {
        $periode = explode(' - ', $request->formTanggal);
        $punches = IClockTransaction::orderBy('punch_time', 'desc')->orderBy('emp_code');

        if ($request->formPegawai) {
            $punches = $punches->whereHas('pegawai', function ($q) use ($request) {
                $q->whereRaw('LOWER(first_name) LIKE ?', ['%' . strtolower($request->formPegawai) . '%'])
                    ->orWhere('last_name', 'LIKE', '%' . $request->formPegawai . '%')
                    ->orWhere('emp_code', 'LIKE', '%' . $request->formPegawai . '%');
            });
        }

        if ($request->formUnit) {
            $punches = $punches->whereHas('pegawai', function ($data) use ($request) {
                $data->where('department_id', $request->formUnit);
            });
        }

        if (count($periode) == 2) {
            $startDate = $periode[0];
            $endDate = $periode[1];

            $punches = $punches->whereBetween('punch_time', [$startDate, $endDate]);
        } else {
            return [];
            // $startDate = new DateTime();

            // $punches = $punches->whereBetween('punch_time', [date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59')]);
        }
        $punches = $punches->get();

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
                $employeeNip = $employee->nickname;

                if (!isset($employeeData[$dateKey][$empCode])) {
                    $employeeData[$dateKey][$empCode] = [
                        'username' => $employeeUsername,
                        'nip' => $employeeNip,
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

        if ($request->ajax()) {
            $formattedData = [];

            foreach ($employeeData as $tanggal => $pegawai) {
                foreach ($pegawai as $kodePegawai => $data) {
                    $formattedData[] = [
                        'tanggal' => $tanggal,
                        'kode_pegawai' => $kodePegawai,
                        'username' => $data['username'],
                        'nip' => $data['nip'],
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

    public function exportExcel(Request $request)
    {
        return Excel::download(new KehadiranExport($request), 'test_export.xlsx');
    }

    function exportExcelTIK()
    {
        $data = Http::get("http://172.16.40.117:8000/api/getKehadiran?username=&department_id=10&periode=2024-03-01%2000:00:00%20-%202024-03-20%2023:00:00")->json();
        $i = 0;
        foreach ($data as $val) {
            foreach ($val['data'] as $item) {
                $rekap[$i]['nip'] = $item['nip'];
                $rekap[$i]['nama_pegawai'] = $item['nama_pegawai'];
                $rekap[$i]['tanggal'] = $item['tanggal'];
                $rekap[$i]['jam_masuk'] = $item['jam_masuk'];
                $rekap[$i]['jam_keluar'] = $item['jam_keluar'];

                $jam_masuk = Carbon::parse($item['jam_masuk']);
                $jam_keluar = Carbon::parse($item['jam_keluar']);
                $selisih = $jam_masuk->diffInMinutes($jam_keluar);
                $jam = floor($selisih / 60);
                $menit = $selisih % 60;

                $rekap[$i]['total_waktu'] = "$jam jam $menit menit";

                // Waktu target hadir
                $tanggal = $val['tanggal'];
                $waktu_target_hadir = Carbon::parse("$tanggal 08:30:00");

                // Periksa apakah waktu masuk terlambat
                if ($jam_masuk->gt($waktu_target_hadir)) {
                    $rekap[$i]['keterangan'] = "Terlambat";
                } else {
                    $rekap[$i]['keterangan'] = "Tepat Waktu";
                }

                $rekap[$i]['total_waktu'] = "$jam jam $menit menit";
                $i++;
            }
        }
        $data = collect($rekap)->sortBy('nama_pegawai')->values();
        // return $rekap;

        return Excel::download(new ExportExcelTIK($data), 'export-rekap-tik.xlsx');
    }
}
