<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>KMS Ibu Hamil — {{ $ibuHamil->nama }}</title>
    @include('laporan._styles')
    <style>
        .info-grid { display: table; width: 100%; margin-bottom: 16px; }
        .info-row { display: table-row; }
        .info-label { display: table-cell; padding: 3px 8px 3px 0; font-weight: 600; width: 140px; font-size: 11px; color: #555; }
        .info-value { display: table-cell; padding: 3px 0; font-size: 11px; }
    </style>
</head>
<body>
    @include('laporan._header', ['judul' => 'Kartu Menuju Sehat (KMS) — Ibu Hamil'])

    <div class="info-grid">
        <div class="info-row"><div class="info-label">Nama</div><div class="info-value">{{ $ibuHamil->nama }}</div></div>
        <div class="info-row"><div class="info-label">NIK</div><div class="info-value">{{ $ibuHamil->nik }}</div></div>
        <div class="info-row"><div class="info-label">Tanggal Lahir</div><div class="info-value">{{ $ibuHamil->tgl_lahir?->translatedFormat('d F Y') }}</div></div>
        <div class="info-row"><div class="info-label">Usia Kehamilan</div><div class="info-value">{{ $ibuHamil->usia_kehamilan ?? '-' }} minggu</div></div>
        <div class="info-row"><div class="info-label">No. Telp</div><div class="info-value">{{ $ibuHamil->no_telp }}</div></div>
    </div>

    <h4 style="margin: 16px 0 8px; font-size: 12px;">Riwayat Pemeriksaan</h4>
    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 30px;">No</th>
                <th>Tanggal</th>
                <th class="text-center">BB (kg)</th>
                <th class="text-center">TB (cm)</th>
                <th class="text-center">LILA (cm)</th>
                <th class="text-center">Tensi</th>
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
                    <td class="text-center">
                        {{ $p->lila ?? '-' }}
                        @if($p->lila && $p->lila < 23.5)
                            <span style="color: #dc2626;">(KEK)</span>
                        @endif
                    </td>
                    <td class="text-center">{{ $p->tensi_darah ?? '-' }}</td>
                    <td style="font-size: 10px;">{{ $p->catatan ?? '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center" style="padding: 16px; color: #999;">Belum ada riwayat pemeriksaan</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
