<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ubah Kata Sandi</title>
    <link rel="stylesheet" href="https://unpkg.com/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-6 rounded-lg shadow-md w-96">
        <h2 class="text-xl font-bold mb-4 text-gray-800">Ubah Kata Sandi</h2>

        @if (session('success'))
            <div class="mb-4 text-green-600 text-sm bg-green-100 p-2 rounded">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Kata Sandi Saat Ini</label>
                <input type="password" name="current_password" class="w-full px-3 py-2 border rounded-lg @error('current_password') border-red-500 @enderror" required>
                @error('current_password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Kata Sandi Baru</label>
                <input type="password" name="password" class="w-full px-3 py-2 border rounded-lg @error('password') border-red-500 @enderror" required>
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Konfirmasi Kata Sandi Baru</label>
                <input type="password" name="password_confirmation" class="w-full px-3 py-2 border rounded-lg" required>
            </div>

            <div class="flex justify-between items-center">
                <a href="{{ route('dashboard') }}" class="text-blue-500 text-sm hover:underline">Kembali</a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Simpan</button>
            </div>
        </form>
    </div>
</body>
</html>