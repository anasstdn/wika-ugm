<table>
    <thead>
        <tr>
            <td colspan="13" style="text-align: center"><b>LAPORAN SURAT PERMINTAAN BARANG</b></td>
        </tr>
        <tr>
            {{-- @foreach ($data[0] as $key => $value)
		<th>{{ ucfirst($key) }}</th>
	    @endforeach --}}
            <td>No</td>
            <td>Nomor SPM</td>
            <td>Tanggal</td>
            <td>Kode Material</td>
            <td>Material</td>
            <td>Volume</td>
            <td>Satuan</td>
            <td>Nama Pemohon</td>
            <td>Lokasi</td>
            <td>SPM Batal</td>
            <td>Site Manager</td>
            <td>Komersial</td>
            <td>Project Manager</td>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $row)
            <tr>
                @foreach ($row as $value)
                    <td>{{ $value }}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
