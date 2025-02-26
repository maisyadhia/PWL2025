<!DOCTYPE html>
<html>
<head>
    <title>Edit Item</title>
</head>
<body>
    <h1>Edit Item</h1>
    <!-- Form untuk mengedit item yang sudah ada -->
    <form action="{{ route('items.update', $item) }}" method="POST">
        @csrf
        @method ('PUT') <!-- Menggunakan metode PUT untuk pembaruan -->
        <label for="name">Name:</label>
        <input type="text" name="name" value="{{ $item->name }}" required> <!-- Input untuk nama item yang ingin diedit -->
        <br>
        <label for="description">Description:</label>
        <textarea name="description" required>{{ $item->description }}</textarea> <!-- Textarea untuk deskripsi item yang ingin diedit -->
        <br>
        <button type="submit">Update Item</button> <!-- Tombol untuk submit perubahan -->
    </form>
    <a href="{{ route('items.index') }}">Back to List</a> <!-- Link untuk kembali ke daftar item -->
</body>
</html>
