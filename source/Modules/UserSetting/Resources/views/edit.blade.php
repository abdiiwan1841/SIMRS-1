@extends('layouttemplate::master')

@section('title')
    Ubah Rincian Akun
    @endsection

@section('content')
    <div class="card card-body">
        {{ Form::model($user, ['method' => 'PATCH', 'route' => ['setting.update', $user->id]]) }}

        <div class="form-group d-none">
            {{ Form::label('id_user', 'ID', ['class' => 'control-label']) }}
            {{ Form::text('id_user', null, ['class' => 'form-control']) }}
        </div>

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

        @if($user->jabatan_id == '1')
            <div class="form-group">
                <label name="jabatan_id">Jabatan</label><br>
                @foreach($jabatans as $jabatan)
                    <input type="radio" name="jabatan_id" value="{{ $jabatan->id }}" style="" {{ $user->jabatan_id == $jabatan->id ? 'checked' : ''}} > {{ ucfirst($jabatan->nama )}}<br>
                @endforeach
                <br>
            </div>
        @endif

        <br>

        {{ Form::submit('Simpan Perubahan', ['class' => 'btn btn-outline-success float-right']) }}

        {{ Form::close() }}
    </div>
    @endsection