<div class="modal-dialog" role="document">
    <div class="modal-content">
        <form method="post" id="form" action="#" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">{{isset($data) && $data->exists()? 'Edit': 'Tambah'}} Supplier</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <input type="hidden" id="mode" name="mode" value="{{isset($data) && $data->exists()?'edit':'add'}}">
                <input type="hidden" id="id" name="id" value="{{isset($data) && $data->exists()?$data->id:''}}">
                <div class="block-content">
                    <div class="form-group row" style="margin-bottom: 1.429rem;">
                        <label class="col-form-label col-md-3">Kode Material</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control form-control-sm" id="kode_material" name="kode_material" value="{{ isset($data) && !empty($data->kode_material) ?$data->kode_material:'' }}">
                            <span class="help-block"></span>
                        </div>
                    </div>
                    <div class="form-group row" style="margin-bottom: 1.429rem;">
                        <label class="col-form-label col-md-3">Nama Material</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control form-control-sm" id="material" name="material" value="{{ isset($data) && !empty($data->material) ?$data->material:'' }}">
                            <span class="help-block"></span>
                        </div>
                    </div>
                    <div class="form-group row" style="margin-bottom: 1.429rem;">
                        <label class="col-form-label col-md-3">Spesifikasi</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control form-control-sm" id="spesifikasi" name="spesifikasi" value="{{ isset($data) && !empty($data->spesifikasi) ?$data->spesifikasi:'' }}">
                            <span class="help-block"></span>
                        </div>
                    </div>
                    <div class="form-group row" style="margin-bottom: 1.429rem;">
                        <label class="col-form-label col-md-3">Satuan</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control form-control-sm" id="satuan" name="satuan" value="{{ isset($data) && !empty($data->satuan) ?$data->satuan:'' }}">
                            <span class="help-block"></span>
                        </div>
                    </div>
                    <div class="form-group row" style="margin-bottom: 1.429rem;">
                        <label class="col-form-label col-md-3">Flag Aktif</label>
                        <div class="col-md-8">
                            <select class="select2 form-control form-control-sm" name="flag_aktif" id="flag_aktif" style="width: 100%;" data-placeholder="">
                                <option value="Y" {{ isset($data) && !empty($data->flag_aktif) ?$data->flag_aktif == 'Y'?'selected':'':'' }}>Aktif</option>
                                <option value="N" {{ isset($data) && !empty($data->flag_aktif) ?$data->flag_aktif == 'N'?'selected':'':'' }}>Nonaktif</option>
                            </select>
                            <span class="help-block"></span>
                        </div>
                    </div>
                    <div class="form-group row" style="margin-bottom: 1.429rem;">
                        <label class="col-form-label col-md-3">Level</label>
                        <div class="col-md-8">
                            <select class="select2 form-control form-control-sm" name="level" id="level" style="width: 100%;" data-placeholder="">
                                <option value="1" {{ isset($data) && !empty($data->flag_aktif) ?$data->flag_aktif == '1'?'selected':'':'' }}>1</option>
                                <!-- <option value="2" {{ isset($data) && !empty($data->flag_aktif) ?$data->flag_aktif == '2'?'selected':'':'' }}>2</option>
                                <option value="3" {{ isset($data) && !empty($data->flag_aktif) ?$data->flag_aktif == '3'?'selected':'':'' }}>3</option>
                                <option value="4" {{ isset($data) && !empty($data->flag_aktif) ?$data->flag_aktif == '4'?'selected':'':'' }}>4</option>
                                <option value="5" {{ isset($data) && !empty($data->flag_aktif) ?$data->flag_aktif == '5'?'selected':'':'' }}>5</option> -->
                            </select>
                            <span class="help-block"></span>
                        </div>
                    </div>
                    <div class="form-group row" style="margin-bottom: 1.429rem;">
                        <label class="col-form-label col-md-3">Parent Status</label>
                        <div class="col-md-8">
                            <select class="select2 form-control form-control-sm" name="parent_status" id="parent_status" style="width: 100%;" data-placeholder="">
                                <option value="Y" {{ isset($data) && !empty($data->parent_status) ?$data->parent_status == 'Y'?'selected':'':'' }}>Ya</option>
                                <option value="N" {{ isset($data) && !empty($data->parent_status) ?$data->parent_status == 'N'?'selected':'':'' }}>Tidak</option>
                            </select>
                            <span class="help-block"></span>
                        </div>
                    </div>
                    <div class="form-group row hide" style="margin-bottom: 1.429rem;display: none">
                        <label class="col-form-label col-md-3">Parent Material</label>
                        <div class="col-md-8">
                            <select class="parent form-control form-control-sm" name="parent_id" id="parent_id" style="width: 100%;" data-placeholder="">

                            </select>
                            <span class="help-block"></span>
                        </div>
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

    load_parent = () =>{
        $('#level').on('change rightnow', function(){
            if($(this).val() !== '1')
            {
                $('.hide').fadeIn();
                parent();
            }
            else
            {
                $('.hide').fadeOut();
            }
        }).triggerHandler("rightnow");

        return new Promise((resolve, reject) => resolve(true));
    }

    function parent(params={})
    {
        return new Promise((resolve,reject) =>{
            $(".parent").select2({
                dropdownParent: $("#form"),
                width: '100%',
                minimumInputLength : 2,
                ajax : {
                    url : '{{url('material/material-search')}}',
                    dataType : 'JSON'
                }
            });
        })
    }

    $(document).ready(function(){

        $.validator.addMethod("noSpace", function(value, element) { 
            return value.indexOf(" ") < 0 && value != ""; 
        }, "Silahkan isi tanpa spasi");


        $(".select2").select2({
                dropdownParent: $("#form"),
                width: '100%',
            });

        load_parent();


        $('#form').validate({
            ignore: [],
            button: {
                selector: "#simpan",
                disabled: "disabled"
            },
            debug: false,
            errorClass: 'invalid-feedback',
            errorElement: 'div',
            errorPlacement: (error, e) => {
                jQuery(e).parents('.form-group > div').append(error);
            },
            highlight: e => {
                jQuery(e).closest('.form-group').removeClass('is-invalid').addClass('is-invalid');
            },
            success: e => {
                jQuery(e).closest('.form-group').removeClass('is-invalid');
                jQuery(e).remove();
            },
            rules: {
                'kode_supplier': {
                    required: true,
                    minlength: 1
                },
                'nama_supplier': {
                    required: true,
                    minlength: 1
                },
                'flag_aktif': {
                    required: true,
                },
                
            },
            messages: {
                'kode_supplier': {
                    required: 'Silahkan isi form',
                    minlength: 'Karakter minimal diisi 1'
                },
                'nama_supplier': {
                    required: 'Silahkan isi form',
                    minlength: 'Karakter minimal diisi 1'
                },
                'flag_aktif': {
                    required: 'Silahkan isi form',
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
            if($(e.currentTarget).valid()==true)
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
        formData.append('kode_material', $('#kode_material').val());
        formData.append('material', $('#material').val());
        formData.append('spesifikasi', $('#spesifikasi').val());
        formData.append('satuan', $('#satuan').val());
        formData.append('flag_aktif', $('#flag_aktif').val());
        formData.append('level', $('#level').val());
        formData.append('parent_status', $('#parent_status').val());
        formData.append('parent_id', $('#parent_id').val());
        formData.append('_token', '{{csrf_token()}}');

        Codebase.layout('header_loader_on');

        $('#simpan').html('Silahkan tunggu...');

      //   const options = {
      //     method : "POST",
      //     url : "{{ url('material/send-data') }}",
      //     headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      //     data: formData,
      //     dataType: "json",
      //     cache: false,
      //     contentType: false,
      //     processData: false,
      // }

    //   axios(options)
    //   .then((response) =>{
    //     console.log(response);
    //     if(response.data.status==true)
    //     {
    //         notification(response.data.msg,'sukses');
    //         $('#simpan').html('Selesai');
    //         Codebase.layout('header_loader_off');

    //         setTimeout(function(){
    //             $('#formModal').modal('hide');
    //             $('#table').bootstrapTable('refresh')
    //         }, 2000);
    //     }
    //     else
    //     {
    //         Codebase.layout('header_loader_off');
    //         notification(response.data.msg,'gagal');
    //     }
    // })
    //   .catch((er) => {
    //       Codebase.layout('header_loader_off');
    //       notification(er,'gagal');
    //   })

        $.ajax({
            url: '{{url('material/send-data')}}',
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
                    $('#simpan').html('Selesai');
                    Codebase.layout('header_loader_off');

                    setTimeout(function(){
                        $('#formModal').modal('hide');
                         // table.ajax.reload( null, false );
                         $('#table').bootstrapTable('refresh')
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