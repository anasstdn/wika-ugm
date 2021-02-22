@extends('layouts.app')

@section('content')
<!-- Header -->
<div class="bg-primary-dark">
  <div class="content content-top">
    <div class="row push">
      <div class="col-md py-10 d-md-flex align-items-md-center text-center">
        <h1 class="text-white mb-0">
          <span class="font-w300">Purchase Order</span>
          <span class="font-w400 font-size-lg text-white-op d-none d-md-inline-block">Pengajuan PO</span>
        </h1>
      </div>
      <div class="col-md py-10 d-md-flex align-items-md-center justify-content-md-end text-center">
        @can('po-create')
        <a class="btn btn-alt-primary" href="{{ route('po.create') }}">
          <i class="fa fa-plus mr-5"></i> Tambah PO Baru
        </a>
        @endcan
      </div>
    </div>
  </div>
</div>
@endsection