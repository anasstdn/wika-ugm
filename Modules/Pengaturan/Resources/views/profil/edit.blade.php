@extends('layouts.app')

@section('content')
<style>
  .ajax-loader{
    position:fixed;
    top:0px;
    right:0px;
    width:100%;
    height:auto;
    background-color:#A9A9A9;
    background-repeat:no-repeat;
    background-position:center;
    z-index:10000000;
    opacity: 0.4;
    filter: alpha(opacity=40); /* For IE8 and earlier */
  }
</style>

<div class="ajax-loader text-center" style="display:none">
  <div class="progress">
    <div class="progress-bar progress-bar-striped active" aria-valuenow="100" aria-valuemin="1000"
    aria-valuemax="100" style="width: 100%;" id="loader" role="progressbar">
  </div>
</div>
<div id="" style="font-size:11pt;font-family: sans-serif;color: white">{{ __('alert.loading') }}</div>
</div>

<div class="bg-image bg-image-bottom" style="background-image: url('{{asset('codebase/')}}/src/assets/media/photos/photo12@2x.jpg');">
  <div class="bg-black-op-75 py-30">
    <div class="content content-top text-left">
      <div class="row push">
      <div class="col-md py-10 d-md-flex align-items-md-center text-center">
        <h1 class="text-white mb-0">
          <span class="font-w300">Manajemen Informasi Pengguna</span>
          &nbsp
          <a href="{{url('profil')}}" class="btn btn-rounded btn-hero btn-sm btn-alt-secondary mb-5">
            <i class="fa fa-arrow-left mr-5"></i> Kembali
          </a>
        </h1>
      </div>
    </div>
  </div>
</div>

