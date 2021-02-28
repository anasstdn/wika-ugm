@extends('layouts.app')

@section('content')
<div class="bg-primary-dark">
	<div class="content content-top">
		<div class="row push">
			<div class="col-md py-10 d-md-flex align-items-md-center text-center">
				<h1 class="text-white mb-0">
					<span class="font-w300">Purchase Order</span>
					<span class="font-w400 font-size-lg text-white-op d-none d-md-inline-block">Pengajuan PO</span>
				</h1>
			</div>
		</div>
	</div>
</div>


<div class="content">
	<div class="block block-themed">
		<div class="block-header bg-info">
			<h3 class="block-title"><i class="fa fa-user-circle mr-5 text-muted"></i> Pengajuan PO</h3>
			<div class="block-options">
				<button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
					<i class="si si-refresh"></i>
				</button>
				<button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
			</div>
		</div>
		<div class="block-content">
			<div class="row items-push">
				<div class="col-lg-12">
					{{-- @if(isset($data) && !empty($data))
					{!! Form::model($data, ['method' => 'put','route' => ['po.update', $data->id],'class' => 'js-wizard-validation-classic-form','id'=>'form','files'=>true]) !!}
					@else --}}
					{!! Form::open(array('route' => 'po.store','method'=>'POST','class' => 'js-wizard-validation-classic-form','id'=>'form','files'=>true)) !!}
					{{-- @endif --}}
					<div class="form-row">
						<div class="form-group col-3">
							<label for="wizard-progress-nama-depan">Nomor PO</label>
							<input class="form-control form-control-sm" type="text" id="no_po" name="no_po" value="">
							<input class="form-control form-control-sm" type="hidden" id="id" name="id" value="{{ $data->id }}">
						</div>
						<div class="form-group col-1">
						</div>
						<div class="form-group col-3">
							<label for="wizard-progress-nama-depan">Tanggal</label>
							<input class="form-control form-control-sm datepicker" type="text" id="tgl_pengajuan_po" name="tgl_pengajuan_po" value="{{ date('d-m-Y') }}">
						</div>
					</div>
					<div class="form-row">
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
						<div class="form-group col-1">
						</div>
						<div class="form-group col-3">
							<label for="wizard-progress-nama-depan">Vendor Supplier</label>
							<input class="form-control form-control-sm" type="text" id="nama" value="{{$data->supplier->nama_supplier}}" readonly="">
						</div>
					</div>
					<br>
					<center><span style="font-size:12pt;font-weight: bold">Tabel Pemesanan Material</span></center>
					<hr/>
					<div class="table-responsive">
						<table class="table table-sm" width="100%" id="tabel">
							<thead>
								<tr>
									<th style="text-align: center">No</th>
									<th>Kode Material</th>
									<th>Jenis Material</th>
									<th>Spesifikasi</th>
									<th>Satuan</th>
									<th>Qty</th>
									<th style="text-align: center">Merek</th>
									<th style="text-align: center">Harga Per Unit</th>
									<th style="text-align: center">Subtotal</th>
								</tr>
							</thead>
							<tbody>
								@if(isset($data_barang) && !$data_barang->isEmpty())
								@foreach($data_barang as $key => $value)
								@php
								$material = \DB::table('material')->find($value->material_id);
								@endphp
								<tr>
									<td>{{ $key+1 }}
										<input type="hidden" name="survei_id[]" value="{{ $value->id }}">
									</td>
									<td>{{ $material->kode_material }}</td>
									<td>{{ $material->material }}</td>
									<td>{{ $material->spesifikasi }}</td>
									<td>{{ $material->satuan }}</td>
									<td>{{ $value->volume }}
										<input type="hidden" name="qty[]" class="form-control qty hitung" id="qty_{{ $key }}" value="{{ $value->volume }}">
									</td>
									<td>
										<input class="form-control form-control-sm" type="text" name="merek[]" id="merek_{{ $key }}" value="{{ $value->merek }}" readonly="">
									</td>
									<td>
										<input class="form-control form-control-sm harga hitung number_dot" style="text-align: right" type="text" name="total[]" id="total_{{ $key }}" value="{{ number_format($value->harga_per_unit,0,',','.') }}" readonly="">
									</td>
									<td>
										<input class="form-control form-control-sm subtotal hitung number_dot" style="text-align: right" type="text" name="subtotal[]" id="subtotal_{{ $key }}" value="0" readonly="">
									</td>
								</tr>
								@endforeach
								@endif
							</tbody>
							<tfoot>
								<tr>
									<td colspan="8" style="text-align: right"><b>GRAND TOTAL</b></td>
									<td>
										<span style="font-weight: bold;text-align: right" id="total_all"></span>
										<input type="hidden" name="grand_total" id="grand_total">
									</td>
								</tr>
							</tfoot>
						</table>
					</div>
					<div class="form-group row" style="margin-top: 2em">
						<div class="col-12 text-right">
							<a href="{{ url('/po') }}" class="btn btn-alt-success">Kembali</a>
							<button type="submit" id="simpan" class="btn btn-alt-primary">Simpan</button>
						</div>
					</div>
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@push('js')
<script>
	var nominal =0;
	var nominal_sum = 0;
	$(function(){
		$('.datepicker').datepicker({
			format: "dd-mm-yyyy",
			locale: 'id',
			autoclose: true
		});

		$(".select").select2({
			dropdownParent: $("#form"),
			width: '100%'
		});

		sum_total(nominal_sum);

		$('.hitung').on('change keyup',function(){
			var nominal_sum = 0;
			sum_total(nominal_sum);
		})

		$('#form').submit('#simpan',function (e) {
			var err = 0;

			if($('#no_po').val() == '')
			{
				err += 1;
				notification('Silahkan pilih supplier terlebih dahulu','gagal');
			}

			if(err > 0)
			{
				e.preventDefault();
			}
			else
			{
				clicked(e);
			}

		});
	})

	sum_total = (nominal_sum) => {
		var total_all = 0;
		$('#tabel tbody tr').each( (tr_index,tr) =>{
			$(tr).children('td').find('input').each( (td_index, td) => {
				var qty = parseInt($('#qty_'+tr_index).val());
				var harga = currencyToNumber($('#total_'+tr_index).val());
				var subtotal = qty * harga;
				$('#subtotal_'+tr_index).val(addCommas(pecahan(subtotal)));
			});   
			total_all += currencyToNumber($('#subtotal_'+tr_index).val());
		});

		$('#grand_total').val(total_all);
		$('#total_all').html('Rp. '+addCommas(pecahan(total_all)));
	}

	$('.number_dot').on('change keyup',function(){
		nominal = currencyToNumber($(this).val());
		if(isNaN(nominal)){
			$(this).val(0);    
		} else {
			$(this).val(addCommas(nominal));
		}
        //alert(nominal);
    })

	function addCommas(nStr){
		nStr += '';
		x = nStr.split('.');
		x1 = x[0];
		x2 = x.length > 1 ? ',' + x[1] : '';
		var rgx = /(\d+)(\d{3})/;
		while (rgx.test(x1)) {
			x1 = x1.replace(rgx, '$1' + '.' + '$2');
		}
		return x1 + x2;
	}

	function currencyToNumber(a){
		if(a!=null||a!=''){
			var b=a.toString();
			var pecah_koma = b.split(',');
			pecah_koma[0]=pecah_koma[0].replace(/\.+/g, '');
			c=pecah_koma.join('.');
			return parseFloat(c);
		}else{
			return '';
		}
	}

	function numberToCurrency2(a){
		if(a!=null&&!isNaN(a)){
			var b=parseInt(a);
			var angka=b.toString();
			var c = '';
			var lengthchar = angka.length;
			var j = 0;
			for (var i = lengthchar; i > 0; i--) {
				j = j + 1;
				if (((j % 3) == 1) && (j != 1)) {
					c = angka.substr(i-1,1) + '.' + c;
				} else {
					c = angka.substr(i-1,1) + c;
				}
			}
			return c;
		}else{
			return '';
		}
	}



	function pecahan(uang)
	{
		pembulatan=Math.round(uang / 100) * 100;
		return pembulatan;
	}
</script>
@endpush