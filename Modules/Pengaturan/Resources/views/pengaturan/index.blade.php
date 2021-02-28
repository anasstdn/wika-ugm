@extends('layouts.app')

@section('content')
<!-- Header -->
<div class="bg-primary-dark">
  <div class="content content-top">
    <div class="row push">
      <div class="col-md py-10 d-md-flex align-items-md-center text-center">
        <h1 class="text-white mb-0">
          <span class="font-w300">Masterdata</span>
          <span class="font-w400 font-size-lg text-white-op d-none d-md-inline-block">Pegawai</span>
        </h1>
      </div>
      <div class="col-md py-10 d-md-flex align-items-md-center justify-content-md-end text-center">
        @can('pegawai-create')
        <a class="btn btn-alt-primary" href="{{ route('pegawai.create') }}">
          <i class="fa fa-plus mr-5"></i> Tambah Baru
        </a>
        @endcan
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
    {{-- <h2 class="content-heading"></h2> --}}
    <!-- Dynamic Table Full -->
    <div class="block block-themed">
      <div class="block-header bg-info">
        <h3 class="block-title">Data Pegawai</h3>
        <div class="block-options">
          <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
            <i class="si si-refresh"></i>
          </button>
          <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
        </div>
      </div>
            {{-- <div class="block-header block-header-default">
                <h3 class="block-title">Pegawai</h3>
              </div> --}}
              <div class="block-content block-content-full">


                <div class="row">
                  <div class="col-lg-12">
                    <!-- Block Tabs Animated Fade -->
                    <div class="block">
                      <ul class="nav nav-tabs nav-tabs-list" data-toggle="tabs" role="tablist">
                        <li class="nav-item">
                          <a class="nav-link active" href="#pengaturan_umum">Pengaturan Umum</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="#pengaturan_pengguna">Pengaturan Pengguna</a>
                        </li>
                       {{--  <li class="nav-item ml-auto">
                          <a class="nav-link" href="#btabs-animated-fade-settings"><i class="si si-settings"></i></a>
                        </li> --}}
                      </ul>
                      <div class="block-content tab-content overflow-hidden">
                        <div class="tab-pane fade show active" id="pengaturan_umum" role="tabpanel">
                          {{-- <h4 class="font-w400">Home Content</h4>
                          <p>Content fades in..</p> --}}
                          @include('pengaturan::pengaturan.umum')
                        </div>
                        <div class="tab-pane fade" id="pengaturan_pengguna" role="tabpanel">
                          {{-- <h4 class="font-w400">Profile Content</h4>
                          <p>Content fades in..</p> --}}
                        </div>
                    {{--     <div class="tab-pane fade" id="btabs-animated-fade-settings" role="tabpanel">
                          <h4 class="font-w400">Settings Content</h4>
                          <p>Content fades in..</p>
                        </div> --}}
                      </div>
                    </div>
                    <!-- END Block Tabs Animated Fade -->
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- END Breadcrumb -->
        </div>
        <!-- END Page Content -->
        <div class="modal fade" id="formModal" aria-hidden="true" aria-labelledby="formModalLabel" role="dialog">
        </div>
        @endsection