<div class="content">
  <div class="block">
    <div class="block-header block-header-default">
      <h3 class="block-title">
        <i class="fa fa-user-circle mr-5 text-muted"></i> Informasi Pribadi
      </h3>
    </div>
    <div class="block-content">
      <form action="#" id="personal" method="POST" onsubmit="return false;">
        <div class="row items-push">
          <div class="col-lg-3">
            <p class="text-muted">
              Silahkan isi form dengan data lengkap. Isian wajib yang terdapat tanda *.
            </p>
          </div>
          <div class="col-lg-7 offset-lg-1">
            <div class="form-group row">
              <div class="col-6">
                <label for="profile-settings-username">No KTP</label>
                <input type="text" class="js-maxlength form-control form-control-sm" minlength="16" maxlength="16" id="nik" name="nik" placeholder="" data-always-show="true" data-placement="bottom-right" value="{{isset($profile)?isset($profile->nik)?$profile->nik:'':''}}">
                <span class="help-block"></span>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-6">
                <label for="profile-settings-name">Nama Depan</label>
                <input type="text" class="js-maxlength form-control form-control-sm" id="nama_depan" name="nama_depan" placeholder="" maxlength="100" data-always-show="true" data-placement="bottom-right" value="{{isset($profile)?isset($profile->nama_depan)?$profile->nama_depan:'':''}}">
                <span class="help-block"></span>
              </div>
              <div class="col-6">
                <label for="profile-settings-name">Nama Belakang</label>
                <input type="text" class="js-maxlength form-control form-control-sm" id="nama_belakang" name="nama_belakang" placeholder="" maxlength="100" data-always-show="true" data-placement="bottom-right" value="{{isset($profile)?isset($profile->nama_belakang)?$profile->nama_belakang:'':''}}">
                <span class="help-block"></span>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-6">
                <label for="profile-settings-email">Tempat Lahir</label>
                <input type="text" class="js-maxlength form-control form-control-sm" id="tempat_lahir" name="tempat_lahir" placeholder="" maxlength="100" data-always-show="true" data-placement="bottom-right" value="{{isset($profile)?isset($profile->tempat_lahir)?$profile->tempat_lahir:'':''}}">
              </div>
              <div class="col-6">
                <label for="profile-settings-email">Tanggal Lahir</label>
                <input type="text" class="datepicker form-control form-control-sm bg-white" data-date-format="d-m-Y" id="tgl_lahir" name="tgl_lahir" placeholder="" value="{{isset($profile)?isset($profile->tgl_lahir)?date('d-m-Y',strtotime($profile->tgl_lahir)):'':''}}">
                <span class="help-block"></span>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-6">
                <label for="profile-settings-email">Alamat KTP</label>
                <textarea class="js-maxlength form-control form-control-sm" name="alamat_ktp" id="alamat_ktp" placeholder="" maxlength="200" data-always-show="true" data-placement="bottom-right">{{isset($profile)?isset($profile->alamat_ktp)?$profile->alamat_ktp:'':''}}</textarea>
                <span class="help-block"></span>
              </div>
              <div class="col-6">
                <label for="profile-settings-email">Kota KTP</label>
                <input type="text" class="js-maxlength form-control form-control-sm" name="kota_ktp" id="kota_ktp" placeholder="" maxlength="50" data-always-show="true" data-placement="bottom-right" value="{{isset($profile)?isset($profile->kota_ktp)?$profile->kota_ktp:'':''}}">
                <span class="help-block"></span>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-6">
                <label for="profile-settings-email">Alamat Domisili</label>
                <textarea class="js-maxlength form-control form-control-sm" name="alamat_domisili" id="alamat_domisili" maxlength="200" data-always-show="true" data-placement="bottom-right" placeholder="">{{isset($profile)?isset($profile->alamat_domisili)?$profile->alamat_domisili:'':''}}</textarea>
                <span class="help-block"></span>
              </div>
              <div class="col-6">
                <label for="profile-settings-email">Kota Domisili</label>
                <input type="text" class="js-maxlength form-control form-control-sm" name="kota_domisili" id="kota_domisili" maxlength="50" data-always-show="true" data-placement="bottom-right" placeholder="" value="{{isset($profile)?isset($profile->kota_domisili)?$profile->kota_domisili:'':''}}">
                <span class="help-block"></span>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-6">
                <label for="profile-settings-email">Email</label>
                <input type="email" class="js-maxlength form-control form-control-sm" id="email" name="email" placeholder="" maxlength="100" data-always-show="true" data-placement="bottom-right" value="{{isset($profile)?isset($profile->email)?$profile->email:'':''}}">
              </div>
              <span class="help-block"></span>
            </div>
             <div class="form-group row">
              <div class="col-6">
                <label for="profile-settings-email">Nomor Telepon</label>
                <input type="text" class="js-maxlength form-control form-control-sm" id="no_telp" name="no_telp" placeholder="" maxlength="20" data-always-show="true" data-placement="bottom-right" value="{{isset($profile)?isset($profile->no_telp)?$profile->no_telp:'':''}}">
                <span class="help-block"></span>
              </div>
            </div>
            {{-- <div class="form-group row">
              <label class="col-12">Jenis Kelamin</label>
              <div class="col-6">
                <div class="custom-control custom-radio custom-control-inline mb-5">
                  <input class="custom-control-input" type="radio" name="jenis_kelamin" id="example-inline-radio1" value="L" checked>
                  <label class="custom-control-label" for="example-inline-radio1">Laki-laki</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline mb-5">
                  <input class="custom-control-input" type="radio" name="jenis_kelamin" id="example-inline-radio2" value="P">
                  <label class="custom-control-label" for="example-inline-radio2">Perempuan</label>
                </div>
              </div>
            </div> --}}
            <div class="form-group row">
              <div class="col-6">
                <label for="profile-settings-email">Jenis Kelamin</label>
                <select class="select2 form-control form-control-sm" name="jenis_kelamin" id="jenis_kelamin" style="width: 100%;" data-placeholder="">
                </select>
                <span class="help-block"></span>
              </div>
            </div>
              <div class="form-group row">
              <div class="col-6">
                <label for="profile-settings-email">Agama</label>
                <select class="select2 form-control form-control-sm" name="agama" id="agama" style="width: 100%;" data-placeholder="">
                </select>
                <span class="help-block"></span>
              </div>
            </div>
             <div class="form-group row">
              <div class="col-6">
                <label for="profile-settings-email">Status Perkawinan</label>
                <select class="select2 form-control form-control-sm" name="status_perkawinan" id="status_perkawinan" style="width: 100%;" data-placeholder="">
                </select>
                <span class="help-block"></span>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-10 col-xl-6">
                <div class="push">
                  @if(isset($profile) && $profile->photo!==null)
                  <img class="img-avatar" id="uploadPreview" src="{{asset('images/')}}/profile/{{$profile->photo}}" alt="">
                  @else
                  <img class="img-avatar" id="uploadPreview" src="{{asset('codebase/')}}/src/assets/media/avatars/avatar15.jpg" alt="">
                  @endif
                </div>
                <div class="custom-file">
                  <!-- Populating custom file input label with the selected filename (data-toggle="custom-file-input" is initialized in Helpers.coreBootstrapCustomFileInput()) -->
                  <input type="file" class="custom-file-input" id="profile-settings-avatar" onchange="PreviewImage();" name="profile-settings-avatar" data-toggle="custom-file-input">
                  <label class="custom-file-label" for="profile-settings-avatar">Pilh Foto</label>
                </div>
              </div>
            </div>
            <input type="hidden" name="foto_name" id="foto_name" value="{{isset($profile)?isset($profile->photo)?$profile->photo:'':''}}">
            <div class="form-group row">
              <div class="col-12">
                <button type="submit" id="simpan" class="btn btn-alt-primary">Update</button>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('js')
