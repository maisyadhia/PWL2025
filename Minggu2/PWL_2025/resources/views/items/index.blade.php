<!DOCTYPE html>
<html>
<head>
    <title>Item List</title>
</head>
<body>
    <h1>Items</h1>
    <!-- Menampilkan pesan sukses jika ada -->
    @if(session ('success'))
        <p>{{ session( 'success')}}</p>
    @endif

    <a href="{{ route('items.create') }}">Add Item</a> <!-- Link untuk menambah item baru -->
    <ul>
        @foreach ($items as $item)
            <li>
                {{ $item->name }} - <!-- Menampilkan nama item -->
                <a href="{{ route('items.edit', $item) }}">Edit</a> <!-- Link untuk mengedit item -->
                <form action="{{ route('items.destroy', $item) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE') <!-- Menyatakan bahwa form ini untuk menghapus data -->
                    <button type="submit">Delete</button> <!-- Tombol untuk menghapus item -->
                </form>
            </li>
        @endforeach
    </ul>
</body>
</html>
