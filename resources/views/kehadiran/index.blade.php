<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link href="https://cdn.datatables.net/v/dt/dt-2.0.1/datatables.min.css" rel="stylesheet">


    @vite([])
</head>

<body>

    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                <a class="navbar-brand" href="#">Hidden brand</a>
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Link</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" aria-disabled="true">Disabled</a>
                    </li>
                </ul>
                <form class="d-flex" role="search">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container">

        <h1 class="text-center mt-3">
            Kehadiran
        </h1>

        <div class="mt-5">
            <div class="row">
                <div class="col">

                    <div class="mb-3">
                        <label for="nama-pegawai" class="form-label">Nama Pegawai</label>
                        <input type="text" class="form-control" id="nama-pegawai" placeholder="Nama Pegawai">
                    </div>
                </div>

                <div class="col">
                    <label for="unit-kerja" class="form-label">Unit Kerja</label>
                    <select class="form-select" aria-label="Unit Kerja" id="unit-kerja" name="unit_kerja">
                        <option value=""></option>
                        <option value="1">UPT TIK</option>
                        <option value="2">BAK</option>
                        <option value="3">BUK</option>
                    </select>
                </div>

                <div class="col">
                    <label for="tanggal" class="form-label">Tanggal</label>
                    <input type="date" class="form-control" id="tanggal" name="tanggal">
                </div>
            </div>
        </div>

        <a href="#" target="__blank" class="btn btn-primary btn-sm">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                class="bi bi-cloud-arrow-down" viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                    d="M7.646 10.854a.5.5 0 0 0 .708 0l2-2a.5.5 0 0 0-.708-.708L8.5 9.293V5.5a.5.5 0 0 0-1 0v3.793L6.354 8.146a.5.5 0 1 0-.708.708z" />
                <path
                    d="M4.406 3.342A5.53 5.53 0 0 1 8 2c2.69 0 4.923 2 5.166 4.579C14.758 6.804 16 8.137 16 9.773 16 11.569 14.502 13 12.687 13H3.781C1.708 13 0 11.366 0 9.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383m.653.757c-.757.653-1.153 1.44-1.153 2.056v.448l-.445.049C2.064 6.805 1 7.952 1 9.318 1 10.785 2.23 12 3.781 12h8.906C13.98 12 15 10.988 15 9.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 4.825 10.328 3 8 3a4.53 4.53 0 0 0-2.941 1.1z" />
            </svg>
            Download
        </a>

        <div class="table-responsive my-5">
            <table class="table table-hover table-stripped" id="table" style="width: 100%">
                <thead>
                    <tr>
                        <th>ID Pegawai</th>
                        <th>Username</th>
                        <th>Nama Pegawai</th>
                        <th>Unit</th>
                        <th>Tanggal</th>
                        <th>Jam Masuk</th>
                        <th>Jam Keluar</th>
                        <th>Total Waktu</th>
                    </tr>
                </thead>
                <tbody id="tbody">

                </tbody>
            </table>
        </div>

    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="https://cdn.datatables.net/v/dt/dt-2.0.1/datatables.min.js"></script>

    <script>
        const csrf = `{{ csrf_token() }}`

        $("#unit-kerja").select2({
            placeholder: 'Pilih Unit Kerja'
        })

        var table = new DataTable('#table');

        $("#nama-pegawai").on("change", function() {
            const nama_pegawai = $("#nama-pegawai").val()
            const unit_kerja = $("#unit-kerja").val()
            const tanggal = $("#tanggal").val()

            table.clear().draw();

            const postData = {
                nama_pegawai,
                unit_kerja,
                tanggal,
                _token: csrf
            }

            const urlData = `{{ route('kehadiran.data') }}`
            $.post(urlData, postData, function(datas) {
                console.log(datas)

                $.each(datas, function(index, value) {
                    table.row.add($(`
                    <tr>
                        <td class="text-center">${value.id_pegawai}</td>
                        <td>${value.username}</td>
                        <td>${value.nama_pegawai}</td>
                        <td>${value.unit}</td>
                        <td>${value.tanggal}</td>
                        <td class="text-center">${value.jam_masuk}</td>
                        <td class="text-center">${value.jam_keluar}</td>
                        <td class="text-center">${value.total_waktu} Jam</td>
                    </tr>
                    `)).draw()
                })

                table.data.reload().draw();
            })
        })

        $("#unit-kerja").on("change", function() {
            const nama_pegawai = $("#nama-pegawai").val()
            const unit_kerja = $("#unit-kerja").val()
            const tanggal = $("#tanggal").val()

            table.clear().draw();

            const postData = {
                nama_pegawai,
                unit_kerja,
                tanggal,
                _token: csrf
            }

            const urlData = `{{ route('kehadiran.data') }}`

            $.post(urlData, postData, function(datas) {
                console.log(datas)

                $.each(datas, function(index, value) {
                    table.row.add($(`
                    <tr>
                        <td class="text-center">${value.id_pegawai}</td>
                        <td>${value.username}</td>
                        <td>${value.nama_pegawai}</td>
                        <td>${value.unit}</td>
                        <td>${value.tanggal}</td>
                        <td class="text-center">${value.jam_masuk}</td>
                        <td class="text-center">${value.jam_keluar}</td>
                        <td class="text-center">${value.total_waktu} Jam</td>
                    </tr>
                    `)).draw()
                })

                table.data.reload().draw();
            })
        })

        $("#tanggal").on("change", function() {
            const nama_pegawai = $("#nama-pegawai").val()
            const unit_kerja = $("#unit-kerja").val()
            const tanggal = $("#tanggal").val()

            table.clear().draw();

            const postData = {
                nama_pegawai,
                unit_kerja,
                tanggal,
                _token: csrf
            }

            const urlData = `{{ route('kehadiran.data') }}`

            $.post(urlData, postData, function(datas) {
                console.log(datas)

                $.each(datas, function(index, value) {
                    table.row.add($(`
                    <tr>
                        <td class="text-center">${value.id_pegawai}</td>
                        <td>${value.username}</td>
                        <td>${value.nama_pegawai}</td>
                        <td>${value.unit}</td>
                        <td>${value.tanggal}</td>
                        <td class="text-center">${value.jam_masuk}</td>
                        <td class="text-center">${value.jam_keluar}</td>
                        <td class="text-center">${value.total_waktu} Jam</td>
                    </tr>
                    `)).draw()
                })

                table.data.reload().draw();
            })
        })
    </script>

</body>

</html>
