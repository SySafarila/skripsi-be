<table border="1">
    <thead>
        <tr>
            <th>Mata Kuliah</th>
            <th>Semester</th>
            <th>Jurusan</th>
            <th>Dosen</th>
            <th>-</th>
            <th>-</th>
            <th>Daftar Dosen Tersedia</th>
            <th>Daftar Jurusan Tersedia</th>
            <th>-</th>
            <th>-</th>
            <th>Catatan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($loop_sample as $index => $data)
            <tr>
                <td>{{ $loop->index == 0 ? 'Contoh Mata Kuliah' : '' }}</td>
                <td>{{ $loop->index == 0 ? '1' : '' }}</td>
                <td>{{ $loop->index == 0 ? 'CONTOH JURUSAN' : '' }}</td>
                <td>{{ $loop->index == 0 ? 'CONTOH DOSEN' : '' }}</td>
                <td></td>
                <td></td>
                <td>{{ @$users[$index]->name ? Str::upper(@$users[$index]->name) : '' }}</td>
                <td>{{ @$majors[$index]->major ? Str::upper(@$majors[$index]->major) : '' }}</td>
                <td></td>
                <td></td>
                <td>{{ $loop->index == 0 ? '*Isi kolom Dosen & Jurusan dengan masing-masing daftar yang tersedia agar terbaca oleh sistem' : '' }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
