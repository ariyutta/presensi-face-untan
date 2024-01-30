<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pegawai') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div id="tabel-pegawai" class="table-bordered table-sm"></div>
            </div>
        </div>
    </div>

    @section('js')
        <script>
            let table = new Tabulator("#tabel-pegawai", {
                pagination: true,
                paginationMode: "local",
                paginationSize: 8,
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
                        width: "10px",
                    },
                    {
                        title: "Nama Pegawai",
                        field: "first_name",
                        width: "28px",
                    },
                    {
                        title: "Unit",
                        field: "department.dept_name",
                        width: "17px",
                    },
                    {
                        title: "Posisi",
                        field: "position.position_name",
                        width: "15px",
                    },
                    {
                        title: "Tanggal Masuk",
                        field: "hire_date",
                        width: "15px",
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
                        width: "15px",
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

                    };
                },
                ajaxResponse: function(url, params, response) {
                    return response;
                },
            });
        </script>
    @endsection
</x-app-layout>
