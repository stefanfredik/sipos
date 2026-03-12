<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Balita</title>
    @include('laporan._styles')
</head>
<body>
    @include('laporan._header', ['judul' => 'Laporan Data Balita'])

    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 30px;">No</th>
                <th>NIK</th>
                <th>Nama</th>
                <th>Orang Tua</th>
                <th class="text-center">JK</th>
                <th class="text-center">BB (kg)</th>
                <th class="text-center">TB (cm)</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $i => $item)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td style="font-family: monospace; font-size: 10px;">{{ $item->nik }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->nama_orang_tua }}</td>
                    <td class="text-center">{{ $item->jenis_kelamin }}</td>
                    <td class="text-center">
                        {{ $item->pemeriksaan->isNotEmpty() ? $item->pemeriksaan->first()->berat_badan : '-' }}
                    </td>
                    <td class="text-center">
                        {{ $item->pemeriksaan->isNotEmpty() ? $item->pemeriksaan->first()->tinggi_badan : '-' }}
                    </td>
                    <td class="text-center">
                        <span class="badge {{ $item->is_active ? 'badge-aktif' : 'badge-nonaktif' }}">
                            {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center" style="padding: 24px; color: #999;">Tidak ada data</td></tr>
            @endforelse
        </tbody>
    </table>

    <p class="summary">Total: {{ count($data) }} balita</p>
</body>
</html>
