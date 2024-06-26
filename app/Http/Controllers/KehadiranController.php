<?php

namespace App\Http\Controllers;

use App\Exports\ExportExcelTIK;
use App\Exports\ExportJmlKehadiran;
use App\Exports\KehadiranExport;
use App\Models\IClockTransaction;
use App\Models\PersonnelDepartment;
use App\Models\PersonnelEmployee;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $periode = explode(' - ', $request->tanggal);
        $user_login = Auth::user()->unit_id;

        $punches = IClockTransaction::orderBy('punch_time', 'desc')->orderBy('emp_code');

        if ($request->username) {
            $punches = $punches->whereHas('pegawai', function ($q) use ($request) {
                $q->whereRaw('LOWER(first_name) LIKE ?', ['%' . strtolower($request->username) . '%'])
                    ->orWhere('last_name', 'LIKE', '%' . $request->username . '%')
                    ->orWhere('emp_code', 'LIKE', '%' . $request->username . '%');
            });
        }

        if ($user_login == 999) {
            if ($request->unit) {
                $punches = $punches->whereHas('pegawai', function ($data) use ($request) {
                    $data->whereHas('department', function ($data2) use ($request) {
                        $data2->where('dept_code', $request->unit);
                    });
                });
            } else {
                $punches = $punches->whereHas('pegawai', function ($data) use ($request) {
                    $data->whereHas('department', function ($data2) use ($request) {
                        $data2->where('dept_code', 0);
                    });
                });
            }
        } else {
            $punches = $punches->whereHas('pegawai', function ($data) use ($user_login) {
                $data->whereHas('department', function ($data2) use ($user_login) {
                    $data2->where('dept_code', $user_login);
                });
            });
        }

        if (count($periode) == 2) {
            $startDate = $periode[0];
            $endDate = $periode[1];

            $punches = $punches->whereBetween('punch_time', [$startDate, $endDate]);
        } else {
            // return [];
            $startDate = new DateTime();

            $punches = $punches->whereBetween('punch_time', [date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59')]);
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
                        'jam_keluar' => $punchTime->format('H:i') . ' WIB',
                        'jam_masuk' => $punchTime->format('H:i') . ' WIB',
                        'total_waktu' => 0,
                    ];
                } else {
                    $employeeData[$dateKey][$empCode]['jam_masuk'] = $punchTime->format('H:i') . ' WIB';
                }
            }
        }

        foreach ($employeeData as &$dateData) {
            foreach ($dateData as &$data) {
                $jamMasuk = Carbon::createFromFormat('H:i', substr($data['jam_masuk'], 0, -4));
                $jamKeluar = Carbon::createFromFormat('H:i', substr($data['jam_keluar'], 0, -4));

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

        // return response()->json($employeeData);

        if ($request->ajax()) {
            $formattedData = [];

            foreach ($employeeData as $tanggal => $pegawai) {
                foreach ($pegawai as $kodePegawai => $data) {
                    $formattedData[] = [
                        'tanggal' => date('d/m/Y', strtotime($tanggal)),
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

    function exportExcelTIK(Request $request)
    {
        // return $request->all();
        $usernameId = '';
        $unitId = '';
        $user_login = Auth::user()->unit_id;

        if ($request->username != null) {
            $usernameId = $request->username;
        }

        if ($user_login == 999) {
            if ($request->unit != null) {
                $unitId = $request->unit;
            }
        } else {
            $unitId = $user_login;
        }

        $periode = explode(' - ', $request->tanggal);

        if (count($periode) == 2) {
            $startDate = $periode[0];
            $endDate = $periode[1];
        } elseif (count($periode) == 1) {
            $startDate = $periode[0];
            $endDate = $periode[0];
        } else {
            $startDate = date('Y-m-d') . " 00:00";
            $endDate =  date('Y-m-d') . " 23:59";
        }

        $data = Http::get("http://172.16.40.117:8000/api/getKehadiran?username=$usernameId&department_id=$unitId&periode=$startDate%2000:00:00%20-%20$endDate%2023:59:59")->json();

        // return $data;

        $i = 0;
        foreach ($data as $val) {
            foreach ($val['data'] as $item) {
                $rekap[$i]['unit'] = $item['unit_departement'];
                $rekap[$i]['nip'] = $item['nip'];
                $rekap[$i]['nama_pegawai'] = $item['nama_pegawai'];

                // Ubah format tanggal
                $rekap[$i]['tanggal'] = date('d/m/Y', strtotime($item['tanggal']));

                // Ubah format jam_masuk dan jam_keluar
                $rekap[$i]['jam_masuk'] = date('H:i', strtotime($item['jam_masuk'])) . ' WIB';
                $rekap[$i]['jam_keluar'] = date('H:i', strtotime($item['jam_keluar'])) . ' WIB';

                $jam_masuk = Carbon::parse($item['jam_masuk']);
                $jam_keluar = Carbon::parse($item['jam_keluar']);
                $selisih = $jam_masuk->diffInMinutes($jam_keluar);
                $jam = floor($selisih / 60);
                $menit = $selisih % 60;

                $rekap[$i]['total_waktu'] = "$jam jam $menit menit";

                // Waktu target hadir
                // $tanggal = $val['tanggal'];
                // $waktu_target_hadir = Carbon::parse("$tanggal 08:30:00");

                // // Periksa apakah waktu masuk terlambat
                // if ($jam_masuk->gt($waktu_target_hadir)) {
                //     $rekap[$i]['keterangan'] = "Terlambat";
                // } else {
                //     $rekap[$i]['keterangan'] = "Tepat Waktu";
                // }

                $rekap[$i]['total_waktu'] = "$jam jam $menit menit";
                $i++;
            }
        }

        $data = collect($rekap)->sortBy([
            ['nama_pegawai', 'asc'],
            ['tanggal', 'asc']
        ])->values();

        return Excel::download(new ExportExcelTIK($data), 'export-rekap-tik.xlsx');
    }

    function exportJmlKehadiran(Request $request)
    {
        $usernameId = '';
        $unitId = '';

        if ($request->username != null) {
            $usernameId = $request->username;
        }

        if ($request->unit != null) {
            $unitId = $request->unit;
        }

        $periode = explode(' - ', $request->tanggal);

        if (count($periode) == 2) {
            $startDate = $periode[0];
            $endDate = $periode[1];
        } elseif (count($periode) == 1) {
            $startDate = $periode[0];
            $endDate = $periode[0];
        } else {
            $startDate = date('Y-m-d');
            $endDate =  date('Y-m-d');
        }

        $getHariKerja = Http::get("https://dashboard.presensi.untan.ac.id/api/service/getLibur")->json();
        $data = Http::get("http://172.16.40.117:8000/api/getKehadiran?username=$usernameId&department_id=$unitId&periode=$startDate%2000:00:00%20-%20$endDate%2023:59:59")->json();

        // return $usernameId . ', ' . $unitId . ', ' . $startDate . ', ' . $endDate;

        $today = date('Y-m-d');

        $filteredDates = array_filter($getHariKerja['hari_kerja'], function ($date) use ($today) {
            return $date <= $today;
        });

        $TotalHariKerjaPerBulan = count($getHariKerja['hari_kerja']);
        $TotalHariKerja = count($filteredDates);

        $kehadiranTidakHadir = [];

        if ($data !== null) {
            foreach ($data as $hari) {
                foreach ($hari['data'] as $pegawai) {
                    $namaPegawai = $pegawai['nama_pegawai'];
                    $kehadiranTidakHadir[$namaPegawai]['nip'] = $pegawai['nip'];
                    $kehadiranTidakHadir[$namaPegawai]['unit'] = $pegawai['unit_departement'];

                    // Menghitung kehadiran harian
                    if (!isset($kehadiranTidakHadir[$namaPegawai]['kehadiran'])) {
                        $kehadiranTidakHadir[$namaPegawai]['kehadiran'] = 0;
                    }
                    $kehadiranTidakHadir[$namaPegawai]['kehadiran']++;

                    // Menghandle kehadiran yang lebih dari kehadiran hari ini
                    if ($kehadiranTidakHadir[$namaPegawai]['kehadiran'] > $TotalHariKerja) {
                        $handleKelebihanHari = $TotalHariKerjaPerBulan - $TotalHariKerja;

                        $kehadiranTidakHadir[$namaPegawai]['kehadiran'] = $kehadiranTidakHadir[$namaPegawai]['kehadiran'] - $handleKelebihanHari;
                    }

                    // Menghitung tidak hadir berdasarkan total hari
                    if (!isset($kehadiranTidakHadir[$namaPegawai]['tidak_hadir'])) {
                        $kehadiranTidakHadir[$namaPegawai]['tidak_hadir'] = 0;
                    } else {
                        $kehadiranTidakHadir[$namaPegawai]['tidak_hadir'] = $TotalHariKerja - $kehadiranTidakHadir[$namaPegawai]['kehadiran'];

                        if ($kehadiranTidakHadir[$namaPegawai]['tidak_hadir'] < 0) {
                            $kehadiranTidakHadir[$namaPegawai]['tidak_hadir'] = 0;
                        }
                    }
                }
            }
        }

        // Mengubah jumlah tidak hadir untuk pegawai yang hadir selama seluruh periode
        foreach ($kehadiranTidakHadir as $namaPegawai => &$pegawai) {
            // return $pegawai;
            $pegawai['nama_pegawai'] = $namaPegawai;
        }

        $result = [
            'total_hari_perbulan' => $TotalHariKerjaPerBulan,
            'total_hari_berjalan' => $TotalHariKerja,
            'data' => $kehadiranTidakHadir,
        ];

        $data = array_values($result['data']);
        $data = collect($data)->sortBy([
            ['nama_pegawai', 'asc'],
        ])->values();

        return Excel::download(new ExportJmlKehadiran($data), 'export-jml-kehadiran.xlsx');
    }

    function indexJmlKehadiran()
    {

        $unit = PersonnelDepartment::where('id', '!=', 1)->orderBy('dept_name', 'asc')->get();

        return view('jml-kehadiran.index', compact('unit'));
    }

    function getJmlKehadiran(Request $request)
    {
        $usernameId = '';
        $unitId = '';
        $user_login = Auth::user()->unit_id;

        if ($request->username != null) {
            $usernameId = $request->username;
        }

        if ($user_login == 999) {
            if ($request->unit != null) {
                $unitId = $request->unit;
            }
        } else {
            $unitId = $user_login;
        }

        $periode = explode(' - ', $request->tanggal);

        if (count($periode) == 2) {
            $startDate = $periode[0];
            $endDate = $periode[1];
        } elseif (count($periode) == 1) {
            $startDate = $periode[0];
            $endDate = $periode[0];
        } else {
            $startDate = date('Y-m-d');
            $endDate =  date('Y-m-d');
        }

        $getHariKerja = Http::get("http://172.16.40.85:8002/api/service/get-hari-kerja?tanggal=$startDate")->json();
        $data = Http::get("http://172.16.40.117:8000/api/getKehadiran?username=$usernameId&department_id=$unitId&periode=$startDate%2000:00:00%20-%20$endDate%2023:59:59")->json();

        // return $usernameId . ', ' . $unitId . ', ' . $startDate . ', ' . $endDate;

        $today = date('Y-m-d');

        $filteredDates = array_filter($getHariKerja['hari_kerja'], function ($date) use ($today) {
            return $date <= $today;
        });

        $TotalHariKerjaPerBulan = count($getHariKerja['hari_kerja']);
        $TotalHariKerja = count($filteredDates);

        $kehadiranTidakHadir = [];

        if ($data !== null) {
            foreach ($data as $hari) {
                foreach ($hari['data'] as $pegawai) {
                    $namaPegawai = $pegawai['nama_pegawai'];
                    $kehadiranTidakHadir[$namaPegawai]['nip'] = $pegawai['nip'];
                    $kehadiranTidakHadir[$namaPegawai]['unit'] = $pegawai['unit_departement'];

                    // Menghitung kehadiran harian
                    if (!isset($kehadiranTidakHadir[$namaPegawai]['kehadiran'])) {
                        $kehadiranTidakHadir[$namaPegawai]['kehadiran'] = 0;
                    }
                    $kehadiranTidakHadir[$namaPegawai]['kehadiran']++;

                    // Menghandle kehadiran yang lebih dari kehadiran hari ini
                    $handleKelebihanHari = 0;
                    if ($kehadiranTidakHadir[$namaPegawai]['kehadiran'] > $TotalHariKerja) {
                        $handleKelebihanHari = $kehadiranTidakHadir[$namaPegawai]['kehadiran'] - $TotalHariKerja;

                        $kehadiranTidakHadir[$namaPegawai]['kehadiran'] -= $handleKelebihanHari;
                    }

                    // Menghitung tidak hadir berdasarkan total hari
                    if (!isset($kehadiranTidakHadir[$namaPegawai]['tidak_hadir'])) {
                        $kehadiranTidakHadir[$namaPegawai]['tidak_hadir'] = 0;
                    } else {
                        $kehadiranTidakHadir[$namaPegawai]['tidak_hadir'] = $TotalHariKerja - $kehadiranTidakHadir[$namaPegawai]['kehadiran'];

                        if ($kehadiranTidakHadir[$namaPegawai]['tidak_hadir'] < 0) {
                            $kehadiranTidakHadir[$namaPegawai]['tidak_hadir'] = 0;
                        }
                    }
                }
            }
        }

        // Mengubah jumlah tidak hadir untuk pegawai yang hadir selama seluruh periode
        foreach ($kehadiranTidakHadir as $namaPegawai => &$pegawai) {
            // return $pegawai;
            $pegawai['nama_pegawai'] = $namaPegawai;
        }

        $result = [
            'total_hari_perbulan' => $TotalHariKerjaPerBulan,
            'total_hari_berjalan' => $TotalHariKerja,
            'data' => $kehadiranTidakHadir,
        ];

        $hasil = array_values($result['data']);
        // return response()->json($hasil);
        if ($request->ajax()) {
            return DataTables::of($hasil)
                ->addColumn('unit', function ($row) {
                    return $row['unit'];
                })
                ->addColumn('nip', function ($row) {
                    return $row['nip'];
                })
                ->addColumn('kehadiran', function ($row) {
                    return $row['kehadiran'];
                })
                ->addColumn('tidak_hadir', function ($row) {
                    return $row['tidak_hadir'];
                })
                ->make(true);
        }
    }
}
