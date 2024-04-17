<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pegawai') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto sm:px-6 lg:px-8">
            <div class="row mb-5">
                <div class="col-md-2">
                    <label for="">Nama Pegawai</label>
                    <input type="text" class="form-control form-control-sm formPegawai" placeholder="Ketik Nama Pegawai" style="border-radius: 5px">
                </div>
                @if (Auth::user()->unit_id == 999)
                    <div class="col-md-2">
                        <label for="">Unit</label>
                        <select class="form-select form-select-sm mb-3 mb-lg-0 formUnit" data-control="select2" data-placeholder="Pilih Unit" data-allow-clear="true">
                            <option></option>
                            @foreach ($unit as $item)
                                <option value="{{ $item->id }}">{{ $item->dept_name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                {{-- <div class="col-md-2">
                    <label for="">Posisi</label>
                    <select class="form-select form-select-sm mb-3 mb-lg-0 formPosisi" data-control="select2" data-placeholder="Pilih Posisi" data-allow-clear="true">
                        <option></option>
                        @foreach ($position as $item)
                            <option value="{{ $item->id }}">{{ $item->position_name }}</option>
                        @endforeach
                    </select>
                </div> --}}
                {{-- <div class="col-md-2">
                    <label for="">Perangkat Terdaftar</label>
                    <select class="form-select form-select-sm mb-3 mb-lg-0 formPerangkat" data-control="select2" data-placeholder="Pilih Perangkat" data-allow-clear="true">
                        <option></option>
                        @foreach ($area as $item)
                            <option value="{{ $item->id }}">{{ $item->area_name }}</option>
                        @endforeach
                    </select>
                </div> --}}
            </div>
            <div class="card shadow">
                <div class="table-responsive py-3 px-3">
                    <table id="tabel-pegawai" class="table-sm table-bordered">
                        <thead>
                            <tr>
                                <th style="text-align: left; width: 160px">NIP</th>
                                <th style="text-align: left; width: 250px">Username</th>
                                <th style="text-align: left; width: 250px">Nama Pegawai</th>
                                <th style="text-align: left; width: 180px">Unit</th>
                                {{-- <th>Posisi</th> --}}
                                <th style="text-align: left">Perangkat Terdaftar</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @section('js')
        <script>
            $(document).ready(function(){
                clearSession();

                var oTable = $('#tabel-pegawai').DataTable({
                    processing: true,
                    pagination: false,
                    serverSide: true,
                    searching: false,
                    ordering: true,
                    ajax: {
                    url: "{{ route('pegawai.getData') }}",
                    data: function(d) {
                        d.formUnit = sessionStorage.formUnit;
                        d.formPegawai = sessionStorage.formPegawai;
                        d.formTanggal = sessionStorage.formTanggal;
                    },
                        type: 'GET'
                    },
                    columns: [
                        {
                            data: 'nickname',
                            name: 'nickname',
                        },
                        {
                            data: 'last_name',
                            name: 'last_name',
                        },
                        {
                            data: 'first_name',
                            name: 'first_name',
                        },
                        {
                            data: 'department.dept_name',
                            name: 'department_id',
                        },
                        // {
                        //     data: 'position.position_name',
                        //     name: 'position_id',
                        // },
                        {
                            data: 'area_pegawai',
                            name: 'area_pegawai',
                            render: function(data, type, row, meta) {
                                if (type === 'display') {
                                    var areaNames = data.map(function(area) {
                                        return area.area.area_name;
                                    });
                                    return areaNames.join(', ');
                                }
                                return data;
                            }
                        },
                    ],
                    order: [
                        [2, 'asc']
                    ],
                    rowCallback: function(row, data) {
                        if (data.nickname === null) {
                            $(row).css('background-color', '#FF6347');
                        }
                        else if (data.nickname === "") {
                            $(row).css('background-color', '#FF6347');
                        }
                    }
                });

                function clearSession() {
                    sessionStorage.removeItem('formUnit');
                    sessionStorage.removeItem('formPegawai');
                    sessionStorage.removeItem('formTanggal');
                }

                $('.formUnit').on('change', function(e) {
                    sessionStorage.setItem('formUnit', this.value);
                    oTable.draw();
                    e.preventDefault();
                });

                $('.formPegawai').on('change', function(e) {
                    sessionStorage.setItem('formPegawai', this.value);
                    oTable.draw();
                    e.preventDefault();
                });

                // $('.cariPegawai').on('click', function(e) {
                //     var unit = $('.formUnit').val();
                //     var pegawai = $('.formPegawai').val();

                //     sessionStorage.setItem('formUnit', unit);
                //     sessionStorage.setItem('formPegawai', pegawai);

                //     oTable.draw();
                //     e.preventDefault();
                // });

            });
        </script>
    @endsection
</x-app-layout>
