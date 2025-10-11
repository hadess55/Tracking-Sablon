@extends('layouts.admin')
@section('header','Tambah Status Produksi')
@section('content')
<form method="POST" action="{{ route('admin.produksi-status.store') }}">
  @csrf
  @include('admin.produksi_status._form')
</form>
@endsection
