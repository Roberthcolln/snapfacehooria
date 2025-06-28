@extends('layouts.index')
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h5 class="mb-0">{{ $title }}</h5>
          <a href="{{ route('foto.create') }}" class="btn btn-dark btn-sm" style="float: right"><i class="fas fa-plus"></i> Tambah</a>


        </div>

        <div class="card-body">
          @if ($message = Session::get('Sukses'))
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
          @endif


          <table id="example3" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th style="width: 5%;">No</th>
                <th>Nama</th>
                <th>Harga</th>
                <th>Foto</th>
                <th style="width: 28%;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($foto as $row)
              <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $row->nama_foto }}</td>
                <td>Rp {{ number_format($row->harga_foto, 0, ',', '.') }}</td>
                <td class="text-center">
                  <img src="{{ asset('file/foto_album/'.$row->file_foto) }}" alt="foto" class="img-thumbnail" style="max-width: 80px;">
                </td>
                <td class="text-center">
                  <a href="{{ route('foto.edit', $row->id_foto) }}" class="btn btn-sm btn-primary me-1 mb-1">
                    <i class="fas fa-edit me-1"></i>Edit
                  </a>
                  <a href="{{ route('foto.show', $row->id_foto) }}" class="btn btn-sm btn-warning me-1 mb-1 text-white">
                    <i class="fas fa-eye me-1"></i>Detail
                  </a>
                  <!-- <a href="{{ url('foto/cetak-kartu', $row->id_foto) }}" class="btn btn-sm btn-success me-1 mb-1" target="_blank">
                      <i class="fas fa-print me-1"></i>Print
                    </a> -->
                  <form action="{{ route('foto.destroy', $row->id_foto) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger mb-1 show_confirm">
                      <i class="fas fa-trash me-1"></i>Delete
                    </button>
                  </form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>

        </div> <!-- /.card-body -->
      </div> <!-- /.card -->
    </div>
  </div>
</div>

  @endsection