<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kehadiran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto sm:px-6 lg:px-8">
            <div class="row mb-5">
                <div class="col-md-2">
                    <label for="">Unit</label>
                    <select class="form-select form-select-sm mb-3 mb-lg-0 formUnit" data-control="select2" data-placeholder="Pilih Unit" data-allow-clear="true" name="formUnit">
                        <option></option>
                        @foreach ($unit as $item)
                            <option value="{{ $item->id }}">{{ $item->dept_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="">Nama Pegawai</label>
                    <input type="text" class="form-control form-control-sm formPegawai" placeholder="Ketik Nama Pegawai" style="border-radius: 5px" name="formPegawai">
                </div>
                <div class="col-md-3">
                    <label for="">Periode</label>
                    <input type="text" name="formTanggal" placeholder="Pilih Tanggal" class="form-control form-control-sm formTanggal" style="border-radius: 5px" >
                </div>
                <div class="col-sm-1">
                    <label for="">Aksi....</label>
                    <button class="btn btn-primary btn-sm cariPegawai" type="button">Cari Pegawai</button>
                </div>
                <div class="col-sm-1">
                    <label for="">Cetak....</label>
                    <button class="btn btn-success btn-sm cetakPegawai" type="button">Cetak Pegawai</button>
                </div>
            </div>
            <div class="card shadow">
                <div class="table-responsive py-3 px-3">
                    <table id="tabel-kehadiran" class="table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>ID Pegawai</th>
                                <th>Nama Pegawai</th>
                                <th>Unit</th>
                                <th>Tanggal</th>
                                <th>Jam Masuk</th>
                                <th>Jam Keluar</th>
                                <th>Total Waktu</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @section('js')
        <script>
            $(".formTanggal").flatpickr({
                altInput: true,
                altFormat: "d/m/Y",
                dateFormat: "Y-m-d",
                mode: "range",
                locale: "id",
                defaultDate: "today"
            });

            $(document).ready(function(){
                clearSession();

                var oTable = $('#tabel-kehadiran').DataTable({
                    processing: true,
                    pagination: false,
                    serverSide: true,
                    searching: false,
                    ordering: true,
                    ajax: {
                    url: "{{ url('http://presensi-face-untan.test/kehadiran/get-data') }}",
                    data: function(d) {
                        d.formUnit = sessionStorage.formUnit;
                        d.formPegawai = sessionStorage.formPegawai;
                        d.formTanggal = sessionStorage.formTanggal;
                    },
                        type: 'GET'
                    },
                    columns: [
                        {
                            data: 'kode_pegawai',
                            name: 'kode_pegawai',
                        },
                        {
                            data: 'nama_pegawai',
                            name: 'nama_pegawai',
                        },
                        {
                            data: 'unit_departement',
                            name: 'unit_departement',
                        },
                        {
                            data: 'tanggal',
                            name: 'tanggal',
                        },
                        {
                            data: 'jam_masuk',
                            name: 'jam_masuk',
                        },
                        {
                            data: 'jam_keluar',
                            name: 'jam_keluar',
                        },
                        {
                            data: 'total_waktu',
                            name: 'total_waktu',
                        },
                    ]
                });

                function clearSession() {
                    sessionStorage.removeItem('formUnit');
                    sessionStorage.removeItem('formPegawai');
                    sessionStorage.removeItem('formTanggal');
                }

                // $('.formUnit').on('change', function(e) {
                //     sessionStorage.setItem('formUnit', this.value);
                //     oTable.draw();
                //     e.preventDefault();
                // });

                // $('.formPegawai').on('change', function(e) {
                //     sessionStorage.setItem('formPegawai', this.value);
                //     oTable.draw();
                //     e.preventDefault();
                // });

                $('.cariPegawai').on('click', function(e) {
                    var unit = $('.formUnit').val();
                    var pegawai = $('.formPegawai').val();
                    var tanggal = $('.formTanggal').val();

                    sessionStorage.setItem('formUnit', unit);
                    sessionStorage.setItem('formPegawai', pegawai);
                    sessionStorage.setItem('formTanggal', tanggal);

                    oTable.draw();
                    e.preventDefault();
                });

            });


            // let table = new Tabulator("#tabel-kehadiran", {
            //     pagination: true,
            //     paginationMode: "local",
            //     paginationSize: 15,
            //     paginationSizeSelector: true,
            //     placeholder: "Data tidak tersedia",
            //     layout: "fitColumns",
            //     minHeight: 15,
            //     height: "100%",
            //     selectable: 1,
            //     paginationCounter: "rows",
            //     data: tempData,
            //     columns: [
            //         { title: "ID Pegawai", field: "id" },
            //         { title: "Username", field: "username" },
            //         { title: "Nama Pegawai", field: "nama_pegawai" },
            //         { title: "Unit", field: "unit" },
            //         { title: "Tanggal", field: "tanggal" },
            //         { title: "Jam Masuk", field: "jam_masuk" },
            //         { title: "Jam Keluar", field: "jam_keluar" },
            //         { title: "Total Waktu", field: "total_waktu" }
            //     ],
            //     ajaxURL: "{{ url('http://presensi-face-untan.test/api/kehadiran') }}",
            //     ajaxParams: function() {
            //         return {
            //             username: $('.formPegawai').val(),
            //         };
            //     },
            //     ajaxResponse: function(url, params, response) {
            //         return response;
            //     },
            // });

            // Mengambil data dari URL menggunakan AJAX
            // $.ajax({
            //     url: "{{ url('http://presensi-face-untan.test/api/kehadiran') }}",
            //     method: "GET",
            //     success: function(response) {
            //         let tableData = [];
            //         for (let date in response) {
            //             for (let id in response[date]) {
            //                 let row = response[date][id];
            //                 tableData.push({
            //                     id: id,
            //                     username: row.username,
            //                     nama_pegawai: row.nama_pegawai,
            //                     unit: row.unit_departement,
            //                     tanggal: row.tanggal,
            //                     jam_masuk: row.jam_masuk,
            //                     jam_keluar: row.jam_keluar,
            //                     total_waktu: row.total_waktu
            //                     // Anda bisa menambahkan field lain sesuai kebutuhan
            //                 });

            //                 tempData.push({
            //                     id: id,
            //                     username: row.username,
            //                     nama_pegawai: row.nama_pegawai,
            //                     unit: row.unit_departement,
            //                     tanggal: row.tanggal,
            //                     jam_masuk: row.jam_masuk,
            //                     jam_keluar: row.jam_keluar,
            //                     total_waktu: row.total_waktu
            //                 });
            //             }
            //         }
            //         // Setelah mendapatkan data, masukkan ke dalam tabel
            //         // table.setData(tableData);
            //     },
            //     error: function(xhr, status, error) {
            //         console.error("Error:", error);
            //     }
            // });

            // $('.formPegawai').change(function() {
            //     table.setData();
            // });
        </script>
    @endsection
</x-app-layout>
