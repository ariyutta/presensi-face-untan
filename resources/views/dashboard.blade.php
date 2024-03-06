<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

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
                            <h1>Klinik</h1>
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
        </div>
    </div>
</x-app-layout>
