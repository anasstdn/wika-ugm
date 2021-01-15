@extends('layouts.app')

@section('content')
<!-- Header -->
<div class="bg-primary-dark">
  <div class="content content-top">
    <div class="row push">
      <div class="col-md py-10 d-md-flex align-items-md-center text-center">
        <h1 class="text-white mb-0">
          <span class="font-w300">Access Control List</span>
          <span class="font-w400 font-size-lg text-white-op d-none d-md-inline-block">Manajemen Roles</span>
        </h1>
      </div>
      <div class="col-md py-10 d-md-flex align-items-md-center justify-content-md-end text-center">
      </div>
    </div>
  </div>
</div>
<!-- END Header -->

<!-- Page Content -->
<!-- Page Content -->
<div class="bg-white">
  <!-- Breadcrumb -->
  <div class="content">
    <nav class="breadcrumb mb-0">
      <a class="breadcrumb-item" href="javascript:void(0)">Access Control List</a>
      <span class="breadcrumb-item active">Manajemen Roles</span>
    </nav>
    <h2 class="content-heading"></h2>
    <!-- Dynamic Table Full -->
    <div class="block">
      <div class="block-header block-header-default">
        <h3 class="block-title">Manajemen Roles</h3>
      </div>
      <div class="block-content block-content-full">
        @if(isset($role) && !empty($role))
        {!! Form::model($role, ['method' => 'PUT','route' => ['roles.update', $role->id],'class' => 'js-validation','id'=>'form']) !!}
        @else
        {!! Form::open(array('route' => 'roles.store','method'=>'POST','class' => 'js-validation','id'=>'form')) !!}
        @endif
        <div class="row">
          <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
              <strong>Nama Role:</strong>
              {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control wajib')) !!}
            </div>
          </div>
          <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
              <strong>Permission:</strong>
              <br/>
              @foreach($permission as $value)
              <label>{{ Form::checkbox('permission[]', $value->id, isset($rolePermissions) && in_array($value->id, $rolePermissions) ? true : false, array('class' => 'name')) }}
                {{ $value->name }}</label>
                <br/>
                @endforeach
              </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 text-right">
              <a class="btn btn-outline-primary" href="{{ url('/roles') }}"> Kembali</a>
              <button type="submit" id="submit" class="btn btn-primary">Simpan</button>
            </div>
          </div>
          {!! Form::close() !!}
        </div>
      </div>
      <!-- END Breadcrumb -->
    </div>
    <!-- END Page Content -->
    <div class="modal fade" id="formModal" aria-hidden="true" aria-labelledby="formModalLabel" role="dialog">
    </div>
    @endsection
    @push('js')
    <script>
      $(function(){
        $('.js-validation').validate({
          ignore: [],
          button: {
            selector: "#submit",
            disabled: "disabled"
          },
          debug: false,
            // errorClass: 'invalid-feedback',
            rules: {
              'name': {
                required: true,
                minlength: 1
              },
            }, 
            messages: {
              'name': {
                required: 'Silahkan isi form',
                minlength: 'Karakter minimal diisi 1'
              },
            }
          });


        $('#form').submit('#submit',function (e) {
          var err = 0;
          var atLeastOneIsChecked = false;

          $('.wajib').each(function(index,element){
            if($(element).val()=='')
            {
              err+=1;
              $(element).next().css({'color':'red','font-size':'10pt','font-family':'sans-serif'});
              $(element).next().html('Silahkan isi form');
            }
          })


          $('input:checkbox[name="permission[]"]').each(function () {
            if ($(this).is(':checked')) {
              atLeastOneIsChecked = true;
              return false;
            }
          });
          if(atLeastOneIsChecked == false)
          {
            err += 1;
            notification('Silahkan isi permission minimal 1','gagal');
          }

          if(err > 0)
          {
            e.preventDefault();
          }

        });
      })
    </script>
    @endpush