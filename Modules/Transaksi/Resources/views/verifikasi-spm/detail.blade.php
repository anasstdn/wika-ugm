@extends('layouts.app')

@section('content')
<div class="bg-primary-dark">
	<div class="content content-top">
		<div class="row push">
			<div class="col-md py-10 d-md-flex align-items-md-center text-center">
				<h1 class="text-white mb-0">
					<span class="font-w300">SPM</span>
					<span class="font-w400 font-size-lg text-white-op d-none d-md-inline-block">Detail SPM</span>
				</h1>
			</div>
		</div>
	</div>
</div>
<div class="bg-white">
	<div class="content">
		<div class="block block-themed">
			<div class="block-header bg-info">
				<h3 class="block-title">Detail Surat Permintaan Material</h3>
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
						<label for="wizard-progress-nama-depan">Nomor Surat Permintaan Material</label>
						<input class="form-control form-control-sm" type="text" id="nama" value="{{ isset($data->no_spm)?$data->no_spm:'' }}" readonly="">
					</div>
					<div class="form-group col-3">
						<label for="wizard-progress-nama-depan">Tanggal SPM</label>
						<input class="form-control form-control-sm" type="text" id="nama" value="{{ isset($data->tgl_spm)?date_indo(date('Y-m-d',strtotime($data->tgl_spm))):'' }}" readonly="">
					</div>
					<div class="form-group col-3">
						<label for="wizard-progress-nama-depan">Pemohon</label>
						<input class="form-control form-control-sm" type="text" id="nama" value="{{ isset($data->nama_pemohon)?$data->nama_pemohon:'' }}" readonly="">
					</div>
					<div class="form-group col-3">
						<label for="wizard-progress-nama-depan">Lokasi</label>
						<input class="form-control form-control-sm" type="text" id="nama" value="{{ isset($data->lokasi)?$data->lokasi:'' }}" readonly="">
					</div>
				</div>
				<div class="form-row">
					<div class="form-group col-3">
						<label for="wizard-progress-nama-depan">Mengetahui Site Manager</label>
						<br/>
						@if($data->flag_verif_site_manager == 'Y')
						<span class="badge badge-success">Disetujui</span>
						@elseif($data->flag_verif_site_manager == 'N')
						<span class="badge badge-danger">Ditolak</span>
						@else
						<span class="badge badge-danger">Menunggu Verifikasi</span>
						@endif
					</div>
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
								<th>Jumlah</th>
								<th>Satuan</th>
								<th>Digunakan Tanggal</th>
								<th>Keterangan</th>
							</tr>
						</thead>
						<tbody>
							@if(isset($data_detail) && !$data_detail->isEmpty())
							@foreach($data_detail as $key => $value)
							@php
							$material = \DB::table('material')->find($value->material_id);
							@endphp
							<tr>
								<td>{{ $key+1 }}</td>
								<td>{{ $material->kode_material }} - {{ $material->material }}</td>
								<td>{{ $material->spesifikasi }}</td>
								<td>{{ $value->volume }}</td>
								<td>{{ $material->satuan }}</td>
								<td>{{ date_indo(date('Y-m-d',strtotime($value->tgl_penggunaan))) }}</td>
								<td>{{ $value->keterangan }}</td>
							</tr>
							@endforeach
							@endif
						</tbody>
					</table>
				</div>
				<hr/>
				<div class="form-row">
					<div class="form-group col-6">
						<label for="wizard-progress-nama-depan">Keterangan</label>
						<textarea class="form-control" readonly="" rows="5">
							@if(!empty($data->keterangan))
							{{ $data->keterangan }}
							@endif
							@if(!empty($data->catatan_site_manager))
							Site Manager : &#13;&#10{{ $data->catatan_site_manager }}
							@endif
						</textarea>
					</div>
				</div>

				<br/><br/>
				<div class="row">
					<div class="col-12 text-right">
						<a href="{{ url('/verifikasi-spm') }}" class="btn btn-alt-success">Kembali</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection