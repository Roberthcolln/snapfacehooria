<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cetak Kartu Anggota TMCC</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&display=swap" rel="stylesheet">
  
  <style>
    *{
      -webkit-print-color-adjust: exact !important;
      color-adjust: exact !important;
      print-color-adjust: exact !important;
      font-family: 'Poppins', sans-serif;
    }

    .card {
      width: 600px;
      height: 350px;
      background-image: url('/idcard.png');
      background-size: cover;
      background-position: center;
      position: relative;
      margin: auto;
      text-align: center;
    }

    .foto {
      position: absolute;
      top: 130px;
      right: 120px;
      width: 100px;
      height: 100px;
      border-radius: 50%;
    }

    .qrcode {
      position: absolute;
      bottom: 38px;
      left: 45px;
    }

    .biodata {
      position: absolute;
      top: 55px;
      left: 30px;
      text-align: left;
      color: white;
    }

    .biodata h5 {
      font-size: 20px;
      margin: 0;
      position: absolute;
      top: 60px;
      left: 10px;
    }

    .biodata p {
      font-size: 14px;
      margin: 5px 0;
    }
  </style>
</head>
<body onload="window.print()">
    <div class="card">
      <img src="{{ asset('file/foto_album/'.$foto->file_foto) }}" alt="File foto" class="foto">
      
      <div class="biodata">
        <h1>{{ $foto->nama_foto }}</h1>
        
        
       
      </div>
    </div>
</body>
</html>
