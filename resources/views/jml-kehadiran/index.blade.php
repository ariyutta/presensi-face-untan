<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Jumlah Kehadiran') }}
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
                <div class="col-sm-1">
                    <label for="">Aksi....</label>
                    <button class="btn btn-primary btn-sm cariPegawai" type="button">Cari Pegawai</button>
                </div>
                <div class="col-sm-1">
                    <label for="">Cetak....</label>
                    <a href="" target="_blank" id="buttonCetak" class="btn btn-success btn-sm" type="button">Cetak Pegawai</a>
                </div>
            </div>
            <div class="card shadow">
                <div class="table-responsive py-3 px-3">
                    <table id="tabel-jml-kehadiran" class="table-sm table-bordered">
                        <thead>
                            <tr>
                                <th style="text-align: left">NIP</th>
                                <th style="text-align: left">Nama Pegawai</th>
                                <th style="text-align: left">Unit</th>
                                <th style="text-align: left">Kehadiran</th>
                                <th style="text-align: left">Tidak Hadir</th>
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

                var oTable = $('#tabel-jml-kehadiran').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('kehadiran.getJmlKehadiran') }}",
                        data: function(d) {
                            d.unit = sessionStorage.unit;
                            d.username = sessionStorage.username;
                            d.tanggal = sessionStorage.tanggal;
                        }
                    },
                    columns: [
                        { data: 'nip', name: 'nip' },
                        { data: 'nama_pegawai', name: 'nama_pegawai' },
                        { data: 'unit', name: 'unit' },
                        { data: 'kehadiran', name: 'kehadiran' },
                        { data: 'tidak_hadir', name: 'tidak_hadir' }
                    ],
                    order: [
                        [1, 'asc']
                    ],
                    rowCallback: function(row, data) {
                        if (data.nip === null) {
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

                    var url = location.protocol + '//' + location.host + '/kehadiran/' + 'export-jml-kehadiran?username='+ formPegawai + '&unit=' + formUnit + '&tanggal=' + formTanggal;
                    var links = encodeURI(url);
                    console.log(links)

                    $("#buttonCetak").attr("href", links);
                });
            });
        </script>
    @endsection
</x-app-layout>
