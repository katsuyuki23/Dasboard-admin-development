@extends('layouts.app')

@section('title', 'Kategori Transaksi')

@section('content')
<h1 class="mb-4">Kategori Transaksi</h1>

<div class="row">
    <div class="col-md-5">
        <div class="card card-box p-3">
            <h5>Tambah Kategori</h5>
            <form action="{{ route('kategori.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label>Nama Kategori</label>
                    <input type="text" name="nama_kategori" class="form-control" required>
                </div>
                <button class="btn btn-primary w-100">Simpan</button>
            </form>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card card-box p-3">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Kategori</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kategori as $k)
                    <tr>
                        <td>{{ $k->id_kategori }}</td>
                        <td>{{ $k->nama_kategori }}</td>
                        <td>
                            <form action="{{ route('kategori.destroy', $k->id_kategori) }}" method="POST" onsubmit="return confirm('Hapus?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
