@php
$count  = 0;
@endphp
<style>
  .has-error {
    color: red;
  }
</style>
{!! Form::open(array('route' => 'pengaturan.store','method'=>'POST','class' => 'js-validation','id'=>'form')) !!}
<div class="row items-push">
	<div class="col-lg-3">
		<p class="text-muted">
			Pengaturan Umum
		</p>
	</div>
	<div class="col-lg-7 offset-lg-1">
		<div class="form-group row">
			<div class="col-12">
				<label for="profile-settings-username">Nama Perusahaan (*)</label>
				<input type="text" class="form-control" id="nama_perusahaan" name="pengaturan[{{$count}}][nama_perusahaan]" placeholder="" value="{{ isset($nama_perusahaan)?$nama_perusahaan:'' }}">
				<span class="help-block"></span>
			</div>
		</div>
		<div class="form-group row">
			<div class="col-12">
				<label for="profile-settings-username">Alamat (*)</label>
				<textarea class="form-control" id="alamat" name="pengaturan[{{$count += 1}}][alamat]">{{ isset($alamat)?$alamat:'' }}</textarea>
				<span class="help-block"></span>
			</div>
		</div>
		<div class="form-group row">
			<div class="col-12">
				<label for="profile-settings-username">No Telepon (*)</label>
				<input type="text" class="form-control" id="telepon" name="pengaturan[{{$count+=1}}][telepon]" placeholder="" value="{{ isset($telepon)?$telepon:'' }}">
				<span class="help-block"></span>
			</div>
		</div>
		<div class="form-group row">
			<div class="col-12">
				<label for="profile-settings-username">Email (*)</label>
				<input type="text" class="form-control" id="email" name="pengaturan[{{$count+=1}}][email]" placeholder="" value="{{ isset($email)?$email:'' }}">
				<span class="help-block"></span>
			</div>
		</div>
		<div class="form-group row">
			<div class="col-12">
				<label for="profile-settings-username">Website</label>
				<input type="text" class="form-control" id="website" name="pengaturan[{{$count+=1}}][website]" placeholder="" value="{{ isset($website)?$website:'' }}">
				<span class="help-block"></span>
			</div>
		</div>
		<div class="form-group row">
			<div class="col-12">
				<label for="profile-settings-username">Telegram API Key</label>
				<input type="text" class="form-control" id="telegram_api" name="pengaturan[{{$count+=1}}][telegram_api]" placeholder="" value="{{ isset($telegram_api)?$telegram_api:'' }}">
				<span class="help-block"></span>
			</div>
		</div>
		<div class="form-group row">
			<div class="col-12 text-right">
				<button type="submit" id="simpan" class="btn btn-alt-primary">Simpan</button>
			</div>
		</div>
	</div>
</div>
{!! Form::close() !!}

@push('js')
<script type="text/javascript">
	$(function(){
		$('#form').submit('#simpan', e => {
			e.preventDefault();
			$('#form').find('input,textarea').parent().parent().removeClass('has-error');
			$('#form').find('.help-block').text('');
			save_to_db($('#form'));
		})
	})

	save_to_db = (element) => {

		var formData = element.serialize();

		Codebase.layout('header_loader_on');

		$.ajax({
			url: '{{route('pengaturan.store')}}',
			type: 'POST',
			data: formData,
			dataType:'JSON',
			success:function(data){
				console.log(data);
				if(data.status==true)
				{
					notification(data.msg,'sukses');
					Codebase.layout('header_loader_off');;
				}
				else
				{
					Codebase.layout('header_loader_off');
					notification('Silahkan periksa kembali data','gagal');
					for (var i = 0; i < data.inputerror.length; i++) 
					{
						$('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error');
						$('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]);
					}
				}
			},
			error:function (xhr, status, error)
			{
				Codebase.layout('header_loader_off');
				notification(xhr.responseText,'gagal');
			},
		}); 
	}
</script>
@endpush