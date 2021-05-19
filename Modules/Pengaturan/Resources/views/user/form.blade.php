<div class="modal-dialog" role="document">
    <div class="modal-content">
        <form method="post" id="form" action="#" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">{{ isset($data) && $data->exists() ? 'Edit' : 'Tambah' }} Pengguna</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <input type="hidden" id="mode" name="mode"
                    value="{{ isset($data) && $data->exists() ? 'edit' : 'add' }}">
                <input type="hidden" id="id" name="id" value="{{ isset($data) && $data->exists() ? $data->id : '' }}">
                <div class="block-content">
                    <div class="alert alert-dismissible" role="alert" style="display: none">
                        <span id="message" style="font-size: 9pt"></span>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="form-group row" style="margin-bottom: 1.429rem;">
                        <label class="col-form-label col-md-3">Nama</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control form-control-sm" id="name" name="name"
                                value="{{ isset($data) && !empty($data->name) ? $data->name : '' }}">
                            <span class="help-block"></span>
                        </div>
                    </div>
                    <div class="form-group row" style="margin-bottom: 1.429rem;">
                        <label class="col-form-label col-md-3">Username</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control form-control-sm" id="username" name="username"
                                value="{{ isset($data) && !empty($data->username) ? $data->username : '' }}">
                            <span class="help-block"></span>
                        </div>
                    </div>
                    <div class="form-group row" style="margin-bottom: 1.429rem;">
                        <label class="col-form-label col-md-3">Email</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control form-control-sm" id="email" name="email"
                                value="{{ isset($data) && !empty($data->email) ? $data->email : '' }}">
                            <span class="help-block"></span>
                        </div>
                    </div>
                    <div class="form-group row" style="margin-bottom: 1.429rem;">
                        <label class="col-form-label col-md-3">Password</label>
                        <div class="col-md-8">
                            <input type="password" class="form-control form-control-sm" id="password" name="password"
                                value="">
                            <span class="help-block"></span>
                        </div>
                    </div>
                    <div class="form-group row" style="margin-bottom: 1.429rem;">
                        <label class="col-form-label col-md-3">Konfirmasi Password</label>
                        <div class="col-md-8">
                            <input type="password" class="form-control form-control-sm" id="confirm-password"
                                name="confirm-password" value="">
                            <span class="help-block"></span>
                        </div>
                    </div>
                    <div class="form-group row" style="margin-bottom: 1.429rem;">
                        <label class="col-form-label col-md-3">Roles</label>
                        <div class="col-md-8">
                            {!! Form::select('roles[]', $roles, isset($userRole) ? $userRole : [], ['class' => 'form-control', 'multiple']) !!}
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
    var status = false;
    var email = false;
    var username = false;
    $(document).ready(function() {
        $(".select2").select2({
            dropdownParent: $("#form"),
            width: '100%'
        });

        // $.validator.addMethod("noSpace", function(value, element) { 
        //     return value.indexOf(" ") < 0 && value != ""; 
        // }, "Silahkan isi tanpa spasi");


        // $('#form').validate({
        //     ignore: [],
        //     button: {
        //         selector: "#simpan",
        //         disabled: "disabled"
        //     },
        //     debug: false,
        //     errorClass: 'invalid-feedback',
        //     errorElement: 'div',
        //     errorPlacement: (error, e) => {
        //         jQuery(e).parents('.form-group > div').append(error);
        //     },
        //     highlight: e => {
        //         jQuery(e).closest('.form-group').removeClass('is-invalid').addClass('is-invalid');
        //     },
        //     success: e => {
        //         jQuery(e).closest('.form-group').removeClass('is-invalid');
        //         jQuery(e).remove();
        //     },
        //     rules: {
        //         'name': {
        //             required: true,
        //             minlength: 1
        //         },
        //         'username': {
        //             required: true,
        //             noSpace: true,    
        //             minlength: 3, 
        //             remote: {
        //                 url: "{{ url('user/check-username') }}",
        //                 type: "post",
        //                 data: {
        //                     "_token": "{{ csrf_token() }}",
        //                     username: function()
        //                     {
        //                       return $('#form :input[name="username"]').val();
        //                   },
        //                   mode: function()
        //                   {
        //                       return $('#form :input[name="mode"]').val();
        //                   },
        //                   id: function()
        //                   {
        //                       return $('#form :input[name="id"]').val();
        //                   }
        //                 },
        //                 beforeSend: function () {
        //                     Codebase.layout('header_loader_on');
        //                     $('#loader').css('width','100%');
        //                 },
        //                 dataFilter: function (data) {
        //                     var json = JSON.parse(data);
        //                     Codebase.layout('header_loader_off');
        //                     if (json.msg == "true") {
        //                         notification('Username sudah digunakan','gagal');
        //                         return "\"" + "Username sudah digunakan" + "\"";
        //                     } else {
        //                         notification('Username tersedia','sukses');
        //                         return 'true';
        //                     }
        //                 }
        //             }
        //         }, 
        //        'email': {
        //             required: true,     
        //             email:true,
        //             minlength: 3,
        //             remote: {
        //                 url: "{{ url('user/check-email') }}",
        //                 type: "post",
        //                 data: {
        //                     "_token": "{{ csrf_token() }}",
        //                     email_username: function()
        //                   {
        //                       return $('#form :input[name="email"]').val();
        //                   },
        //                   mode: function()
        //                   {
        //                       return $('#form :input[name="mode"]').val();
        //                   },
        //                   id: function()
        //                   {
        //                       return $('#form :input[name="id"]').val();
        //                   }
        //                 },
        //                 beforeSend: function () {
        //                     Codebase.layout('header_loader_on');
        //                     $('#loader').css('width','100%');
        //                 },
        //                 dataFilter: function (data) {
        //                     var json = JSON.parse(data);
        //                     Codebase.layout('header_loader_off');
        //                     if (json.msg == "true") {
        //                         notification('Email sudah digunakan','gagal');
        //                         return "\"" + "Email sudah digunakan" + "\"";
        //                     } else {
        //                         notification('Email tersedia','sukses');
        //                         return 'true';
        //                     }
        //                 }
        //             }
        //         },
        //         'password': {
        //             required: true,
        //             minlength: 8,
        //         },
        //         'roles[]': {
        //             required: true,
        //         },
        //     },
        //     messages: {
        //         'name': {
        //             required: 'Silahkan isi form',
        //             minlength: 'Karakter minimal diisi 1'
        //         },
        //         'username': {
        //             required: 'Silahkan isi form',
        //             minlength: 'Karakter minimal diisi 3'
        //         },
        //         'email': {
        //             required: 'Silahkan isi form',
        //             remote: $.validator.format("{0} is already taken."),
        //             email:"Format yang diisi harus email",
        //             minlength: 'Karakter minimal diisi 3'
        //         },
        //         'password': {
        //             required: 'Silahkan isi form',
        //             minlength: 'Karakter minimal 8',
        //         },
        //         'roles[]': {
        //             required: 'Silahkan isi form',
        //         },
        //     }
        // });

        // $('input').on('focus focusout keyup', function () {
        //     $(this).valid();
        // });

        // $("select").on("select2:close", function (e) {  
        //     $(this).valid(); 
        // });

        // if($('#mode').val()=='edit')
        // {
        //     $('#password').rules('remove', 'required');
        // }


        $('#form').submit('#simpan', function(e) {
            e.preventDefault();
            // if($(e.currentTarget).valid()==true)
            // {
            post_data();
            // }
        })

    });



    function post_data() {
        $('#message').empty();

        $.LoadingOverlay("show", {
            background: "rgba(255, 255, 255, 0.8)",
        });

        var form = $('#form')[0];
        var formData = new FormData(form);

        // $('#simpan').html('Silahkan tunggu...');
        $('#simpan').attr('disabled', 'disabled');

        $.ajax({
            url: "{{ url('user/send-data') }}", // the endpoint
            type: "POST", // http method
            processData: false,
            contentType: false,
            data: formData,
            success: function(data) {
                $.LoadingOverlay("hide");
                $('#simpan').removeAttr('disabled');
                if (data.status == false) {
                    if (data.mode == 'null_validation') {
                        $('.alert').fadeIn();
                        $('.alert').addClass('alert-warning');
                        $('#message').append(
                            'Silahkan lengkapi kolom berikut ini :<br/>');
                        $.each(data.errors, function(key, value) {
                            $('#message').append('<b>-</b> ' + value + '<br/>');
                        });
                        $(".alert").fadeTo(3000, 500).fadeOut(500, function() {
                            $('.alert').fadeOut('slow');
                            $('.alert').removeClass('alert-warning');
                        });
                        return false
                    } else {
                        $('.alert').fadeIn();
                        $('.alert').addClass('alert-warning');
                        $('#message').append('<p>' + data.errors + '</p>');
                        $(".alert").fadeTo(3000, 500).fadeOut(500, function() {
                            $('.alert').fadeOut('slow');
                            $('.alert').removeClass('alert-warning');
                        });
                        return false
                    }
                } else {
                    $('.alert').fadeIn();
                    $('.alert').addClass('alert-success');
                    $('#message').append(data.msg);
                    $(".alert").fadeTo(3000, 500).fadeOut(500, function() {
                        $('.alert').fadeOut('slow');
                        $('.alert').removeClass('alert-success');
                        $('#table').bootstrapTable('refresh');
                        $('#formModal').modal('hide');
                        return false
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);
            }

        });
    }

</script>
