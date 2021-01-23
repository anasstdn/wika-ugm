@extends('layouts.app')

@section('content')
<div class="bg-primary-dark">
	<div class="content content-top">
		<div class="row push">
			<div class="col-md py-10 d-md-flex align-items-md-center text-center">
				<h1 class="text-white mb-0">
					<span class="font-w300">Masterdata</span>
					<span class="font-w400 font-size-lg text-white-op d-none d-md-inline-block">Stok Gudang</span>
				</h1>
			</div>
		</div>
	</div>
</div>


<div class="content">
	<div class="block block-themed">
		<div class="block-header bg-info">
			<h3 class="block-title"><i class="fa fa-user-circle mr-5 text-muted"></i> Stok Gudang</h3>
			<div class="block-options">
				<button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
					<i class="si si-refresh"></i>
				</button>
				<button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
			</div>
		</div>
		<div class="block-content">
			{!! Form::open(array('route' => 'stok.store','method'=>'POST','class' => 'js-wizard-validation-classic-form','id'=>'form','files'=>true)) !!}
			<div class="row items-push">
				<div class="col-lg-3">
					<p class="text-muted">
						Silahkan isi data stok barang dengan lengkap.
					</p>
				</div>
				<div class="col-lg-8 offset-lg-1">
					<div class="table-responsive">
					<table class="table table-sm" width="100%">
						<thead>
							<tr>
								<th>No</th>
								<th>Kode Material</th>
								<th>Material</th>
								<th>Satuan</th>
								<th>Qty</th>
							</tr>
						</thead>
						<tbody>
							@if(isset($data_material) && !$data_material->isEmpty())
							@foreach($data_material as $key => $val)
							@php
							$qty_stok = \DB::table('stok')->where('material_id', $val->id)->first();
							@endphp
							<tr>
								<td>{{$key + 1}}
									<input type="hidden" name="material_id[]" value="{{$val->id}}">
								</td>
								<td>{{$val->kode_material}}</td>
								<td>{{$val->material}}</td>
								<td>{{$val->satuan}}</td>
								<td><input type="number" min="0" class="form-control" style="text-align: right" name="qty[]" value="{{isset($qty_stok->qty) && !empty($qty_stok->qty)?$qty_stok->qty:0}}"></td>
							</tr>
							@endforeach
							@endif
						</tbody>
					</table>
				</div>
				<div class="form-group row" style="margin-top: 2em">
					<div class="col-12 text-right">
						<a href="{{ url('/stok') }}" class="btn btn-alt-success">Kembali</a>
						<button type="submit" id="simpan" class="btn btn-alt-primary">Simpan</button>
					</div>
				</div>
			</div>
		</div>
		{!! Form::close() !!}
	</div>
</div>
@endsection

@push('js')
<script>
$(function(){
	$('#form').submit('#simpan',function(e){
		clicked(e);
	})
})
</script>
@endpush