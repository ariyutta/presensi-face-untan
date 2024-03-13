<?php

namespace App\Exports;

use App\Models\IClockTransaction as ModelsIClockTransaction;
use App\Models\PersonnelEmployee;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Carbon\Carbon;

class KehadiranExport implements FromCollection
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $periode = explode(' - ', $this->request->formTanggal);
        $punches = ModelsIClockTransaction::orderBy('punch_time', 'desc')->orderBy('emp_code');

        if ($this->request->formPegawai) {
            $punches = $punches->whereHas('pegawai', function ($q) {
                $q->whereRaw('LOWER(first_name) LIKE ?', ['%' . strtolower($this->request->formPegawai) . '%'])
                    ->orWhere('last_name', 'LIKE', '%' . $this->request->formPegawai . '%');
            });
        }

        if ($this->request->formUnit) {
            $punches = $punches->whereHas('pegawai', function ($data) {
                $data->where('department_id', $this->request->formUnit);
            });
        }

        if (count($periode) == 2) {
            $startDate = $periode[0];
            $endDate = $periode[1];

            $punches = $punches->whereBetween('punch_time', [$startDate, $endDate]);
        } else {
            $startDate = new Carbon();

            $punches = $punches->whereBetween('punch_time', [date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59')]);
        }

        $employeeData = [];

        $punches = $punches->get();

        foreach ($punches as $punch) {
            $empCode = $punch->emp_code;
            $punchTime = Carbon::parse($punch->punch_time);
            $dateKey = $punchTime->toDateString();

            $employee = PersonnelEmployee::where('emp_code', $empCode)->first();

            if ($employee) {
                $employeeName = $employee->first_name;
                $department = $employee->department->dept_name;

                if (!isset($employeeData[$dateKey][$empCode])) {
                    $employeeData[$dateKey][$empCode] = [
                        'nama_pegawai' => $employeeName,
                        'unit_departement' => $department,
                        'tanggal' => $punchTime->format('d/m/Y'),
                        'jam_masuk' => $punchTime->format('H:i:s') . ' WIB',
                        'jam_keluar' => $punchTime->format('H:i:s') . ' WIB',
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

        $formattedData = [];

        foreach ($employeeData as $tanggal => $pegawai) {
            foreach ($pegawai as $kodePegawai => $data) {
                $formattedData[] = [
                    'tanggal' => $tanggal,
                    'kode_pegawai' => $kodePegawai,
                    'nama_pegawai' => $data['nama_pegawai'],
                    'unit_departement' => $data['unit_departement'],
                    'jam_masuk' => $data['jam_masuk'],
                    'jam_keluar' => $data['jam_keluar'],
                    'total_waktu' => $data['total_waktu'],
                ];
            }
        }

        return new Collection($formattedData);
    }
}
