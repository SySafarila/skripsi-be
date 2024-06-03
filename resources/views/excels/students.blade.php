<table border="1">
    <thead>
        <tr>
            {{-- <th>Tipe Registrasi</th> --}}
            <th>NIM</th>
            <th>Nama Lengkap</th>
            {{-- <th>Jabatan</th> --}}
            <th>Tanggal Lahir</th>
            <th>Semester</th>
            <th>Jurusan</th>
            <th>-</th>
            <th>-</th>
            <th>Daftar Jurusan</th>
            <th></th>
            <th>Catatan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($majors as $major)
            @switch($loop->index)
                @case(0)
                    <tr>
                        {{-- <td>NIDN</td> --}}
                        <td>123</td>
                        <td>Contoh Nama</td>
                        {{-- <td>DOSEN</td> --}}
                        <td>08052001</td>
                        <td>8</td>
                        <td>{{ Str::upper('Teknik Informatika') }}</td>
                        <td></td>
                        <td></td>
                        <td>{{ Str::upper($major->major) }}</td>
                        <td></td>
                        <td style="color: red;">*Format tanggal lahir DDMMYYYY contoh 08052007 karena tanggal lahir tersebut akan digunakan sebagai password default, kolom Jurusan harus sesuai dengan isian pada kolom H</td>
                    </tr>
                @break

                @case(1)
                    <tr>
                        {{-- <td>NIDN</td> --}}
                        <td></td>
                        <td></td>
                        {{-- <td>DOSEN</td> --}}
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>{{ Str::upper($major->major) }}</td>
                    </tr>
                @break


                @default
                <tr>
                    {{-- <td>NIDN</td> --}}
                    <td></td>
                    <td></td>
                    {{-- <td>DOSEN</td> --}}
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>{{ Str::upper($major->major) }}</td>
                </tr>
            @endswitch
        @endforeach
    </tbody>
</table>
