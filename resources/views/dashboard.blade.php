<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    @php
        $currentTime = now()->format('H:i');
        $greeting = '';

        if ($currentTime >= '00:00' && $currentTime < '11:00') {
            $greeting = 'Selamat Pagi, ';
        } elseif ($currentTime >= '11:00' && $currentTime < '15:00') {
            $greeting = 'Selamat Siang, ';
        } elseif ($currentTime >= '15:00' && $currentTime < '18:00') {
            $greeting = 'Selamat Sore, ';
        } else {
            $greeting = 'Selamat Malam, ';
        }
    @endphp

    @if (Auth::user()->unit_id == 10)
        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="row">
                    <h3 class="mb-5" style="font-size:22px"><strong>{{ $greeting }} {{ Auth::user()->name }}!</strong></h3>
                    <div class="col-sm-2 mb-5">
                        <div class="card shadow">
                            <div class="card-body">
                                <h1>UPT TIK</h1>
                                <h1 style="font-size:30px">{{ $countDept['upt_tik'] }}</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-10 mb-5">
                        <div class="card shadow">
                            <div class="card-body">
                                <h1 style="font-size:20px"><strong>History Absen</strong></h1>
                                <table id="absenTable" class="table table-sm table-bordered mt-3">
                                    <thead>
                                        <tr>
                                            <th>NIP</th>
                                            <th>Nama Pegawai</th>
                                            <th>Jam Masuk</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($absen_today as $item)
                                            <tr>
                                                <td>{{ $item->pegawai->nickname }}</td>
                                                <td>{{ $item->pegawai->first_name }}</td>
                                                <td>{{ $item->punch_time }}</td>
                                                <td>{{ $item->punch_state }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @elseif (Auth::user()->unit_id == 12)
        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="row">
                    <h3 class="mb-5" style="font-size:18px">Kesekretariat</h3>
                    <div class="col-sm-2 mb-5">
                        <div class="card shadow">
                            <div class="card-body">
                                <h1>UPT Bahasa</h1>
                                <h1 style="font-size:30px">{{ $countDept['upt_bahasa'] }}</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif (Auth::user()->unit_id == 11)
        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="row">
                    <h3 class="mb-5" style="font-size:18px">Kesekretariat</h3>
                    <div class="col-sm-2 mb-5">
                        <div class="card shadow">
                            <div class="card-body">
                                <h1>UPT Perpustakaan</h1>
                                <h1 style="font-size:30px">{{ $countDept['upt_perpus'] }}</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif (Auth::user()->unit_id == 13)
        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="row">
                    <h3 class="mb-5" style="font-size:18px">Kesekretariat</h3>
                    <div class="col-sm-2 mb-5">
                        <div class="card shadow">
                            <div class="card-body">
                                <h1>LPPPM</h1>
                                <h1 style="font-size:30px">{{ $countDept['lpppm'] }}</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif (Auth::user()->unit_id == 14)
        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="row">
                    <h3 class="mb-5" style="font-size:18px">Kesekretariat</h3>
                    <div class="col-sm-2 mb-5">
                        <div class="card shadow">
                            <div class="card-body">
                                <h1>LPPKM</h1>
                                <h1 style="font-size:30px">{{ $countDept['lppkm'] }}</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif (Auth::user()->unit_id == 15)
        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="row">
                    <h3 class="mb-5" style="font-size:18px">Kesekretariat</h3>
                    <div class="col-sm-2 mb-5">
                        <div class="card shadow">
                            <div class="card-body">
                                <h1>BUK(Kumpeg)</h1>
                                <h1 style="font-size:30px">{{ $countDept['buk_kumpeg'] }}</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif (Auth::user()->unit_id == 16)
        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="row">
                    <h3 class="mb-5" style="font-size:18px">Kesekretariat</h3>
                    <div class="col-sm-2 mb-5">
                        <div class="card shadow">
                            <div class="card-body">
                                <h1>BUK(Keuangan)</h1>
                                <h1 style="font-size:30px">{{ $countDept['buk_keuangan'] }}</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif (Auth::user()->unit_id == 17)
        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="row">
                    <h3 class="mb-5" style="font-size:18px">Kesekretariat</h3>
                    <div class="col-sm-2 mb-5">
                        <div class="card shadow">
                            <div class="card-body">
                                <h1>BUK(Umum)</h1>
                                <h1 style="font-size:30px">{{ $countDept['buk_umum'] }}</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif (Auth::user()->unit_id == 18)
        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="row">
                    <h3 class="mb-5" style="font-size:18px">Kesekretariat</h3>
                    <div class="col-sm-2 mb-5">
                        <div class="card shadow">
                            <div class="card-body">
                                <h1>BAK</h1>
                                <h1 style="font-size:30px">{{ $countDept['bak'] }}</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif (Auth::user()->unit_id == 19)
        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="row">
                    <h3 class="mb-5" style="font-size:18px">Kesekretariat</h3>
                    <div class="col-sm-2 mb-5">
                        <div class="card shadow">
                            <div class="card-body">
                                <h1>BPKHM</h1>
                                <h1 style="font-size:30px">{{ $countDept['bpkhm'] }}</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif (Auth::user()->unit_id == 20)
        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="row">
                    <h3 class="mb-5" style="font-size:18px">Kesekretariat</h3>
                    <div class="col-sm-2 mb-5">
                        <div class="card shadow">
                            <div class="card-body">
                                <h1>Rumah Sakit</h1>
                                <h1 style="font-size:30px">{{ $countDept['rumkit'] }}</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif (Auth::user()->unit_id == 21)
        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="row">
                    <h3 class="mb-5" style="font-size:18px">Kesekretariat</h3>
                    <div class="col-sm-2 mb-5">
                        <div class="card shadow">
                            <div class="card-body">
                                <h1>Rektorat</h1>
                                <h1 style="font-size:30px">{{ $countDept['rektorat'] }}</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif (Auth::user()->unit_id == 15)
        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="row">
                    <h3 class="mb-5" style="font-size:18px">Kesekretariat</h3>
                    <div class="col-sm-2 mb-5">
                        <div class="card shadow">
                            <div class="card-body">
                                <h1>Klinik Pratama</h1>
                                <h1 style="font-size:30px">{{ $countDept['klinik'] }}</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="row">
                    <h3 class="mb-5" style="font-size:18px">Kesekretariat</h3>
                    <div class="col-sm-2 mb-5">
                        <div class="card shadow">
                            <div class="card-body">
                                <h1>UPT Bahasa</h1>
                                <h1 style="font-size:30px">{{ $countDept['upt_bahasa'] }}</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2 mb-5">
                        <div class="card shadow">
                            <div class="card-body">
                                <h1>UPT TIK</h1>
                                <h1 style="font-size:30px">{{ $countDept['upt_tik'] }}</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2 mb-5">
                        <div class="card shadow">
                            <div class="card-body">
                                <h1>UPT Perpustakaan</h1>
                                <h1 style="font-size:30px">{{ $countDept['upt_perpus'] }}</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2 mb-5">
                        <div class="card shadow">
                            <div class="card-body">
                                <h1 style="font-size:12px">BUK (Keuangan)</h1>
                                <h1 style="font-size:30px">{{ $countDept['buk_keuangan'] }}</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2 mb-5">
                        <div class="card shadow">
                            <div class="card-body">
                                <h1>BUK (KumPeg)</h1>
                                <h1 style="font-size:30px">{{ $countDept['buk_kumpeg'] }}</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2 mb-5">
                        <div class="card shadow">
                            <div class="card-body">
                                <h1>BUK (Umum)</h1>
                                <h1 style="font-size:30px">{{ $countDept['buk_umum'] }}</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2 mb-5">
                        <div class="card shadow">
                            <div class="card-body">
                                <h1>BAK</h1>
                                <h1 style="font-size:30px">{{ $countDept['bak'] }}</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2 mb-5">
                        <div class="card shadow">
                            <div class="card-body">
                                <h1>BPKHM</h1>
                                <h1 style="font-size:30px">{{ $countDept['bpkhm'] }}</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2 mb-5">
                        <div class="card shadow">
                            <div class="card-body">
                                <h1>LPPKM</h1>
                                <h1 style="font-size:30px">{{ $countDept['lppkm'] }}</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2 mb-5">
                        <div class="card shadow">
                            <div class="card-body">
                                <h1>LPPPM</h1>
                                <h1 style="font-size:30px">{{ $countDept['lpppm'] }}</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2 mb-5">
                        <div class="card shadow">
                            <div class="card-body">
                                <h1>Lab. Terpadu</h1>
                                <h1 style="font-size:30px">{{ $countDept['lab_terpadu'] }}</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2 mb-5">
                        <div class="card shadow">
                            <div class="card-body">
                                <h1>Klinik Pratama</h1>
                                <h1 style="font-size:30px">{{ $countDept['klinik'] }}</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2 mb-5">
                        <div class="card shadow">
                            <div class="card-body">
                                <h1>Rumah Sakit</h1>
                                <h1 style="font-size:30px">{{ $countDept['rumkit'] }}</h1>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-2">
                    <h3 class="mb-5" style="font-size:18px">Fakultas</h3>
                    <div class="col-sm-2 mb-5">
                        <div class="card shadow">
                            <div class="card-body">
                                <h1>Fakultas Hukum</h1>
                                <h1 style="font-size:30px">{{ $countDept['fak_hukum'] }}</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2 mb-5">
                        <div class="card shadow">
                            <div class="card-body">
                                <h1>Fakultas Ekonomi</h1>
                                <h1 style="font-size:30px">{{ $countDept['fak_ekonomi'] }}</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2 mb-5">
                        <div class="card shadow">
                            <div class="card-body">
                                <h1>Fakultas Pertanian</h1>
                                <h1 style="font-size:30px">{{ $countDept['fak_pertanian'] }}</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2 mb-5">
                        <div class="card shadow">
                            <div class="card-body">
                                <h1>Fakultas Teknik</h1>
                                <h1 style="font-size:30px">{{ $countDept['fak_teknik'] }}</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2 mb-5">
                        <div class="card shadow">
                            <div class="card-body">
                                <h1>Fakultas ISIP</h1>
                                <h1 style="font-size:30px">{{ $countDept['fak_isip'] }}</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2 mb-5">
                        <div class="card shadow">
                            <div class="card-body">
                                <h1>Fakultas KIP</h1>
                                <h1 style="font-size:30px">{{ $countDept['fak_kip'] }}</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2 mb-5">
                        <div class="card shadow">
                            <div class="card-body">
                                <h1>Fakultas Kehutanan</h1>
                                <h1 style="font-size:30px">{{ $countDept['fak_kehutanan'] }}</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2 mb-5">
                        <div class="card shadow">
                            <div class="card-body">
                                <h1>Fakultas MIPA</h1>
                                <h1 style="font-size:30px">{{ $countDept['fak_mipa'] }}</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2 mb-5">
                        <div class="card shadow">
                            <div class="card-body">
                                <h1>Fakultas Kedokteran</h1>
                                <h1 style="font-size:30px">{{ $countDept['fak_kedokteran'] }}</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @section('js')
        <script>
            $(document).ready(function() {
                $('#absenTable').DataTable({
                    data: {!! $absen_today !!}, // Menggunakan data yang dikirimkan dari controller
                    columns: [
                        { data: 'id' },
                        { data: 'terminal_id' },
                        { data: 'punch_time' },
                        // Tambahkan kolom lain sesuai kebutuhan
                    ]
                });
            });
        </script>
    @endsection

</x-app-layout>
