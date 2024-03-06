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
                <div class="col-md-2">
                    <label for="">Unit</label>
                    <select class="form-select form-select-sm mb-3 mb-lg-0 formUnit" data-control="select2" data-placeholder="Pilih Unit" data-allow-clear="true">
                        <option></option>
                        @foreach ($unit as $item)
                            <option value="{{ $item->id }}">{{ $item->dept_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="">Posisi</label>
                    <select class="form-select form-select-sm mb-3 mb-lg-0 formPosisi" data-control="select2" data-placeholder="Pilih Posisi" data-allow-clear="true">
                        <option></option>
                        @foreach ($position as $item)
                            <option value="{{ $item->id }}">{{ $item->position_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="">Perangkat Terdaftar</label>
                    <select class="form-select form-select-sm mb-3 mb-lg-0 formPerangkat" data-control="select2" data-placeholder="Pilih Perangkat" data-allow-clear="true">
                        <option></option>
                        @foreach ($area as $item)
                            <option value="{{ $item->id }}">{{ $item->area_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="card shadow">
                <div id="tabel-pegawai" class="table-bordered table-sm"></div>
            </div>
        </div>
    </div>

    @section('js')
        <script>
            let table = new Tabulator("#tabel-pegawai", {
                pagination: true,
                paginationMode: "local",
                paginationSize: 15,
                paginationSizeSelector: true,
                placeholder: "Data tidak tersedia",
                layout: "fitColumns",
                minHeight: 15,
                height: "100%",
                selectable: 1,
                paginationCounter:"rows",
                columns: [
                    {
                        title: "ID Pegawai",
                        field: "emp_code",
                        // width: "10px",
                    },
                    {
                        title: "Username",
                        field: "last_name",
                        // width: "28px",
                    },
                    {
                        title: "Nama Pegawai",
                        field: "first_name",
                        // width: "28px",
                    },
                    {
                        title: "Unit",
                        field: "department.dept_name",
                        // width: "17px",
                    },
                    {
                        title: "Posisi",
                        field: "position.position_name",
                        // width: "15px",
                    },
                    {
                        title: "Tanggal Masuk",
                        field: "hire_date",
                        // width: "15px",
                        formatter: "datetime",
                        formatterParams: {
                            inputFormat: "yyyy-MM-dd",
                            outputFormat: "dd/MM/yyyy",
                            invalidPlaceholder: "(invalid format)",
                        },
                    },
                    {
                        title: "Perangkat Terdaftar",
                        field: "area_pegawai",
                        // width: "15px",
                        formatter: function(cell, formatterParams, onRendered) {
                            var areas = cell.getValue();
                            var areaNames = areas.map(function(areaID) {
                                return areaID.area.area_name;
                            });

                            return areaNames.join(', ');
                        },
                    },
                ],
                ajaxURL: "{{ route('pegawai.getData') }}",
                ajaxParams: function() {
                    return {
                        formPegawai: $('.formPegawai').val(),
                        formUnit: $('.formUnit').val(),
                        formPosisi: $('.formPosisi').val(),
                        formPerangkat: $('.formPerangkat').val(),
                    };
                },
                ajaxResponse: function(url, params, response) {
                    return response;
                },
            });

            $('.formPegawai').change(function() {
                table.setData();
            });

            $('.formUnit').change(function() {
                table.setData();
            });

            $('.formPosisi').change(function() {
                table.setData();
            });

            $('.formPerangkat').change(function() {
                table.setData();
            });
        </script>
    @endsection
</x-app-layout>
