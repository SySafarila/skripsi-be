<table border="1">
    <thead>
        <tr>
            <th>Tipe Registrasi</th>
            <th>Nomor Registrasi</th>
            <th>Nama Lengkap</th>
            <th>Jabatan</th>
            <th>Tanggal Lahir</th>
            <th>-</th>
            <th>-</th>
            <th>Tipe Registrasi</th>
            <th>Jabatan Tersedia</th>
            <th>Catatan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($positions as $position)
            @switch($loop->index)
                @case(0)
                    <tr>
                        <td>NIDN</td>
                        <td>000001</td>
                        <td>Contoh Nama</td>
                        <td>{{ Str::upper($positions[0]->division) }}</td>
                        <td>05122006</td>
                        <td></td>
                        <td></td>
                        <td>NIDN</td>
                        <td>{{ Str::upper($positions[0]->division) }}</td>
                        <td style="color: red;">*Kolom tipe registrasi disesuaikan dengan isi dari kolom I, kolom tipe pekerjaan disesuaikan dengan isi dari kolom J, format tanggal lahir DDMMYYYY contoh 08052007 karena tanggal lahir tersebut akan digunakan sebagai password default</td>
                    </tr>
                @break

                @case(1)
                    <tr>
                        <td>NIP</td>
                        <td>000002</td>
                        <td>Contoh Nama 2</td>
                        <td>{{ Str::upper($positions[1]->division) }}</td>
                        <td>05122007</td>
                        <td></td>
                        <td></td>
                        <td>NIP</td>
                        <td>{{ Str::upper($position->division) }}</td>
                    </tr>
                @break


                @default
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>{{ Str::upper($position->division) }}</td>
                </tr>
            @endswitch
        @endforeach
    </tbody>
</table>
