<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        img {
            -webkit-user-drag: none;
            user-select: none;
            pointer-events: none;
        }

        body {
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col items-center p-4">
    <h1 class="text-3xl font-bold text-center text-gray-800 my-6">🖼️ Keranjang Foto</h1>

    <div class="w-full max-w-4xl bg-white rounded-2xl shadow-lg p-6">
        <!-- Item Produk -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-center border-b pb-6 mb-6">
            <div class="relative">
                <img src="{{ asset('file/foto_album/' . $foto->file_foto) }}" alt="Foto" class="rounded-xl w-full md:w-32 h-auto object-cover">
                <div class="absolute inset-0 pointer-events-none opacity-20 bg-repeat" style="background-image: url('{{ asset('watermark/' . $konf->watermark_setting) }}'); background-size: 80px;">
                </div>
            </div>

            <div class="md:col-span-2">
                <h2 class="text-xl font-semibold text-gray-800">{{ $foto->nama_foto }}</h2>
                <p class="text-sm text-gray-600 mt-1">Nama File: {{ $foto->file_foto }}</p>
            </div>
            <div class="text-right">
                <p class="text-lg font-bold text-green-600">Rp {{ number_format($foto->harga_foto, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Tombol Checkout -->
        <div class="text-right">
            <form action="#" method="POST">
                @csrf
                <input type="hidden" name="foto_id" value="{{ $foto->id }}">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-3 rounded-xl shadow-lg transition-all duration-200">
                    🧾 Beli Sekarang
                </button>
            </form>
        </div>

        <!-- Tombol Kembali -->
        <div class="mt-6 text-center">
            <a href="{{ url('/') }}" class="text-blue-600 hover:underline">⬅️ Kembali ke Beranda</a>
        </div>
    </div>

    <!-- Proteksi tambahan -->
    <script>
        // Blok klik kanan
        document.addEventListener("contextmenu", e => e.preventDefault());

        // Blok shortcut keyboard
        document.addEventListener("keydown", function (e) {
            const blockedKeys = ["F12", "F11", "F10", "F9", "PrintScreen"];
            if (
                blockedKeys.includes(e.key) ||
                e.keyCode === 44 || // PrintScreen
                e.keyCode === 91 || // Windows Key
                (e.ctrlKey && ["u", "s", "p"].includes(e.key.toLowerCase())) ||
                (e.ctrlKey && e.shiftKey && ["i", "c", "j"].includes(e.key.toLowerCase()))
            ) {
                e.preventDefault();
                alert("Akses dibatasi demi keamanan.");
            }
        });

        // Coba mendeteksi PrintScreen (tidak pasti berhasil)
        document.addEventListener("keyup", function(e) {
            if (e.key === "PrintScreen") {
                alert("Screenshot tidak diperbolehkan!");
            }
        });

        // Cegah long press di mobile
        document.addEventListener('touchstart', preventLongPress, false);
        document.addEventListener('touchend', preventLongPress, false);
        let timer;
        function preventLongPress(e) {
            if (e.type === 'touchstart') {
                timer = setTimeout(() => {
                    e.preventDefault();
                }, 500);
            } else {
                clearTimeout(timer);
            }
        }
    </script>
</body>
</html>
