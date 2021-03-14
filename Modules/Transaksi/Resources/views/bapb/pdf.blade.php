<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Faktur Berita Acara Pengiriman Barang</title>
    
    <style>
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 5px;
            /*border: 1px solid #eee;*/
            box-shadow: 0 0 10px rgba(0, 0, 0, .15);
            font-size: 16px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }

        .invoice-box table {
            width: 100%;
            line-height: normal; /* inherit */
            text-align: left;
        }

        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }

   /* .invoice-box table tr td:nth-child(2) {
        text-align: right;
        }*/

    /*.invoice-box table tr.top table td {
        padding-bottom: 20px;
        }*/

        .invoice-box table tr.top table td.title {
            /*font-size: 45px;*/
            /*line-height: 45px;*/
            color: #333;
        }

        .invoice-box table tr.information table td {
            padding-bottom: 40px;
        }

        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }

        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.item td{
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }

        .invoice-box table tr.total td:nth-child(2) {
            border-top: 2px solid #eee;
            font-weight: bold;
        }

        @media only screen and (max-width: 600px) {
            .invoice-box table tr.top table td {
                width: 100%;
                display: block;
                text-align: center;
            }

            .invoice-box table tr.information table td {
                width: 100%;
                display: block;
                text-align: center;
            }
        }

        /** RTL **/
        .rtl {
            direction: rtl;
            font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        }

        .rtl table {
            text-align: right;
        }

        .rtl table tr td:nth-child(2) {
            text-align: left;
        }
    </style>
</head>

<body>
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title">
                                <table style="width:100%;">
                                    <tr>
                                        <td style="width:10%;"><img src="data:image/png;base64,{{base64_encode(file_get_contents(public_path('images/logo_wika3.png')))}}"></td>
                                        <td style="width:65%;text-align: left;color: black"><span style="font-size: 11px;font-weight: bold">WIKA-WG KSO <br>
                                            PROYEK PAKET 1 : PEMBANGUNAN SGLC DAN ERIC <br>
                                            UGM YOGYAKARTA
                                        </span>
                                        </td>
                                        <td style="width:25%;text-align: left">
                                            <div id="box" style="border:0.1px solid black;">
                                                <span style="font-size: 10px;font-weight: normal;margin-left: 1px">
                                                1. Supplier <br/>
                                                2. Gudang <br/>
                                                3. QC <br/>
                                                4. KSPP <br/>
                                                5. KSAK <br/>
                                            </span>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            <tr class="information">

                <td colspan="2" style="width: 100%;text-align: center;font-weight: bold;font-size: 11pt;color: black"><u>BERITA ACARA PENERIMAAN BARANG</u></td>
            </tr>     
        </table>
        <table style="font-size: 10px;width: 100%;">
            <tr>
                <td width="29%"></td>
                <td>
                    <table style="border:1px solid black;border-collapse: collapse;">
                        <tr>
                            <td width="5%"><b>No</b></td><td width="2%"><b>:</b></td><td><b>{{$data->no_bapb}}</b></td>
                        </tr>
                    </table>
                </td>
                <td width="30%"></td>
            </tr>
        </table>
        <table style="font-size: 10px;width: 100%;">
            <tr>
                <td width="29%"></td>
                <td>
                    <table style="border:1px solid black;border-collapse: collapse;">
                        <tr>
                            <td width="3%"><b>Leveransir</b></td><td width="2%"><b>:</b></td><td><b>{{$data->leveransir}}</b></td>
                        </tr>
                        <tr>
                            <td width="3%"><b>Srt. Jalan</b></td><td width="2%"><b>:</b></td><td><b>{{$data->no_surat_jalan}}</b></td>
                        </tr>
                         <tr>
                            <td width="3%"><b>Tanggal</b></td><td width="2%"><b>:</b></td><td><b>{{date_indo(date('Y-m-d',strtotime($data->tgl_surat_jalan)))}}</b></td>
                        </tr>
                        <tr>
                            <td width="3%"><b>Nopol</b></td><td width="2%"><b>:</b></td><td><b>{{$data->no_polisi}}</b></td>
                        </tr>
                    </table>
                </td>
                <td width="30%"></td>
            </tr>
        </table>
        <br/>
        <table style="font-size: 9px">
            <tr class="heading">
                <td>NO</td>
                <td>KODE SB. DAYA</td>
                <td>KODE TAHAP</td>
                <td>JENIS MATERIAL</td>
                <td>SATUAN</td>
                <td>VOLUME</td>
                <td width="12%">H SATUAN</td>
                <td width="15%">JUMLAH</td>
                <td>KETERANGAN</td>
            </tr>
            @php
            $total = 0;
            @endphp
            @if(isset($data_detail) && !$data_detail->isEmpty())
            @foreach($data_detail as $key => $val)
            @php
            $material = \DB::table('material')->find($val->material_id);

            $harga = \DB::table('detail_bapb')->select('detail_po.*')
                    ->join('bapb','bapb.id','=','detail_bapb.bapb_id')
                     ->join('po_bapb','bapb.id','=','po_bapb.bapb_id')
                     ->join('po','po.id','=','po_bapb.po_id')
                     ->join('detail_po','detail_po.po_id','=','po.id')
                     ->where('detail_bapb.id','=',$val->id)
                     ->where('detail_po.material_id','=',$val->material_id)
                     ->first();
            @endphp
            <tr class="item">
                <td>{{$key+1}}</td>
                <td>{{ $val->kode_sb_daya }}</td>
                <td>{{ $val->kode_tahap }}</td>
                <td>{{ $material->material }}</td>
                <td>{{ $material->satuan }}</td>
                <td>{{ $val->volume }}</td>
                <td style="text-align: right"><div style="float:left">Rp. </div>{{ number_format($harga->harga_per_unit,2,',','.') }}</td>
                <td style="text-align: right"><div style="float:left">Rp. </div>{{ number_format(($val->volume * $harga->harga_per_unit),2,',','.') }}</td>
                <td>{{ $val->keterangan }}</td>
            </tr>
            @php
            $total += ($val->volume * $harga->harga_per_unit);
            @endphp
            @endforeach
            @endif
            <tr class="item">
                <td colspan="7" style="text-align:right;"><b>JUMLAH</b></td>
                <td style="text-align: right"><b><div style="float:left">Rp. </div>{{ number_format($total,2,',','.') }}</b></td>
                <td></td>
            </tr>
        </table>
        <table style="font-size: 9px;width: 30%;text-align:left;" cellspacing="0">
            <tr>
                <td width="20%">Catatan</td>
                <td width="1%">:</td>
                <td></td>
            </tr>
            <tr>
                <td>1-5 & 8</td>
                <td>:</td>
                <td>Diisi oleh Bag. Gudang</td>
            </tr>
            <tr>
                <td>6-7</td>
                <td>:</td>
                <td>Diisi oleh Bag. KSPP</td>
            </tr>
        </table>
        <br>
        <table style="font-size: 10px;width: 100%;text-align: center">
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td>Yogyakarta, {{date_indo(date('Y-m-d',strtotime($data->created_at)))}}</td>
            </tr>
            <tr>
                <td>KSPP</td>
                <td>KSKA</td>
                <td>QC</td>
                <td>Gudang</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>(.............................)</td>
                <td>(.............................)</td>
                <td>(.............................)</td>
                <td>(.............................)</td>
            </tr>
        </table>
        <span style="font-size: 8px">* Harap dilampirkan pada saat penagihan</span>
    </div>
   
</body>
</html>
