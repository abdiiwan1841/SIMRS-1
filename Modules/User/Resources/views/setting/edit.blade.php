@extends('layouttemplate::pages')

@section('title')
    Ubah Data Akun
    @endsection

@section('content')
    <div class="card card-body">
        <h3>Ubah Data Akun</h3>
        <br>

        {{ Form::model($user, ['method' => 'PATCH', 'route' => ['setting.update', $user->id]]) }}

        <div class="form-group">
            {{ Form::label('nama', 'Nama', ['class' => 'control-label']) }}
            {{ Form::text('nama', null, ['class' => 'form-control']) }}
        </div>

        <div class="form-group">
            {{ Form::label('alamat', 'Alamat', ['class' => 'control-label']) }}
            {{ Form::text('alamat', null, ['class' => 'form-control']) }}
        </div>

        <div class="form-group">
            {{ Form::label('telepon', 'Telepon', ['class' => 'control-label']) }}
            {{ Form::text('telepon', null, ['class' => 'form-control']) }}
        </div>

        @if($user->jabatan == 'administrator')
        <div class="form-group">
            {{ Form::label('jabatan', 'Jabatan', ['class' => 'control-label']) }}
            <br>
            {{ Form::radio('jabatan', 'administrator') }} &nbsp; Administrator<br>
            {{ Form::radio('jabatan', 'petugas') }} &nbsp; Petugas Rawat Inap<br>
            {{ Form::radio('jabatan', 'kasir') }} &nbsp; Kasir<br>
        </div>
        @endif

        <br>

        {{ Form::submit('Simpan Perubahan', ['class' => 'btn btn-outline-success']) }}

        {{ Form::close() }}
    </div>
    @endsection