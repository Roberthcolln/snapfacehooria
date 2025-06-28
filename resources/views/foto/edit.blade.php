@extends('layouts.index')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $title }}</h3>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Error!</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <form action="{{ route('foto.update', $foto->id_foto) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')


                        <div class="form-group mb-2">
                            <label for="">Nama</label>
                            <input type="text" name="nama_foto" class="form-control" value="{{ $foto->nama_foto }}" readonly>
                        </div>

                        <div class="form-group mb-2">
                            <label for="">Harga</label>
                            <input type="number" name="harga_foto" class="form-control" value="{{ $foto->harga_foto }}">
                        </div>


                        <div class="form-group mb-2">
                            <label for="">File <abbr title="" style="color: black">*</abbr> </label>
                            <input id="inputImg" type="file" accept="image/*" name="file_foto" class="form-control">
                            <!-- Preview foto lama -->
                            @if ($foto->file_foto)
                            <img src="{{ asset('file/foto_album/' . $foto->file_foto) }}" class="w-25 h-25 my-2" id="previewImg" alt="Foto lama">
                            @else
                            <img class="d-none w-25 h-25 my-2" id="previewImg" src="#" alt="Preview image">
                            @endif

                        </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-dark">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
    @endsection
    @section('script')
    @section('script')
    <script>
        document.getElementById('inputImg').addEventListener('change', function() {
            // Get the file input value and create a URL for the selected image
            var input = this;
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImg').setAttribute('src', e.target.result);
                    document.getElementById('previewImg').classList.add("d-block");
                };
                reader.readAsDataURL(input.files[0]);
            }
        });
    </script>
    <script>
        ClassicEditor
            .create(document.querySelector('#editor'), {

            })
            .catch(error => {
                console.error(error);
            });
    </script>
    <script>
        CKEDITOR.replace('editor', {
            filebrowserUploadUrl: "{{route('image.upload', ['_token' => csrf_token() ])}}",
            filebrowserUploadMethod: 'form'
        });
    </script>
    @endsection
    @endsection