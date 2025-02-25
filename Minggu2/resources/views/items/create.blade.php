<!DOCTYPE html>
<html>
<head>
    <title>Add Item</title>
</head>
<body >
    <h1>Add Item</h1>
    <!-- Form untuk menambahkan item baru -->
    <form action="{{ route('items.store') }}" method="POST">
        @csrf <!-- Token CSRF untuk mencegah serangan CSRF -->
        <label for="name">Name: </label>
        <input type="text" name="name" required> <!-- Input untuk nama item -->
        <br>
        <label for="description">Description:</label>
        <textarea name="description" required></textarea> <!-- Textarea untuk deskripsi item -->
        <br>
        <button type="submit">Add Item</button> <!-- Tombol untuk submit form -->
    </form>
    <a href="{{ route('items.index') }}">Back to List</a> <!-- Link untuk kembali ke daftar item -->
</body>
</html>
