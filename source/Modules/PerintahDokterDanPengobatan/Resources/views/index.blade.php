@extends('layouttemplate::master')

@section('title')
    Perintah Dokter dan Pengobatan Pasien
@endsection

@section('content')
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs small">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('perjalanan_penyakit.index', $pasien->id) }}">Perjalanan Penyakit</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('perintah_dokter_dan_pengobatan.index', $pasien->id) }}">Perintah Dokter dan Pengobatan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('catatan_harian_perawatan.index', $pasien->id) }}">Catatan Harian dan Perawatan</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="col-md-12">
                    <div class="page-header">
                        <h4>Perintah Dokter dan Pengobatan: {{ $pasien->nama }}</h4>
                        <hr>
                        <div class="col-md-12">
                            <table>
                                <tbody class="small">
                                <tr>
                                    <th>Jenis Kelamin</th>
                                    <td style="padding-left: 10px">: {{ ucfirst($pasien->jenkel) }}</td>
                                </tr>
                                <tr>
                                    <th>Umur</th>
                                    <td id="umur" style="padding-left: 10px"></td>
                                </tr>
                                <tr>
                                    <th>Tanggal Masuk</th>
                                    <td style="padding-left: 10px">: {{ date("d F Y", strtotime($tanggal_masuk)) }}</td>
                                </tr>
                                <tr>
                                    <th>Diagnosa Awal</th>
                                    <td style="padding-left: 10px">: {{ ucfirst($diagnosa_awal) }}</td>
                                </tr>
                                </tbody>
                            </table>
                            <br>
                            <p id="tanggal_lahir" hidden>{{ $pasien->tanggal_lahir }}</p>
                        </div>
                    </div>
                    <table class="table table-striped small">
                        <thead>
                            <tr>
                                <th>Terapi dan Rencana Tindakan</th>
                                <th>Catatan Perawat</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($perintahs as $perintah)
                            <tr>
                                <td class="text-justify w-50">
                                    <b>Dibuat tanggal: {{ date("d F Y", strtotime($perintah->tanggal_keterangan)) }}</b><br>
                                    @if(date("d F Y", strtotime($perintah->tanggal_keterangan)) == date("d F Y", strtotime($perintah->updated_at)))
                                        <b>Diubah tanggal: –</b>
                                    @else
                                        <b>Diubah tanggal: {{ date("d F Y", strtotime($perintah->updated_at)) }}</b>
                                    @endif
                                    <hr>
                                    <p>{!! $perintah->planning_perintah_dokter_dan_pengobatan !!} &nbsp;<a href="{{ route('perjalanan_penyakit.show', [$pasien->id, $perintah->id]) }}">Perjalanan Penyakit...</a></p>
                                </td>
                                <td class="text-justify">
                                    {!! $perintah->perintah_dokter_dan_pengobatan->catatan_perawat or ''!!}
                                    @if(Auth::user()->jabatan_id == 3)
                                        @if(!empty($perintah->perintah_dokter_dan_pengobatan->catatan_perawat))
                                            <br><hr>
                                            <div class="btn-group float-right">
                                                <a href="{{ route('perintah_dokter_dan_pengobatan.edit', [$perintah->id_pasien, $perintah->id]) }}" class="btn btn-warning">Ubah</a>
                                            </div>
                                        @else
                                            <div class="btn-group float-left">
                                                <a href="{{ route('perintah_dokter_dan_pengobatan.create', ['id' => $perintah->id_pasien, 'perintah' => $perintah->id]) }}" class="btn btn-outline-primary">Catatan Baru</a>
                                            </div>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        var lahir = new Date($('#tanggal_lahir').text());
        var sekarang = new Date();
        var tahun_sekarang = sekarang.getFullYear();
        var tahun_lahir = lahir.getFullYear();
        var umur = tahun_sekarang - tahun_lahir;
        $('#umur').append(": " + umur + " Tahun");
    </script>
    @include('layouttemplate::attributes.pasien_ranap')
@endsection
