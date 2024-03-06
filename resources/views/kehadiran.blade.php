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
                    <label for="">Nama Pegawai</label>
                    <input type="text" class="form-control form-control-sm formPegawai" placeholder="Ketik Nama Pegawai" style="border-radius: 5px">
                </div>
                <div class="col-md-2">
                    <label for="">Unit</label>
                    <select class="form-select form-select-sm mb-3 mb-lg-0 formUnit" data-control="select2" data-placeholder="Pilih Unit" data-allow-clear="true">
                        <option></option>
                        {{-- @foreach ($jenis as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach --}}
                    </select>
                </div>
                {{-- <div class="col-md-2">
                    <label for="">Posisi</label>
                    <select class="form-select form-select-sm mb-3 mb-lg-0 formPosisi" data-control="select2" data-placeholder="Pilih Posisi" data-allow-clear="true">
                        <option></option>
                        @foreach ($elemen as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div> --}}
                <div class="col-md-3">
                    <label for="">Periode</label>
                    <input type="text" name="daterange" placeholder="Pilih Tanggal" class="form-control form-control-sm daterange" style="border-radius: 5px">
                </div>
            </div>
            <div class="card shadow">
                <div id="tabel-kehadiran" class="table-bordered table-sm"></div>
            </div>
        </div>
    </div>

    @section('js')
        <script>
            var tempData = [];

            $(".daterange").flatpickr({
                altInput: true,
                altFormat: "d/m/Y H:i",
                dateFormat: "Y-m-d H:i",
                enableTime: true,
                mode: "range",
                locale: "id",
                maxDate: "today",
                defaultDate: "today - today"
            });

            let table = new Tabulator("#tabel-kehadiran", {
                pagination: true,
                paginationMode: "local",
                paginationSize: 15,
                paginationSizeSelector: true,
                placeholder: "Data tidak tersedia",
                layout: "fitColumns",
                minHeight: 15,
                height: "100%",
                selectable: 1,
                paginationCounter: "rows",
                data: tempData,
                columns: [
                    { title: "ID Pegawai", field: "id" },
                    { title: "Username", field: "username" },
                    { title: "Nama Pegawai", field: "nama_pegawai" },
                    { title: "Unit", field: "unit" },
                    { title: "Tanggal", field: "tanggal" },
                    { title: "Jam Masuk", field: "jam_masuk" },
                    { title: "Jam Keluar", field: "jam_keluar" },
                    { title: "Total Waktu", field: "total_waktu" }
                ],
                ajaxURL: "{{ url('http://presensi-face-untan.test/api/kehadiran') }}",
                ajaxParams: function() {
                    return {
                        username: $('.formPegawai').val(),
                    };
                },
                ajaxResponse: function(url, params, response) {
                    return response;
                },
            });

            // Mengambil data dari URL menggunakan AJAX
            $.ajax({
                url: "{{ url('http://presensi-face-untan.test/api/kehadiran') }}",
                method: "GET",
                success: function(response) {
                    let tableData = [];
                    for (let date in response) {
                        for (let id in response[date]) {
                            let row = response[date][id];
                            tableData.push({
                                id: id,
                                username: row.username,
                                nama_pegawai: row.nama_pegawai,
                                unit: row.unit_departement,
                                tanggal: row.tanggal,
                                jam_masuk: row.jam_masuk,
                                jam_keluar: row.jam_keluar,
                                total_waktu: row.total_waktu
                                // Anda bisa menambahkan field lain sesuai kebutuhan
                            });

                            tempData.push({
                                id: id,
                                username: row.username,
                                nama_pegawai: row.nama_pegawai,
                                unit: row.unit_departement,
                                tanggal: row.tanggal,
                                jam_masuk: row.jam_masuk,
                                jam_keluar: row.jam_keluar,
                                total_waktu: row.total_waktu
                            });
                        }
                    }
                    // Setelah mendapatkan data, masukkan ke dalam tabel
                    // table.setData(tableData);
                },
                error: function(xhr, status, error) {
                    console.error("Error:", error);
                }
            });

            $('.formPegawai').change(function() {
                table.setData();
            });
        </script>
    @endsection
</x-app-layout>
