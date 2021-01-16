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
                        <label class="col-form-label col-md-3">Kode Supplier</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control form-control-sm" id="kode_supplier" name="kode_supplier" value="{{ isset($data) && !empty($data->kode_supplier) ?$data->kode_supplier:'' }}">
                            <span class="help-block"></span>
                        </div>
                    </div>
                    <div class="form-group row" style="margin-bottom: 1.429rem;">
                        <label class="col-form-label col-md-3">Nama Supplier</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control form-control-sm" id="nama_supplier" name="nama_supplier" value="{{ isset($data) && !empty($data->nama_supplier) ?$data->nama_supplier:'' }}">
                            <span class="help-block"></span>
                        </div>
                    </div>
                    <div class="form-group row" style="margin-bottom: 1.429rem;">
                        <label class="col-form-label col-md-3">Telepon</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control form-control-sm" id="telepon" name="telepon" value="{{ isset($data) && !empty($data->telepon) ?$data->telepon:'' }}">
                            <span class="help-block"></span>
                        </div>
                    </div>
                    <div class="form-group row" style="margin-bottom: 1.429rem;">
                        <label class="col-form-label col-md-3">Mobile</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control form-control-sm" id="mobile" name="mobile" value="{{ isset($data) && !empty($data->mobile) ?$data->mobile:'' }}">
                            <span class="help-block"></span>
                        </div>
                    </div>
                    <div class="form-group row" style="margin-bottom: 1.429rem;">
                        <label class="col-form-label col-md-3">Diskon Supplier</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control form-control-sm" id="diskon_supplier" name="diskon_supplier" value="{{ isset($data) && !empty($data->diskon_supplier) ?$data->diskon_supplier:'' }}">
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
        formData.append('kode_supplier', $('#kode_supplier').val());
        formData.append('nama_supplier', $('#nama_supplier').val());
        formData.append('telepon', $('#telepon').val());
        formData.append('mobile', $('#mobile').val());
        formData.append('diskon_supplier', $('#diskon_supplier').val());
        formData.append('flag_aktif', $('#flag_aktif').val());
        formData.append('_token', '{{csrf_token()}}');

        Codebase.layout('header_loader_on');

        $('#simpan').html('Silahkan tunggu...');

        const options = {
          method : "POST",
          url : "{{ url('supplier/send-data') }}",
          headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
          data: formData,
          dataType: "json",
          cache: false,
          contentType: false,
          processData: false,
      }

      axios(options)
      .then((response) =>{
        console.log(response);
        if(response.data.status==true)
        {
            notification(response.data.msg,'sukses');
            $('#simpan').html('Selesai');
            Codebase.layout('header_loader_off');

            setTimeout(function(){
                $('#formModal').modal('hide');
                $('#table').bootstrapTable('refresh')
            }, 2000);
        }
        else
        {
            Codebase.layout('header_loader_off');
            notification(response.data.msg,'gagal');
        }
    })
      .catch((er) => {
          Codebase.layout('header_loader_off');
          notification(er,'gagal');
      })

        // $.ajax({
        //     url: '{{url('permissions/send-data')}}',
        //     type: 'POST',
        //     data: formData,
        //     headers: {
        //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //     },
        //     xhr: function () {
        //         var xhr = new window.XMLHttpRequest();
        //         xhr.upload.addEventListener("progress",
        //             uploadProgressHandler,
        //             false
        //             );
        //         xhr.addEventListener("load", loadHandler, false);
        //         xhr.addEventListener("error", errorHandler, false);
        //         xhr.addEventListener("abort", abortHandler, false);

        //         return xhr;
        //     },
        //     cache: false,
        //     contentType: false,
        //     processData: false,
        //     success:function(data){

        //         if(data.status==true)
        //         {
        //             notification(data.msg,'sukses');
        //             $('#simpan').html('Selesai');
        //             Codebase.layout('header_loader_off');

        //             setTimeout(function(){
        //                 $('#formModal').modal('hide');
        //                  // table.ajax.reload( null, false );
        //                  $('#table').bootstrapTable('refresh')
        //              }, 2000);
        //         }
        //         else
        //         {
        //             Codebase.layout('header_loader_off');
        //             notification(data.msg,'gagal');
        //         }

        //     },
        //     error:function (xhr, status, error)
        //     {
        //         Codebase.layout('header_loader_off');
        //         notification(xhr.responseText,'gagal');
        //     },
        // }); 
    }
</script>