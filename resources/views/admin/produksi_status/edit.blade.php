@extends('layouts.admin')
@section('header','Edit Status Produksi')
@section('content')
<form method="POST" action="{{ route('admin.produksi-status.update',$status) }}">
  @csrf @method('PUT')
  @include('admin.produksi_status._form',['status'=>$status])
</form>
@endsection
