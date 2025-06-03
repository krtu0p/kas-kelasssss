<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Dashboard Bendahara</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">
    <div class="container py-5">
        <h2 class="mb-4">Dashboard Pembayaran Kas Kelas</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif


        <form method="POST" action="{{ route('pembayaran.update') }}">
            @csrf

            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Nama Siswa</th>
                        @for ($i = 1; $i <= $maxMinggu; $i++)
                            <th>M{{ $i }}</th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    @foreach ($siswas as $siswa)
                        <tr>
                            <td>{{ $siswa->nama }}</td>
                            @for ($i = 1; $i <= $maxMinggu; $i++)
                                @php
                                    $pembayaran = $siswa->pembayaran->firstWhere('minggu', $i);
                                    $checked = $pembayaran && $pembayaran->status ? 'checked' : '';
                                @endphp
                                <td class="text-center">
                                    <input type="checkbox" name="pembayaran[{{ $siswa->id }}][]" value="{{ $i }}" {{ $checked }} />
                                </td>
                            @endfor
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <button type="submit" class="btn btn-primary">Update Pembayaran</button>
        </form>

        <form method="POST" action="{{ route('pembayaran.tambah_minggu') }}" class="mt-3">
            @csrf
            <button type="submit" class="btn btn-success">Tambah Minggu</button>
        </form>

        <form method="POST" action="{{ route('pembayaran.hapus_minggu') }}" class="mt-3">
    @csrf
    <button type="submit" class="btn btn-warning">Hapus Minggu Terakhir (M{{ $maxMinggu }})</button>
</form>


        <form method="POST" action="{{ route('logout') }}" class="mt-3">
            @csrf
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>

        <div class="mt-4 text-center">
            <a href="{{ route('siswa.index') }}" class="btn btn-secondary">Lihat Halaman Siswa</a>
        </div>
    </div>
</body>
</html>