<script type="text/javascript">
  const load_dropdown_data = async() => {
    load_data_jenis_kelamin = await load_data_axios('{{ url('profil/load-data-jenis-kelamin') }}','POST').then(function(result) {
      insert_into_select_opt(result,'#jenis_kelamin','id','jenis_kelamin');
    });

    load_data_agama = await load_data_axios('{{ url('profil/load-data-agama') }}','POST').then(function(result) {
      insert_into_select_opt(result,'#agama','id','agama');
    });

    load_data_status_perkawinan = await load_data_axios('{{ url('profil/load-data-status-perkawinan') }}','POST').then(function(result) {
      insert_into_select_opt(result,'#status_perkawinan','id','status_perkawinan');
    });

  }

  $(function(){

    load_dropdown_data().then((e) => {
      console.log("load data dropdown berhasil");
      // clearConsole();
    }).catch(e => console.log(e));

    $(".select2").select2({
      dropdownParent: $("#personal"),
      width: '100%'
    });

    $('.datepicker').datepicker({
     format: "dd-mm-yyyy",
     locale: 'id',
     autoclose: true
   });

    $('#personal').find('#country').bind('change',function(){
      var country=$('#country').val();
      if(country!=='')
      {
        province(country);
      }
    })

    $.validator.addMethod("validDate", function(value, element) {
        return this.optional(element) || moment(value,"DD-MM-YYYY").isValid();
    }, "Format tanggal yang diperbolehkan, exp: DD-MM-YYYY");

    $('#personal').validate({
            ignore: [],
             button: {
            selector: "#simpan",
            disabled: "disabled"
        },
        onkeyup: function(element, event) {
        var t = this;
        setTimeout(function() {
            // this is the default function
            var excludedKeys = [
                16, 17, 18, 20, 35, 36, 37,
                38, 39, 40, 45, 144, 225
            ];
            if (event.which === 9 && t.elementValue(element) === "" || $.inArray(event.keyCode, excludedKeys) !== -1) {
                return;
            } else if (element.name in t.submitted || element.name in t.invalid) {
                t.element(element);
            } // end default
        }, 2000);  // 2-second delay
    }, 
          debug: false,
            errorClass: 'invalid-feedback',
            errorElement: 'div',
            errorPlacement: (error, e) => {
                jQuery(e).parents('.form-group > div').append(error);
                // $(e).parents('.form-group > div').find(".help-block").append(error);
            },
            highlight: e => {
                jQuery(e).closest('.form-group').removeClass('is-invalid').addClass('is-invalid');
            },
            success: e => {
                jQuery(e).closest('.form-group').removeClass('is-invalid');
                jQuery(e).remove();
            },
            rules: {
              'nik': {
                    required: true,
                    minlength: 16,
                    maxlength: 20,
                    digits: true,
                },
                'nama_depan': {
                    required: true,
                    minlength: 1,
                    maxlength: 100,
                },
                 'nama_belakang': {
                    required: true,
                    minlength: 1,
                    maxlength: 100,
                },
                 'tempat_lahir': {
                    required: true,
                    minlength: 1,
                    maxlength: 100,
                },
                'tgl_lahir': {
                    required: true,
                    validDate:true,
                },
                'alamat_ktp': {
                    required: true,
                    minlength: 1,
                    maxlength: 200,
                },
                'kota_ktp': {
                    required: true,
                },
                'alamat_domisili': {
                    required: true,
                    minlength: 1,
                    maxlength: 200,
                },
                'kota_domisili': {
                    required: true,
                },
                'email': {
                    required: true,
                    email:true,
                },
                'no_telp': {
                    required: true,
                    minlength: 1,
                    maxlength: 20,
                    // digits: true,
                },
                'jenis_kelamin': {
                    required: true,
                },
                'agama': {
                    required: true,
                },
                'status_perkawinan': {
                    required: true,
                },
            },
            messages: {
              'nik': {
                    required: 'Silahkan isi data',
                    minlength: 'Silahkan isi dengan minimal 16 karakter',
                    maxlength: 'Silahkan isi dengan maksimal 20 karakter',
                    digits : 'Format penulisan harus angka.'
                },
                'nama_depan': {
                    required: 'Silahkan isi data',
                    minlength: 'Silahkan isi dengan minimal 1 karakter',
                    maxlength: 'Silahkan isi dengan maksimal 100 karakter',
                },
                 'nama_belakang': {
                    required: 'Silahkan isi data',
                    minlength: 'Silahkan isi dengan minimal 1 karakter',
                    maxlength: 'Silahkan isi dengan maksimal 100 karakter',
                },
                 'tempat_lahir': {
                    required: 'Silahkan isi data',
                    minlength: 'Silahkan isi dengan minimal 1 karakter',
                    maxlength: 'Silahkan isi dengan maksimal 100 karakter',
                },
                 'tgl_lahir': {
                    required: 'Silahkan isi data',
                },
                 'alamat_ktp': {
                    required: 'Silahkan isi data',
                    minlength: 'Silahkan isi dengan minimal 1 karakter',
                    maxlength: 'Silahkan isi dengan maksimal 200 karakter',
                },
                'kota_ktp': {
                    required: 'Silahkan isi data',
                },
                'alamat_domisili': {
                    required: 'Silahkan isi data',
                    minlength: 'Silahkan isi dengan minimal 1 karakter',
                    maxlength: 'Silahkan isi dengan maksimal 200 karakter',
                },
                'kota_domisili': {
                    required: 'Silahkan isi data',
                },
                'email': {
                    required: 'Silahkan isi data',
                },
                'no_telp': {
                    required: 'Silahkan isi data',
                    minlength: 'Silahkan isi dengan minimal 1 karakter',
                    maxlength: 'Silahkan isi dengan maksimal 20 karakter',
                },
                'jenis_kelamin': {
                    required: 'Silahkan isi data',
                },
                'agama': {
                    required: 'Silahkan isi data',
                },
                'status_perkawinan': {
                    required: 'Silahkan isi data',
                },
            }
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

   $('#personal').submit('#simpan',function(e){
      e.preventDefault();
      if($(e.currentTarget).valid()==true)
      {
        post_data();
      }
    })

    // console.log(load_data_jenis_kelamin);
   

   // insert_into_select_opt(unit_kerja,'#unit_kerja','id','unit_kerja');

  })


        function PreviewImage() {
        var oFReader = new FileReader();
        oFReader.readAsDataURL($('#profile-settings-avatar').prop('files')[0]);


        oFReader.onload = function (oFREvent) {
          console.log(oFREvent.target.result);
            document.getElementById("uploadPreview").src = oFREvent.target.result;
        };
    };

    function post_data()
    {
      var formData = new FormData();
      formData.append('nik', $('#nik').val());
      formData.append('nama_depan', $('#nama_depan').val());
      formData.append('nama_belakang', $('#nama_belakang').val());
      formData.append('tempat_lahir', $('#tempat_lahir').val());
      formData.append('tgl_lahir', $('#tgl_lahir').val());
      formData.append('alamat_ktp', $('#alamat_ktp').val());
      formData.append('kota_ktp', $('#kota_ktp').val());
      formData.append('alamat_domisili', $('#alamat_domisili').val());
      formData.append('kota_domisili', $('#kota_domisili').val());
      formData.append('email', $('#email').val());
      formData.append('no_telp', $('#no_telp').val());
      formData.append('jenis_kelamin', $('#jenis_kelamin').val());
      formData.append('agama', $('#agama').val());
      formData.append('status_perkawinan', $('#status_perkawinan').val());
      formData.append('foto', $('#profile-settings-avatar').get(0).files[0]);
      formData.append('foto_name', $('#foto_name').val());
      formData.append('_token', '{{csrf_token()}}');

      Codebase.layout('header_loader_on');

      $('#simpan').html('Silahkan tunggu...');

      const options = {
        method : "POST",
        url : "{{ url('profil/send-data') }}",
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
      //   url: '{{url('profile/send-data')}}',
      //   type: 'POST',
      //   data: formData,
      //   headers: {
      //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      //   },
      //   xhr: function () {
      //     var xhr = new window.XMLHttpRequest();
      //     xhr.upload.addEventListener("progress",
      //       uploadProgressHandler,
      //       false
      //       );
      //     xhr.addEventListener("load", loadHandler, false);
      //     xhr.addEventListener("error", errorHandler, false);
      //     xhr.addEventListener("abort", abortHandler, false);

      //     return xhr;
      //   },
      //   cache: false,
      //   contentType: false,
      //   processData: false,
      //   success:function(data){

      //     if(data.status==true)
      //     {
      //       toastr_notif(data.msg,'sukses');
      //       $('#simpan').html('Finished');

      //       setTimeout(function(){
      //       // $('#formModal').modal('hide');
      //       // fetch_data(1);
      //       window.location.href = "{{ url('profile')}}";
      //     }, 2000);
      //     }
      //     else
      //     {
      //       toastr_notif(data.msg,'gagal');
      //     }

      //   },
      //   error:function (xhr, status, error)
      //   {
      //     toastr_notif(xhr.responseText,'gagal');
      //   },
      // }); 
    }

function insert_into_select_opt(data, selector, value_prop, value_text){
  if(data.length>0) {
    $(selector).empty();
    $(selector).append('<option value="">-Pilih-</option>');
    $.each(data,function(key,value){
      $(selector).append('<option value="'+value[value_prop]+'">'+value[value_text]+'</option>');
    });
  } else {
    $(selector).empty();
    $(selector).append('<option value=""selected>-Data is empty-</option>');
  }
};

function load_data_axios(url,method, params = {})
{
   const options = {
          method : method,
          url : url,
          headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
          data: params,
          dataType: "json",
          cache: false,
          contentType: false,
          processData: false,
        }

        return new Promise((resolve, reject) => {
         axios(options)
         .then((response) =>{
           resolve(response.data);
        })
         .catch((er) => {
          console.log(er);
        })
       })
}

function clearConsole() { 
    if(window.console || window.console.firebug) {
       console.clear();
    }
}
</script>
@endpush