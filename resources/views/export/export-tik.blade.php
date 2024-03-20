<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th>NIP</th>
                <th>Nama</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Jam Pulang</th>
                <th>Total Waktu</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
                <tr>
                    <td>{{ "'".$item['nip'] }}</td>
                    <td>{{ $item['nama_pegawai'] }}</td>
                    <td>{{ $item['tanggal'] }}</td>
                    <td>{{ $item['jam_masuk'] }}</td>
                    <td>{{ $item['jam_keluar'] }}</td>
                    <td>{{ $item['total_waktu'] }}</td>
                    <td>{{ $item['keterangan'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
