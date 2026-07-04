@extends('layouts.admin-nav')

@php
    $title = 'File Upload Demo';
    $pageTitle = 'File Upload & Gallery';
    $pageSubtitle = 'Demonstrasi fitur single dan multi-image upload ke database.';
@endphp

@section('title', $title)
@section('page-title', $pageTitle)
@section('page-subtitle', $pageSubtitle)

@section('nav-menu')
    @include('pages.admin.file-upload-examples.partials.nav')
@endsection

@section('main-content')

    @livewire('admin.file-upload-example-table')

@endsection
