@extends('layouts.admin-nav')

@section('nav-menu')
    @include('pages.admin.access-management.partials.nav')
@endsection

@php
    $title = 'Manajemen Role & Hak Akses';
    $pageTitle = 'Role & Hak Akses';
    $pageSubtitle = 'Kelola peran dan izin untuk setiap pengguna.';
@endphp

@section('title', $title)
@section('page-title', $pageTitle)
@section('page-subtitle', $pageSubtitle)

@section('main-content')

    @livewire('admin.role-table')

@endsection
