<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Ibu Hamil</title>
    @include('laporan._styles')
</head>
<body>
    @include('laporan._header', ['judul' => 'Laporan Data Ibu Hamil'])

    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 30px;">No</th>
                <th>NIK</th>
                <th>Nama</th>
                <th class="text-center">Usia Kehamilan</th>
                <th class="text-center">LILA (cm)</th>
                <th>No. Telp</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $i => $item)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td style="font-family: monospace; font-size: 10px;">{{ $item->nik }}</td>
                    <td>{{ $item->nama }}</td>
                    <td class="text-center">{{ $item->usia_kehamilan ?? '-' }} minggu</td>
                    <td class="text-center">
                        @if($item->pemeriksaan->isNotEmpty() && $item->pemeriksaan->first()->lila)
                            {{ $item->pemeriksaan->first()->lila }}
                            @if($item->pemeriksaan->first()->lila < 23.5)
                                <span style="color: #dc2626; font-weight: bold;">(KEK)</span>
                            @endif
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $item->no_telp }}</td>
                    <td class="text-center">
                        <span class="badge {{ $item->is_active ? 'badge-aktif' : 'badge-nonaktif' }}">
                            {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center" style="padding: 24px; color: #999;">Tidak ada data</td></tr>
            @endforelse
        </tbody>
    </table>

    <p class="summary">Total: {{ count($data) }} ibu hamil</p>
</body>
</html>
