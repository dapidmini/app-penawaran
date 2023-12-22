@foreach ($mydata as $row1)
    <h3>{{ number_format($loop->index+1) }}.</h3>
    <h4>{{ date('d-M-Y H:i:s', strtotime($row1->tgl_pengajuan)) }}</h4>
    <h4>Rp {{ number_format($row1->penjualan_kotor) }}</h4>
    <h4>Rp {{ number_format($row1->profit) }}</h4>

    <ul>
        @foreach ($row1->barangs as $barang)
            <li>{{ $barang->nama }}</li>
        @endforeach
    </ul>
@endforeach