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
								<form action="#" id="form" method="post" enctype="multipart/form-data">
									{{ csrf_field() }}
									<div id="isi">
									</div>
								</form>
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
			Codebase.layout('header_loader_on');
			$('#accordion_q1').collapse('hide');
			$('#isi').empty();
			var formData = new FormData();
			formData.append('po_id', e.params.args.data.id);

			load_data('{{ url('bapb/load-data-po') }}','POST',formData).then(function(result){
				// console.log(result);
				Codebase.layout('header_loader_off');
				$('#isi').append(result);
				$('#accordion_q1').collapse('show');
				$('#table').DataTable({
					language: {
						lengthMenu : '{{ "Menampilkan _MENU_ data" }}',
						zeroRecords : '{{ "Data tidak ditemukan" }}' ,
						info : '{{ "_PAGE_ dari _PAGES_ halaman" }}',
						infoEmpty : '{{ "Data tidak ditemukan" }}',
						infoFiltered : '{{ "(Penyaringan dari _MAX_ data)" }}',
						loadingRecords : '{{ "Memuat data dari server" }}' ,
						processing :    '{{ "Memuat data data" }}',
						search :        '{{ "Pencarian:" }}',
						paginate : {
							first :     '{{ "<" }}' ,
							last :      '{{ ">" }}' ,
							next :      '{{ ">>" }}',
							previous :  '{{ "<<" }}'
						}
					},
					aoColumnDefs: [{
						bSortable: false,
						aTargets: [-1]
					}],
					iDisplayLength: 5,
					aLengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
				});

				initWizardSimple();
			})
		});

		$('#form').submit('#simpan',function(e){
			e.preventDefault();
			if($(this).valid())
			{
				if(confirm('Apakah anda yakin untuk melanjutkan ke proses selanjutnya?')) {

					save_data();
				}
			}
		});
	})

	function load_data(url,method, params = {})
	{
		return new Promise((resolve, reject) => {
			$.ajax({
				url : url,
				type: method,
				headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
				dataType: "HTML",
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

	save_data = () => {

		Codebase.layout('header_loader_on');

		// $('#simpan').html('<i class="fa fa-circle-o-notch fa-spin"><i>').prop('disabled',true);
		$.ajax({
			url: '{{url('bapb/simpan-data')}}',
			type: 'POST',
			data: $('#form').serialize(),
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			xhr: function () {
				var xhr = new window.XMLHttpRequest();
				xhr.upload.addEventListener("progress",
					uploadProgressHandler,
					false
					);
				xhr.addEventListener("load", loadHandler, false);
				xhr.addEventListener("error", errorHandler, false);
				xhr.addEventListener("abort", abortHandler, false);

				return xhr;
			},
			success:function(data){

				if(data.status==true)
				{
					notification(data.msg,'sukses');
					$('#simpan').html('<i class="fa fa-check mr-5"></i> Proses & Simpan').prop('disabled',false);
					// Codebase.layout('header_loader_off');
					// $('#simpan').fadeOut();
					setTimeout(function(){
						// if(data.print_button == true)
						// {
						// 	$('.print_pdf').fadeIn();
						// 	$('.label_verifikasi').removeClass('badge-danger').addClass('badge-success');
						// 	$('.label_verifikasi').html('Disetujui');
						// }
						// else
						// {
						// 	$('.label_verifikasi').html('Ditolak');
						// }
						$('#isi').empty();
						var formData = new FormData();
						formData.append('po_id', data.po_id);

						load_data('{{ url('bapb/load-data-po') }}','POST',formData).then(function(result){
							Codebase.layout('header_loader_off');
							$('#isi').append(result);
							$('#accordion_q1').collapse('show');
							$('#table').DataTable({
								language: {
									lengthMenu : '{{ "Menampilkan _MENU_ data" }}',
									zeroRecords : '{{ "Data tidak ditemukan" }}' ,
									info : '{{ "_PAGE_ dari _PAGES_ halaman" }}',
									infoEmpty : '{{ "Data tidak ditemukan" }}',
									infoFiltered : '{{ "(Penyaringan dari _MAX_ data)" }}',
									loadingRecords : '{{ "Memuat data dari server" }}' ,
									processing :    '{{ "Memuat data data" }}',
									search :        '{{ "Pencarian:" }}',
									paginate : {
										first :     '{{ "<" }}' ,
										last :      '{{ ">" }}' ,
										next :      '{{ ">>" }}',
										previous :  '{{ "<<" }}'
									}
								},
								aoColumnDefs: [{
									bSortable: false,
									aTargets: [-1]
								}],
								iDisplayLength: 5,
								aLengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
							});

							initWizardSimple();
						})
					}, 2000);
				}
				else
				{
					Codebase.layout('header_loader_off');
					notification(data.msg,'gagal');
					$('#simpan').html('<i class="fa fa-check mr-5"></i> Proses & Simpan').prop('disabled',false);
				}

			},
			error:function (xhr, status, error)
			{
				Codebase.layout('header_loader_off');
				notification(xhr.responseText,'gagal');
			},
		}); 
	}

	initWizardSimple = () => {

		$('.datepicker').datepicker({
			format: "dd-mm-yyyy",
			locale: 'id',
			autoclose: true
		});

		let formClassic     = $('#form');

		formClassic.on('keyup keypress', e => {
			let code = e.keyCode || e.which;

			if (code === 13) {
				e.preventDefault();
				return false;
			}
		});

		$.validator.addMethod("validDate", function(value, element) {
			return this.optional(element) || moment(value,"DD-MM-YYYY").isValid();
		}, "Format tanggal yang diperbolehkan, exp: DD-MM-YYYY");

		let validatorClassic = formClassic.validate({
			errorClass: 'invalid-feedback animated fadeInDown',
			errorElement: 'div',
			errorPlacement: (error, e) => {
				$(e).parents('.form-group').append(error);
			},
			highlight: e => {
				$(e).closest('.form-group').removeClass('is-invalid').addClass('is-invalid');
			},
			success: e => {
				$(e).closest('.form-group').removeClass('is-invalid');
				$(e).remove();
			},
			rules: {
				'no_bapb': {
					required: true,
				},
				'tgl_bapb': {
					required: true,
					validDate:true,
				},
				'no_surat_jalan': {
					required: true,
				},
				'tgl_surat_jalan': {
					required: true,
					validDate:true,
				},
				'jenis_kelamin': {
					required: true,
				},
				'no_polisi': {
					required: true,
				},
				'jenis_kendaraan': {
					required: true,
				},
            },
		});


		$('input').on('focus focusout keyup', function () {
			$(this).valid();
		});

		$("select").on("select2:close", function (e) {  
			$(this).valid(); 
		});

		$(".datepicker").on("change", function (e) {  
			$(this).valid(); 
		});
	}
</script>
@endpush