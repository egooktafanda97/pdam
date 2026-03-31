<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran - PDAM</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            margin: 0;
            padding: 20px;
            color: #000;
        }
        .struk-container {
            width: 300px;
            margin: 0 auto;
            border: 1px dashed #ccc;
            padding: 15px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-bold { font-weight: bold; }
        .divider { border-bottom: 1px dashed #000; margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 2px 0; vertical-align: top; }
        .action-buttons {
            width: 300px;
            margin: 20px auto;
            text-align: center;
        }
        .btn {
            padding: 8px 15px;
            background: #435ebe;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-family: sans-serif;
            font-size: 14px;
        }
        .btn-secondary { background: #6c757d; }
        
        @media print {
            .action-buttons { display: none; }
            body { padding: 0; }
            .struk-container { border: none; }
        }
    </style>
</head>
<body>

    <div class="action-buttons">
        <button class="btn" onclick="window.print()">Cetak</button>
        <a href="{{ route('petugas.tagihan') }}" class="btn btn-secondary">Tutup</a>
    </div>

    <div class="struk-container">
        <div class="text-center">
            <h3 style="margin: 0;">PDAM</h3>
            <p style="margin: 5px 0;">Jl. Air Bersih No. 1, Kota Air</p>
            <p style="margin: 0;">Telp: (021) 1234567</p>
        </div>

        <div class="divider"></div>

        <table>
            <tr>
                <td width="35%">No. Referensi</td>
                <td width="5%">:</td>
                <td>{{ $pembayaran->kode_pembayaran }}</td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>:</td>
                <td>{{ $pembayaran->created_at->format('d/m/Y H:i:s') }}</td>
            </tr>
            <tr>
                <td>Petugas/Kasir</td>
                <td>:</td>
                <td>{{ substr($pembayaran->petugas->name ?? 'Sistem', 0, 15) }}</td>
            </tr>
        </table>

        <div class="divider"></div>

        <table>
            <tr>
                <td width="35%">No. Pelanggan</td>
                <td width="5%">:</td>
                <td class="text-bold">{{ $pembayaran->tagihan->pelanggan->nomor_pelanggan }}</td>
            </tr>
            <tr>
                <td>Nama</td>
                <td>:</td>
                <td>{{ substr($pembayaran->tagihan->pelanggan->nama, 0, 15) }}</td>
            </tr>
            <tr>
                <td>Periode</td>
                <td>:</td>
                <td>{{ $pembayaran->tagihan->periode }}</td>
            </tr>
            <tr>
                <td>Meter A-A</td>
                <td>:</td>
                <td>{{ $pembayaran->tagihan->pemakaianAir->meter_awal }} - {{ $pembayaran->tagihan->pemakaianAir->meter_akhir }} ({{ $pembayaran->tagihan->pemakaianAir->total_pemakaian }} m³)</td>
            </tr>
        </table>

        <div class="divider"></div>

        <table>
            <tr>
                <td>Tagihan Air</td>
                <td>:</td>
                <td class="text-right">Rp {{ number_format($pembayaran->tagihan->pemakaianAir->total_pemakaian * $pembayaran->tagihan->pelanggan->golonganTarif->tarif_per_m3, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Administrasi</td>
                <td>:</td>
                <td class="text-right">Rp {{ number_format($pembayaran->tagihan->biaya_admin, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Denda</td>
                <td>:</td>
                <td class="text-right">Rp {{ number_format($pembayaran->tagihan->denda, 0, ',', '.') }}</td>
            </tr>
        </table>

        <div class="divider"></div>

        <table>
            <tr>
                <td class="text-bold">TOTAL TAGIHAN</td>
                <td width="5%">:</td>
                <td class="text-right text-bold">Rp {{ number_format($pembayaran->tagihan->total_tagihan, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Metode Bayar</td>
                <td>:</td>
                <td class="text-right">{{ $pembayaran->metode_bayar }}</td>
            </tr>
            <tr>
                <td>Jumlah Bayar</td>
                <td>:</td>
                <td class="text-right">Rp {{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}</td>
            </tr>
            @if($pembayaran->jumlah_bayar > $pembayaran->tagihan->total_tagihan)
            <tr>
                <td>[Kembalian]</td>
                <td>:</td>
                <td class="text-right">Rp {{ number_format($pembayaran->jumlah_bayar - $pembayaran->tagihan->total_tagihan, 0, ',', '.') }}</td>
            </tr>
            @endif
        </table>

        <div class="divider"></div>

        <div class="text-center" style="margin-top: 15px;">
            <p style="margin: 0; font-size: 14px;" class="text-bold">*** LUNAS ***</p>
            <p style="margin: 10px 0 0 0;">Terima kasih atas pembayaran Anda.</p>
            <p style="margin: 0;">Lebih Bijak Menggunakan Air.</p>
        </div>
    </div>

</body>
</html>
