@extends('layouts.app')

@section('content')
<div class="bg-primary-dark">
	<div class="content content-top">
		<div class="row push">
			<div class="col-md py-10 d-md-flex align-items-md-center text-center">
				<h1 class="text-white mb-0">
					<span class="font-w300">Purchase Order</span>
					<span class="font-w400 font-size-lg text-white-op d-none d-md-inline-block">Detail Pengajuan PO</span>
				</h1>
			</div>
		</div>
	</div>
</div>


<div class="content">
	<div class="block block-themed">
		<div class="block-header bg-info">
			<h3 class="block-title"><i class="fa fa-user-circle mr-5 text-muted"></i>Detail Pengajuan PO</h3>
			<div class="block-options">
				<button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
					<i class="si si-refresh"></i>
				</button>
				<button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
			</div>
		</div>
		<div class="block-content block-content-full">
			<div class="form-row">
				<div class="form-group col-3">
					<label for="wizard-progress-nama-depan">Nomor PO</label>
					<input class="form-control form-control-sm" type="text" id="no_po" value="{{ isset($data->no_po)?$data->no_po:'' }}" readonly="">
				</div>
				<div class="form-group col-3">
					<label for="wizard-progress-nama-depan">Tanggal Pengajuan</label>
					<input class="form-control form-control-sm" type="text" id="tgl_pengajuan" value="{{ isset($data->tgl_pengajuan_po)?date_indo(date('Y-m-d',strtotime($data->tgl_pengajuan_po))):'' }}" readonly="">
				</div>
				<div class="form-group col-3">
					<label for="wizard-progress-nama-depan">Supplier</label>
					<input class="form-control form-control-sm" type="text" id="supplier" value="{{ isset($data->supplier->nama_supplier)?$data->supplier->nama_supplier:'' }}" readonly="">
				</div>
				<div class="form-group col-3">
					<label for="wizard-progress-nama-depan">Total</label>
					<input class="form-control form-control-sm" type="text" id="total" value="{{ isset($data->total_harga)?'Rp. '.number_format($data->total_harga,2,',','.'):'' }}" readonly="">
				</div>
			</div>
			<div class="form-row">
					<div class="form-group col-3">
						<label for="wizard-progress-nama-depan">Verifikasi Projek Manager</label>
						<br/>
						@if($data->flag_verif_pm == 'Y')
						<span class="badge badge-success">Disetujui</span>
						@elseif($data->flag_verif_pm == 'N')
						<span class="badge badge-danger">Ditolak</span>
						@else
						<span class="badge badge-danger">Menunggu Verifikasi</span>
						@endif
					</div>
					<div class="form-group col-3">
						<label for="wizard-progress-nama-depan">Verifikasi Komersil</label>
						<br/>
						@if($data->flag_verif_komersial == 'Y')
						<span class="badge badge-success">Disetujui</span>
						@elseif($data->flag_verif_komersial == 'N')
						<span class="badge badge-danger">Ditolak</span>
						@else
						<span class="badge badge-danger">Menunggu Verifikasi</span>
						@endif
					</div>
					<div class="form-group col-3">
						<label for="wizard-progress-nama-depan">User Pembuat</label>
						@php
						$nama = null;
						$pembuat = \DB::table('user_profil')->where('user_id',$data->user_input)->first();

						if(isset($pembuat) && !empty($pembuat->profil_id))
						{
							$profil = \DB::table('profil')->find($pembuat->profil_id);
							$nama = $profil->nama;
						}
						else
						{
							$users = \DB::table('users')->find($data->user_input);
							$nama = $users->name;
						}
						@endphp
						<input class="form-control form-control-sm" type="text" id="nama" value="{{$nama}}" readonly="">
					</div>
				</div>
							<hr/>
				<div class="table-responsive">
					<table class="table table-sm" width="100%">
						<thead>
							<tr>
								<th>No</th>
								<th>Jenis Material</th>
								<th>Spesifikasi</th>
								<th>Digunakan Tanggal</th>
								<th>Jumlah</th>
								<th>Harga Satuan</th>
								<th>Subtotal</th>
							</tr>
						</thead>
						<tbody>
							@php
							$total = 0;
							@endphp
							@if(isset($data_detail) && !$data_detail->isEmpty())
							@foreach($data_detail as $key => $value)
							@php
							$material = \DB::table('material')->find($value->material_id);
							@endphp
							<tr>
								<td>{{ $key+1 }}</td>
								<td>{{ $material->kode_material }} - {{ $material->material }}</td>
								<td>{{ $material->spesifikasi }}</td>
								<td>{{ date_indo(date('Y-m-d',strtotime($value->tgl_penggunaan))) }}</td>
								<td>{{ $value->volume }}&nbsp{{ $material->satuan }}</td>
								<td> <div style="float:left">Rp. </div><div style="float:right">{{ number_format($value->harga_per_unit,2,',','.') }}</div></td>
								<td> <div style="float:left">Rp. </div><div style="float:right">{{ number_format($value->subtotal,2,',','.') }}</div></td>
							</tr>
							@php
							$total += $value->subtotal;
							@endphp
							@endforeach
							@endif
						</tbody>
						<tfoot>
							<tr>
								<td colspan="6" class="text-right"><b>TOTAL</b></td>
								<td><b><div style="float:left">Rp. </div><div style="float:right">{{ number_format($total,2,',','.') }}</div></b></td>
							</tr>
						</tfoot>
					</table>
				</div>
				<hr/>
				<br/><br/>
				<div class="row">
					<div class="col-12 text-right">
						@if($data->flag_verif_komersial == 'Y' && $data->flag_verif_pm == 'Y')
						<a href="{{ url('/po/'.$data->id.'/test-pdf') }}" class="btn btn-alt-info print_pdf" target="_blank">Print PDF</a>
						@endif
						<a href="{{ url('/po') }}" class="btn btn-alt-success">Kembali</a>
					</div>
				</div>
		</div>
	</div>
</div>
@endsection