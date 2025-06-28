@extends('layouts.index')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <!-- <h3 class="card-title">{{ $title }} </h3> -->
                    <a href="{{ route('foto.index') }}" class="btn btn-warning" style="float: right;"><i class="fas fa-backward"></i> Kembali</a>
                </div>
                <div class="card-body table table-responsive">
                    <table class="table">

                        <tr>
                            <th style="width: 30%;">Nama</th>
                            <th style="width: 20px;">:</th>
                            <td>{{ $foto->nama_foto }}</td>
                        </tr>


                        <tr>
                            <th style="width: 30%;">File</th>
                            <th style="width: 20px;">:</th>
                            <td><img src="{{ asset('file/foto_album/'.$foto->file_foto) }}" alt="" style="width: 100px;"></td>
                        </tr>


                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

    @endsection