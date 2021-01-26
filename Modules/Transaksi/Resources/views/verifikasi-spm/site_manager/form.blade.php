<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <form method="post" id="form" action="#" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Pembatalan Verifikasi</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <input type="hidden" id="mode" name="mode" value="{{isset($data) && $data->exists()?'edit':'add'}}">
                <input type="hidden" id="id" name="id" value="{{isset($data) && $data->exists()?$data->id:''}}">
                <div class="block-content">
                    <div class="form-row">
                        <div class="form-group col-4">
                            <label for="wizard-progress-nama-depan">Nomor Surat Permintaan Material</label>
                            <input class="form-control form-control-sm" type="text" value="{{ isset($data->no_spm)?$data->no_spm:'' }}" readonly="">
                        </div>
                        <div class="form-group col-4">
                            <label for="wizard-progress-nama-depan">Tanggal SPM</label>
                            <input class="form-control form-control-sm" type="text" value="{{ isset($data->tgl_spm)?date_indo(date('Y-m-d',strtotime($data->tgl_spm))):'' }}" readonly="">
                        </div>
                        <div class="form-group col-4">
                            <label for="wizard-progress-nama-depan">Pemohon</label>
                            <input class="form-control form-control-sm" type="text" value="{{ isset($data->nama_pemohon)?$data->nama_pemohon:'' }}" readonly="">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-4">
                            <label for="wizard-progress-nama-depan">Lokasi</label>
                            <input class="form-control form-control-sm" type="text" value="{{ isset($data->lokasi)?$data->lokasi:'' }}" readonly="">
                        </div>
                        <div class="form-group col-4">
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
                            <input class="form-control form-control-sm" type="text" value="{{$nama}}" readonly="">
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
                            <label for="wizard-progress-nama-depan">Alasan Pembatalan</label>
                            <input class="form-control pengajuan" type="text" id="alasan_pembatalan" name="alasan_pembatalan" value="{{ isset($data->alasan_pembatalan) && !empty($data->alasan_pembatalan)?$data->alasan_pembatalan:'' }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" id="simpan" class="btn btn-alt-success">
                        <i class="fa fa-check"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script type="text/javascript">
        var status=false;
        var email=false;
        var username=false;
        $(document).ready(function(){
            $(".select2").select2({
                dropdownParent: $("#form"),
                width: '100%'
            });

            $.validator.addMethod("noSpace", function(value, element) { 
                return value.indexOf(" ") < 0 && value != ""; 
            }, "Silahkan isi tanpa spasi");


            $('#form').validate({
                ignore: [],
                button: {
                    selector: "#simpan",
                    disabled: "disabled"
                },
                debug: false,
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
                    'alasan_pembatalan': {
                        required: true,
                        minlength: 1
                    },
                },
                messages: {
                    'alasan_pembatalan': {
                        required: 'Silahkan isi form',
                        minlength: 'Karakter minimal diisi 1'
                    },
                }
            });

            $('input').on('focus focusout keyup', function () {
                $(this).valid();
            });

            $("select").on("select2:close", function (e) {  
                $(this).valid(); 
            });

            if($('#mode').val()=='edit')
            {
                $('#password').rules('remove', 'required');
            }


            $('#form').submit('#simpan',function(e){
                e.preventDefault();
                if($(this).valid() ==true)
                {
                    post_data();
                }
            })

        });



        function post_data()
        {
            var formData = new FormData();
            formData.append('id', $('#id').val());
            formData.append('mode', $('#mode').val());
            formData.append('alasan_pembatalan', $('#alasan_pembatalan').val());
            formData.append('_token', '{{csrf_token()}}');

            Codebase.layout('header_loader_on');

            $('#simpan').html('<i class="fa fa-circle-o-notch fa-spin"><i>').prop('disabled',true);
            $.ajax({
                url: '{{url('verifikasi-spm/send-data')}}',
                type: 'POST',
                data: formData,
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
                cache: false,
                contentType: false,
                processData: false,
                success:function(data){

                    if(data.status==true)
                    {
                        notification(data.msg,'sukses');
                        $('#simpan').html('Simpan').prop('disabled',false);
                        Codebase.layout('header_loader_off');

                        setTimeout(function(){
                            $('#formModal').modal('hide');
                         // table.ajax.reload( null, false );
                         $('#table').bootstrapTable('refresh');
                         $('#table-diterima').bootstrapTable('refresh');
                         $('#table-ditolak').bootstrapTable('refresh');
                     }, 2000);
                    }
                    else
                    {
                        Codebase.layout('header_loader_off');
                        notification(data.msg,'gagal');
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