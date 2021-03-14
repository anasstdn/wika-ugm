@extends('layouts.app')

@section('content')
<div class="bg-primary-dark">
	<div class="content content-top">
		<div class="row push">
			<div class="col-md py-10 d-md-flex align-items-md-center text-center">
				<h1 class="text-white mb-0">
					<span class="font-w300">BAPB</span>
					<span class="font-w400 font-size-lg text-white-op d-none d-md-inline-block">Transaksi BAPB</span>
				</h1>
			</div>
			<div class="col-md py-10 d-md-flex align-items-md-center justify-content-md-end text-center">
        {{-- @can('po-create')
        <a class="btn btn-alt-primary" href="{{ route('po.create') }}">
          <i class="fa fa-plus mr-5"></i> Tambah PO Baru
        </a>
        @endcan --}}
    </div>
</div>
</div>
</div>
<div class="bg-white">
	<div class="content">
		<div class="block block-themed">
			<div class="block-header bg-primary">
				<h3 class="block-title">Transaksi BAPB</h3>
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
						<label for="wizard-progress-nama-depan">Masukkan Kode PO</label>
						<select class="select form-control" name="no_po" id="no_po" style="width: 100%;" data-placeholder="">
							<option value="">-Silahkan Pilih-</option>
						</select>
					</div>
					<div class="form-group col-2">
						<br/>
						<a href="#" class="btn btn-alt-primary" id="cari">Cari</a>
						<a href="#" class="btn btn-alt-success" id="reset">Reset</a>
					</div>
				</div>
				<div id="accordion" role="tablist" aria-multiselectable="true">
					<div class="block block-bordered block-rounded mb-2">
						<div class="block-header" role="tab" id="accordion_h1">
							<a class="font-w600" data-toggle="collapse" data-parent="#accordion" href="#accordion_q1" aria-expanded="true" aria-controls="accordion_q1">Hasil Pencarian</a>
						</div>
						<div id="accordion_q1" class="collapse" role="tabpanel" aria-labelledby="accordion_h1" data-parent="#accordion">
							<div class="block-content">
								<div id="isi">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@push('js')
<script>
	$(function(){
		$(".select").select2({
			width: '100%',
			minimumInputLength: 1,
			ajax: {
				url: '{{ url('bapb/po-search') }}',
				dataType: 'json',
			},
		}).on('select2:selecting', function(e){
			$('#accordion_q1').collapse('hide');
			$('#isi').empty();
			var formData = new FormData();
			formData.append('po_id', e.params.args.data.id);

			load_data('{{ url('bapb/load-data-po') }}','POST',formData).then(function(result){
				console.log(result);
				row = `
				<div class="form-row">
				<div class="form-group col-4">
				<label for="wizard-progress-nama-depan">Nomor Purchase Order</label>
				<input type="text" class="form-control" name="no_po" id="no_po" value="`+result.no_po+`" readonly>
				</div>
				<div class="form-group col-4">
				<label for="wizard-progress-nama-depan">Tanggal Pengajuan PO</label>
				<input type="text" class="form-control" name="tgl_pengajuan_po" id="tgl_pengajuan_po" value="`+result.tgl_pengajuan_po+`" readonly>
				</div>
				<div class="form-group col-4">
				<label for="wizard-progress-nama-depan">User Pembuat PO</label>
				<input type="text" class="form-control" name="user_input" id="user_input" value="`+result.user_input+`" readonly>
				</div>
				</div>
				<div class="form-row">
				<div class="form-group col-2">
				<label for="wizard-progress-nama-depan">Kode Supplier</label>
				<input type="text" class="form-control" name="kode_supplier" id="kode_supplier" value="`+result.kode_supplier+`" readonly>
				</div>
				<div class="form-group col-3">
				<label for="wizard-progress-nama-depan">Nama Supplier</label>
				<input type="text" class="form-control" name="nama_supplier" id="nama_supplier" value="`+result.nama_supplier+`" readonly>
				</div>
				<div class="form-group col-3">
				<label for="wizard-progress-nama-depan">Total Harga</label>
				<input type="text" class="form-control" name="total_harga" id="total_harga" value="`+result.total_harga+`" readonly>
				</div>
				</div>
				<hr/>
				`;
				$('#isi').append(row);
				$('#accordion_q1').collapse('show');
				// $(element).parent().parent().parent().find('.satuan').html(result.satuan);
				// $(element).parent().parent().parent().find('.spesifikasi').html(result.spesifikasi);
			})
		});
	})

	function load_data(url,method, params = {})
	{
		return new Promise((resolve, reject) => {
			$.ajax({
				url : url,
				type: method,
				headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
				dataType: "JSON",
				data:params,
				cache: false,
				contentType: false,
				processData: false,
				success: function(data){
					resolve(data);
				},
				error: function (jqXHR, textStatus, errorThrown) {
					reject(errorThrown);
				}
			})
		})
	}
</script>
@endpush