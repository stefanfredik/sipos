<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Bulanan</title>
    @include('laporan._styles')
</head>
<body>
    @include('laporan._header', [
        'judul' => 'Laporan Rekapitulasi Pemeriksaan Kesehatan',
        'periode' => $periode ?? '-',
    ])

    {{-- Summary --}}
    <table style="width: auto; margin-bottom: 16px;">
        <tr>
            <td style="border: none; padding: 4px 16px 4px 0;"><strong>Ibu Hamil:</strong> {{ $stats['total_bumil'] }}</td>
            <td style="border: none; padding: 4px 16px 4px 0;"><strong>Balita:</strong> {{ $stats['total_balita'] }}</td>
            <td style="border: none; padding: 4px 16px 4px 0;"><strong>Lansia:</strong> {{ $stats['total_lansia'] }}</td>
            <td style="border: none; padding: 4px 0;"><strong>Kehadiran:</strong> {{ $stats['total_hadir'] }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 30px;">No</th>
                <th>Tgl. Periksa</th>
                <th>Nama / NIK</th>
                <th class="text-center">Kategori</th>
                <th>Lokasi</th>
                <th class="text-center">BB (kg)</th>
                <th class="text-center">TB (cm)</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $i => $item)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tgl_pemeriksaan)->translatedFormat('d M Y') }}</td>
                    <td>
                        {{ $item->peserta?->nama ?? '-' }}<br>
                        <span style="font-family: monospace; font-size: 9px; color: #888;">{{ $item->peserta?->nik ?? '-' }}</span>
                    </td>
                    <td class="text-center" style="font-size: 10px;">
                        @switch($item->peserta_type)
                            @case('ibu_hamil') Ibu Hamil @break
                            @case('balita') Balita @break
                            @case('lansia') Lansia @break
                            @default {{ $item->peserta_type }}
                        @endswitch
                    </td>
                    <td>{{ $item->jadwal?->posyandu?->nama_posyandu ?? $item->jadwal?->posyandu?->nama ?? '-' }}</td>
                    <td class="text-center">{{ $item->berat_badan ?? '-' }}</td>
                    <td class="text-center">{{ $item->tinggi_badan ?? '-' }}</td>
                    <td class="text-center">
                        <span class="badge {{ $item->hadir ? 'badge-hadir' : 'badge-absen' }}">
                            {{ $item->hadir ? 'Hadir' : 'Absen' }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center" style="padding: 24px; color: #999;">Tidak ada data untuk periode ini</td></tr>
            @endforelse
        </tbody>
    </table>

    <p class="summary">Total pemeriksaan: {{ count($data) }}</p>
</body>
</html>
