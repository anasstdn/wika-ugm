@extends('vendor.installer.layouts.master-update')
@php
// dd(glob(base_path('Modules').DIRECTORY_SEPARATOR.'*'));
$migrations_default = glob(database_path().DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR.'*.php');
$migration_pengaturan = glob(glob(base_path('Modules').DIRECTORY_SEPARATOR.'*')[0].DIRECTORY_SEPARATOR.'Database'.DIRECTORY_SEPARATOR.'Migrations'.DIRECTORY_SEPARATOR.'*.php');
$migration_transaksi = glob(glob(base_path('Modules').DIRECTORY_SEPARATOR.'*')[1].DIRECTORY_SEPARATOR.'Database'.DIRECTORY_SEPARATOR.'Migrations'.DIRECTORY_SEPARATOR.'*.php');

$migrations = array_merge($migrations_default,$migration_pengaturan,$migration_transaksi);
$migrations = str_replace('.php', '', $migrations);

$migration_exec = DB::table('migrations')->get()->pluck('migration');

$numberOfUpdatesPending = count($migrations) - count($migration_exec);
        // return str_replace('.php', '', $migrations);
@endphp
@section('title', trans('installer_messages.updater.welcome.title'))
@section('container')
    <p class="paragraph text-center">{{ trans_choice('installer_messages.updater.overview.message', $numberOfUpdatesPending, ['number' => $numberOfUpdatesPending]) }}</p>
    <div class="buttons">
        <a href="{{ route('LaravelUpdater::database') }}" class="button">{{ trans('installer_messages.updater.overview.install_updates') }}</a>
    </div>
@stop
