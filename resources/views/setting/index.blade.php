@extends('layouts.index')

@section('content')
<div class="row">
  <div class="col">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">{{ $title }}</h3>
      </div>
      <div class="card-body">
        @if ($message = Session::get('Sukses'))
          <div class="alert alert-success">
            <p class="m-0">{{ $message }}</p>
          </div>
          @endif

        <form class="row g-3" method="POST" action="{{ route('setting.update', $setting->id_setting) }}" enctype="multipart/form-data">
          @csrf
          @method('PUT')

          <div class="col-md-6 mb-3">
            <label class="form-label">Aplikasi</label>
            <input type="text" name="instansi_setting" class="form-control" value="{{ $setting->instansi_setting }}">
          </div>

          <div class="col-md-6 mb-3">
            <label class="form-label">Owner</label>
            <input type="text" name="pimpinan_setting" class="form-control" value="{{ $setting->pimpinan_setting }}">
          </div>

          <div class="col-md-6 mb-3">
            <label class="form-label">Meta Keyword</label>
            <input type="text" name="keyword_setting" class="form-control" value="{{ $setting->keyword_setting }}">
          </div>

          <div class="col-md-6 mb-3">
            <label class="form-label">Alamat</label>
            <input type="text" name="alamat_setting" class="form-control" value="{{ $setting->alamat_setting }}">
          </div>

          <div class="col-12 mb-3">
            <label class="form-label">Deskripsi Sekolah</label>
            <textarea name="tentang_setting" id="editor" class="form-control" rows="10">{{ $setting->tentang_setting }}</textarea>
          </div>

          <div class="col-md-6 mb-3">
            <label class="form-label">Youtube Sekolah</label>
            <input type="text" name="youtube_setting" class="form-control" value="{{ $setting->youtube_setting }}">
          </div>

          <div class="col-md-6 mb-3">
            <label class="form-label">Instagram Sekolah</label>
            <input type="text" name="instagram_setting" class="form-control" value="{{ $setting->instagram_setting }}">
          </div>

          <div class="col-md-6 mb-3">
            <label class="form-label">Email Sekolah</label>
            <input type="email" name="email_setting" class="form-control" value="{{ $setting->email_setting }}">
          </div>

          <div class="col-md-6 mb-3">
            <label class="form-label">No. HP Sekolah</label>
            <input type="text" name="no_hp_setting" class="form-control" value="{{ $setting->no_hp_setting }}">
          </div>

          <div class="col-md-6 mb-3">
            <label class="form-label">Logo Sekolah</label>
            <input type="file" name="logo_setting" class="form-control" accept="image/*">
          </div>

          <div class="col-md-6 mb-3">
            <label class="form-label">Preview Logo</label><br>
            <img src="{{ asset('logo/'.$setting->logo_setting) }}" alt="Logo" style="width: 200px;">
          </div>

          <div class="col-md-6 mb-3">
            <label class="form-label">Favicon Sekolah</label>
            <input type="file" name="favicon_setting" class="form-control" accept="image/*">
          </div>

          <div class="col-md-6 mb-3">
            <label class="form-label">Preview Favicon</label><br>
            <img src="{{ asset('favicon/'.$setting->favicon_setting) }}" alt="Favicon" style="width: 200px;">
          </div>

          <div class="col-md-6 mb-3">
            <label class="form-label">Watermark</label>
            <input type="file" name="watermark_setting" class="form-control" accept="image/*">
          </div>

          <div class="col-md-6 mb-3">
            <label class="form-label">Preview Watermark</label><br>
            <img src="{{ asset('watermark/'.$setting->watermark_setting) }}" alt="Watermark" style="width: 200px;">
          </div>

          <div class="col-12 mb-3">
            <label class="form-label">Link Maps Sekolah</label>
            <textarea name="maps_setting" class="form-control" rows="3">{{ $setting->maps_setting }}</textarea>
          </div>

          <div class="col-12 mb-3">
            <iframe class="w-100 rounded" src="{{ $setting->maps_setting }}" frameborder="0" style="min-height: 300px; border:0;" allowfullscreen></iframe>
          </div>

          <div class="col-12 mb-3 text-end">
            <button type="submit" class="btn btn-dark">
              <i class="fas fa-save"></i> Simpan
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
    <script>
        ClassicEditor
            .create(document.querySelector('#editor'), {
                ckfinder: {
                    uploadUrl: '{{route('image.upload').'?_token='.csrf_token()}}',
                }
            })
            .catch(error => {
                console.error(error);
            });
    </script>
    <script>
        CKEDITOR.replace('editor', {
            filebrowserUploadUrl: "",
            filebrowserUploadMethod: 'form'
        });
    </script>
    <script>
        ClassicEditor
            .create(document.querySelector('#editor1'), {
                ckfinder: {
                    uploadUrl: '{{route('image.upload').'?_token='.csrf_token()}}',
                }
            })
            .catch(error => {
                console.error(error);
            });
    </script>
    <script>
        CKEDITOR.replace('editor1', {
            filebrowserUploadUrl: "",
            filebrowserUploadMethod: 'form'
        });
    </script>
    
    @endsection
