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
                    <td style="width: 150px">{{ "'".$item['nip'] }}</td>
                    <td style="width: 250px">{{ $item['nama_pegawai'] }}</td>
                    <td style="width: 150px">{{ $item['tanggal'] }}</td>
                    <td style="width: 150px">{{ $item['jam_masuk'] }}</td>
                    <td style="width: 150px">{{ $item['jam_keluar'] }}</td>
                    <td style="width: 150px">{{ $item['total_waktu'] }}</td>
                    <td style="width: 150px">{{ $item['keterangan'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
