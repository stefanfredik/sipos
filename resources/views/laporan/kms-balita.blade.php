<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>KMS Balita — {{ $balita->nama }}</title>
    @include('laporan._styles')
    <style>
        .info-grid { display: table; width: 100%; margin-bottom: 16px; }
        .info-row { display: table-row; }
        .info-label { display: table-cell; padding: 3px 8px 3px 0; font-weight: 600; width: 140px; font-size: 11px; color: #555; }
        .info-value { display: table-cell; padding: 3px 0; font-size: 11px; }
    </style>
</head>
<body>
    @include('laporan._header', ['judul' => 'Kartu Menuju Sehat (KMS) — Balita'])

    <div class="info-grid">
        <div class="info-row"><div class="info-label">Nama</div><div class="info-value">{{ $balita->nama }}</div></div>
        <div class="info-row"><div class="info-label">NIK</div><div class="info-value">{{ $balita->nik }}</div></div>
        <div class="info-row"><div class="info-label">Tanggal Lahir</div><div class="info-value">{{ $balita->tgl_lahir?->translatedFormat('d F Y') }}</div></div>
        <div class="info-row"><div class="info-label">Jenis Kelamin</div><div class="info-value">{{ $balita->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</div></div>
        <div class="info-row"><div class="info-label">Orang Tua</div><div class="info-value">{{ $balita->nama_orang_tua }}</div></div>
    </div>

    <h4 style="margin: 16px 0 8px; font-size: 12px;">Riwayat Pemeriksaan</h4>
    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 30px;">No</th>
                <th>Tanggal</th>
                <th class="text-center">BB (kg)</th>
                <th class="text-center">TB (cm)</th>
                <th class="text-center">Lingkar Kepala</th>
                <th class="text-center">Obat Cacing</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pemeriksaan as $i => $p)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $p->tgl_pemeriksaan->translatedFormat('d M Y') }}</td>
                    <td class="text-center">{{ $p->berat_badan ?? '-' }}</td>
                    <td class="text-center">{{ $p->tinggi_badan ?? '-' }}</td>
                    <td class="text-center">{{ $p->lingkar_kepala ?? '-' }}</td>
                    <td class="text-center">{{ $p->obat_cacing ? 'Ya' : 'Tidak' }}</td>
                    <td style="font-size: 10px;">{{ $p->catatan ?? '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center" style="padding: 16px; color: #999;">Belum ada riwayat pemeriksaan</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
