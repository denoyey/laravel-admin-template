@extends('layouts.admin-nav')

@section('nav-menu')
    @include('pages.admin.access-management.partials.nav')
@endsection

@php
    $title = 'Manajemen Pengguna';
    $pageTitle = 'Pengguna';
    $pageSubtitle = 'Daftar pengguna portal administrasi.';
@endphp

@section('title', $title)
@section('page-title', $pageTitle)
@section('page-subtitle', $pageSubtitle)

@section('main-content')

    @livewire('admin.user-table')

@endsection
