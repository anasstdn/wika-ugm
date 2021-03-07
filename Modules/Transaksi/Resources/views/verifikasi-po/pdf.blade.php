<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Faktur Purchase Order</title>
    
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
                                        <td style="width:80%;text-align: left;color: black"><span style="font-size: 11px;font-weight: bold">WIKA-WG KSO <br>
                                            PROYEK PAKET 1 : PEMBANGUNAN SGLC DAN ERIC <br>
                                            UGM YOGYAKARTA
                                        </span>
                                        </td>
                                        <td style="width:10%;text-align: right"></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            <tr class="information">

                <td colspan="2" style="width: 100%;text-align: center;font-weight: bold;font-size: 11pt;color: black">FAKTUR PURCHASE ORDER</td>
            </tr>     
        </table>
        <br/>
        <table style="font-size: 9px;width: 100%;">
           {{--  <tr>
                <td width="13%">Kepada</td><td width="1%">:</td><td>_________________________</td>
                <td width="30%"></td>
                <td width="13%">Proyek</td><td width="1%">:</td><td>_________________________</td>
            </tr> --}}
            <tr>
                <td width="13%">No Purchase Order</td><td width="1%">:</td><td>{{ $data->no_po }}</td>
                <td width="30%"></td>
                <td  width="13%">Pembuat</td><td width="1%">:</td><td>{{ getProfileByUserId($data->user_input)->nama }}</td>
            </tr>
             <tr>
                <td width="13%">Supplier</td><td width="1%">:</td><td>{{ \App\Models\Supplier::find($data->supplier_id)->nama_supplier }}</td>
                <td width="30%"></td>
                <td  width="13%">Tanggal</td><td width="1%">:</td><td>{{ date_indo(date('Y-m-d',strtotime($data->tgl_pengajuan_po))) }}</td>
            </tr>
        </table>
        <table style="font-size: 10px">
            <tr class="heading">
                <td>No</td>
                <td>Jenis Material</td>
                <td>Spesifikasi</td>
                <td>QTY</td>
                <td>Harga</td>
                <td>Subtotal</td>
            </tr>
            @php
            $total = 0;
            @endphp
            @if(isset($data_detail) && !$data_detail->isEmpty())
            @foreach($data_detail as $key => $val)
            @php
            $material = \DB::table('material')->find($val->material_id);
            @endphp
            <tr class="item">
                <td>{{$key+1}}</td>
                <td>{{ $material->kode_material }} - {{ $material->material }}</td>
                <td>{{ $material->spesifikasi }}</td>
                <td>{{ $val->volume }} {{ $material->satuan }}</td>
                <td style="text-align: right"><div style="float:left">Rp. </div>{{ number_format($val->harga_per_unit,2,',','.') }}</td>
                <td style="text-align: right"><div style="float:left">Rp. </div>{{ number_format($val->subtotal,2,',','.') }}</td>
            </tr>
            @php
            $total += $val->subtotal;
            @endphp
            @endforeach
            @endif
            <tr class="item">
                <td colspan="5"><b>GRAND TOTAL</b></td>
                <td style="text-align: right"><b><div style="float:left">Rp. </div>{{ number_format($total,2,',','.') }}</b></td>
            </tr>
        </table>
        <br>
        <br>
        <table style="font-size: 10px;width: 100%;text-align: center">
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td>Yogyakarta, {{date_indo(date('Y-m-d'))}}</td>
            </tr>
            <tr>
                <td></td>
                <td>Mengetahui</td>
                <td>Menyetujui</td>
                <td>Dibuat Oleh</td>
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
                <td></td>
                <td>(.............................)<br/>Manager Proyek</td>
                <td>(.............................)<br/>Kasie Komersial</td>
                <td>(.............................)<br/>Pengadaan</td>
            </tr>
        </table>
    </div>
   
</body>
</html>
