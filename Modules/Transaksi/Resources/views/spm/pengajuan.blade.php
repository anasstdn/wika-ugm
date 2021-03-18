<div class="form-row">
	<div class="form-group col-6">
		<label for="wizard-progress-nama-belakang">No SPM</label>
		<input class="form-control pengajuan col-6" type="text" id="no_spm" name="no_spm" value="{{ isset($data->no_spm) && !empty($data->no_spm)?$data->no_spm:$no_spm }}">
	</div>
</div>

<div class="form-row">
	<div class="form-group col-6">
		<label for="wizard-progress-nama-depan">Tanggal SPM</label>
		<input class="form-control datepicker pengajuan" type="text" id="tgl_spm" name="tgl_spm" value="{{ isset($data->tgl_spm) && !empty($data->tgl_spm)?date('d-m-Y',strtotime($data->tgl_spm)):date('d-m-Y') }}">
	</div>
</div>

<div class="form-row">
	<div class="form-group col-6">
		<label for="wizard-progress-nama-belakang">Pemohon</label>
		<input class="form-control pengajuan" type="text" id="nama_pemohon" name="nama_pemohon" value="{{ isset($data->nama_pemohon) && !empty($data->nama_pemohon)?$data->nama_pemohon:'' }}">
	</div>
</div>

<div class="form-row">
	<div class="form-group col-6">
		<label for="wizard-progress-nama-belakang">Lokasi</label>
		<input class="form-control pengajuan" type="text" id="lokasi" name="lokasi" value="{{ isset($data->lokasi) && !empty($data->lokasi)?$data->lokasi:'' }}">
	</div>
</div>

<div class="form-row">
	<div class="form-group col-6">
		<label for="wizard-progress-nama-belakang">Keterangan</label>
		<textarea class="form-control pengajuan" id="keterangan_spm" name="keterangan_spm" rows="5">{{ isset($data->keterangan) && !empty($data->keterangan)?$data->keterangan:'' }}</textarea>
	</div>
</div>

@push('js')
<script>
	$(function(){
		
	})
</script>
@endpush
