@extends('layouts.admin')

@section('page-title', 'Riwayat Aktivitas')
@section('page-subtitle', 'Pantau semua aktivitas perubahan data di sistem')

@section('content')
    @livewire('admin.activity-log-table')
@endsection
