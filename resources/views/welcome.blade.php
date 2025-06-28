<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>{{ $konf->instansi_setting }}</title>
 
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />

  <link rel="icon" href="{{ asset('favicon/'.$konf->favicon_setting) }}">
  <link rel="stylesheet" href="{{ asset('web/style.css') }}">

  <!-- face-api -->
  <script defer src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
</head>

<body data-watermark="{{ asset('watermark/' . $konf->watermark_setting) }}">

  <!-- Logo & Judul -->
  <header style="text-align:center; margin-top:20px;">
    <img class="logo" src="{{ asset('logo/'.$konf->logo_setting) }}" alt="Logo Instansi" style="height:80px;">
    <h2>{{ $konf->instansi_setting }}</h2>
  </header>

  <!-- Tombol Scan -->
  <div style="text-align:center; margin: 20px;">
    <button id="startScanButton" class="scan-button"> Scan Wajah</button>
  </div>

  <!-- Area Kamera -->
  <div class="container" style="display:none; position:relative; justify-content:center;">
    <video id="videoInput" autoplay muted playsinline style="border-radius:10px;"></video>
    <canvas id="guideCanvas"></canvas>
  </div>

  <!-- Status -->
  <div id="statusMessage" style="text-align:center; margin-top:10px; font-size:16px;">
    <small><i>Tombol "Mulai Scan Wajah" untuk memulai...</i></small>
  </div>

  <!-- Loading Spinner -->
  <div id="loadingIndicator" style="display:none; text-align:center; margin-top:15px;">
    <div class="spinner"></div>
  </div>

  <!-- Hasil -->
  <div id="recognizedFaceContainer"></div>
  <div id="notFoundMessage" style="color:red; text-align:center; margin-top:10px; display:none;">❌ Wajah tidak ditemukan</div>

  <!-- Audio Panduan -->
  <audio id="beepSound" src="/sounds/beep.mp3"></audio>
  <audio id="voiceCenter" src="/sounds/tengah.mp3"></audio>
  <audio id="voiceLeft" src="/sounds/kiri.mp3"></audio>
  <audio id="voiceRight" src="/sounds/kanan.mp3"></audio>

  <!-- Script utama -->
  <script src="{{ asset('web/scan.js') }}"></script>

</body>
</html>
