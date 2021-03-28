@extends('layouts.app')

@section('content')
<div class="bg-primary-dark">
	<div class="content content-top">
		<div class="row push">
			<div class="col-md py-10 d-md-flex align-items-md-center text-center">
				<h1 class="text-white mb-0">
					<span class="font-w300">Purchase Order</span>
					<span class="font-w400 font-size-lg text-white-op d-none d-md-inline-block">Pengajuan Survei Barang</span>
				</h1>
			</div>
		</div>
	</div>
</div>


<div class="content">
	<div class="block block-themed">
		<div class="block-header bg-info">
			<h3 class="block-title"><i class="fa fa-user-circle mr-5 text-muted"></i> Pengajuan Survei Barang</h3>
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
					@if(isset($data) && !empty($data))
					{!! Form::model($data, ['method' => 'put','route' => ['survei.update', $data->id],'class' => 'js-wizard-validation-classic-form','id'=>'form','files'=>true]) !!}
					@else
					{!! Form::open(array('route' => 'survei.store','method'=>'POST','class' => 'js-wizard-validation-classic-form','id'=>'form','files'=>true)) !!}
					@endif
					<br/>
					<span style="font-size: 11pt;color:red">* Silahkan anda memilih barang yang akan dipesan dengan cara mencentang kolom check</span>
					<div id="table_data">
						@include('transaksi::survei.form-data')
					</div>
					<div class="form-group row" style="margin-top: 2em">
						<div class="col-12 text-right">
							<a href="{{ url('/survei') }}" class="btn btn-alt-success">Kembali</a>
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
	var page = 1;
	let method = 'POST';
	var params;
	$(function(){
		load_table(page,method).then((e) => {
			$('#table_data').html(e);
		}).catch(e => console.log(e));

		$('#form').submit('#simpan',function (e) {
			var err = 0;
			var atLeastOneIsChecked = false;
			$('input:checkbox[name="check[]"]').each(function () {
				if ($(this).is(':checked')) {
					atLeastOneIsChecked = true;
					return false;
				}
			});
			if(atLeastOneIsChecked == false)
			{
				err += 1;
				notification('Silahkan anda mencentang minimal 1 data!','gagal');
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

	const load_table = (page,method,params = {}) =>{
		return new Promise((resolve, reject) => {
			Codebase.layout('header_loader_on');
			$.ajax({
				url : '{{ url('survei/load-table') }}?page='+page,
				type: method,
				headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
				dataType: "html",
				data:params,
				success: function(data){
					Codebase.layout('header_loader_off');
					resolve(data);
				},
				error: function (jqXHR, textStatus, errorThrown) {
					reject(errorThrown);
				}
			})
		});
	}

	$(document).on('click', '.pagination a', function(event){
		event.preventDefault(); 
		var page = $(this).attr('href').split('page=')[1];
		load_table(page,method).then((e) => {
			$('#table_data').html(e);
		}).catch(e => console.log(e));
	});
</script>
@endpush