<div style="text-align: center; margin-bottom: 24px; border-bottom: 2px solid #333; padding-bottom: 16px;">
    <h2 style="margin: 0; font-size: 18px; text-transform: uppercase; letter-spacing: 1px;">Sistem Informasi Posyandu (SIPOS)</h2>
    <p style="margin: 4px 0 0; font-size: 13px; color: #555;">Posyandu Desa Belumbang, Kec. Kerambitan, Kab. Tabanan, Bali</p>
    @if(isset($judul))
        <h3 style="margin: 12px 0 0; font-size: 15px;">{{ $judul }}</h3>
    @endif
    @if(isset($periode))
        <p style="margin: 4px 0 0; font-size: 12px; color: #666;">Periode: {{ $periode }}</p>
    @endif
    <p style="margin: 4px 0 0; font-size: 11px; color: #888;">Dicetak: {{ now()->translatedFormat('d F Y H:i') }} WITA</p>
</div>
