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
                    <select class="form-select form-select-sm mb-3 mb-lg-0 unit" data-control="select2" data-placeholder="Pilih Unit" data-allow-clear="true" name="unit">
                        <option></option>
                        @foreach ($unit as $item)
                            <option value="{{ $item->dept_code }}">{{ $item->dept_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="">Nama Pegawai</label>
                    <input type="text" class="form-control form-control-sm username" placeholder="Ketik Nama Pegawai" style="border-radius: 5px" name="username">
                </div>
                <div class="col-md-3">
                    <label for="">Periode</label>
                    <input type="text" name="tanggal" placeholder="Pilih Tanggal" class="form-control form-control-sm tanggal" style="border-radius: 5px" >
                </div>
                <div class="col-sm-2">
                    <label for="">Aksi</label>
                    <div class="d-flex">
                        <button class="btn btn-primary btn-sm cariPegawai" type="button"><i class="fas fa-search mr-1"></i>Cari</button>
                        <a href="" target="_blank" id="buttonCetak" class="btn btn-success btn-sm ms-2" type="button"><i class="fas fa-file-excel mr-1"></i>Cetak</a>
                    </div>
                </div>
            </div>
            <div class="card shadow">
                <div class="table-responsive py-3 px-3">
                    <table id="tabel-kehadiran" class="table-sm table-bordered">
                        <thead>
                            <tr>
                                {{-- <th>ID Pegawai</th> --}}
                                <th style="text-align: left">NIP</th>
                                <th style="text-align: left">Nama Pegawai</th>
                                <th style="text-align: left">Unit</th>
                                <th style="text-align: left">Tanggal</th>
                                <th style="text-align: left">Jam Masuk</th>
                                <th style="text-align: left">Jam Keluar</th>
                                <th style="text-align: left">Total Waktu</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>


    @section('js')
        <script>
            $(".tanggal").flatpickr({
                altInput: true,
                altFormat: "d/m/Y",
                dateFormat: "Y-m-d",
                mode: "range",
                locale: "id",
                defaultDate: "today"
            });

            $(document).ready(function() {
                clearSession();

                var oTable = $('#tabel-kehadiran').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('kehadiran.getData') }}",
                        data: function(d) {
                            d.unit = sessionStorage.unit;
                            d.username = sessionStorage.username;
                            d.tanggal = sessionStorage.tanggal;
                        }
                    },
                    columns: [
                        // { data: 'kode_pegawai', name: 'kode_pegawai' },
                        { data: 'nip', name: 'nip' },
                        { data: 'nama_pegawai', name: 'nama_pegawai' },
                        { data: 'unit_departement', name: 'unit_departement' },
                        { data: 'tanggal', name: 'tanggal' },
                        { data: 'jam_masuk', name: 'jam_masuk' },
                        { data: 'jam_keluar', name: 'jam_keluar' },
                        { data: 'total_waktu', name: 'total_waktu' }
                    ],
                    order: [
                        [1, 'asc'],
                        [3, 'asc']
                    ],
                    rowCallback: function(row, data) {
                        if (data.nip === null) {
                            $(row).css('background-color', '#FF6347');
                        }
                        else if (data.nip === "") {
                            $(row).css('background-color', '#FF6347');
                        }
                    }
                });

                function clearSession() {
                    sessionStorage.removeItem('unit');
                    sessionStorage.removeItem('username');
                    sessionStorage.removeItem('tanggal');
                }

                $('.cariPegawai').on('click', function(e) {
                    var formUnit = $('.unit').val();
                    var formPegawai = $('.username').val();
                    var formTanggal = $('.tanggal').val();

                    sessionStorage.setItem('unit', formUnit);
                    sessionStorage.setItem('username', formPegawai);
                    sessionStorage.setItem('tanggal', formTanggal);

                    oTable.draw();
                    e.preventDefault();

                    var url = location.protocol + '//' + location.host + '/kehadiran/' + 'export-tik?username='+ formPegawai + '&unit=' + formUnit + '&tanggal=' + formTanggal;
                    var links = encodeURI(url);
                    console.log(links)

                    $("#buttonCetak").attr("href", links);
                });
            });
        </script>
    @endsection
</x-app-layout>
