<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemasukan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table-responsive { max-height: 500px; overflow-y: auto; }
        .form-container { max-width: 600px; }
        .card-total { background-color: #ffd700; color: black; padding: 15px; border-radius: 10px; }
        .btn-tambah { background-color: #4CAF50; color: white; }
        .btn-edit { background-color: #2196F3; color: white; }
        .btn-hapus { background-color: #f44336; color: white; }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <h2 class="mb-4">Pemasukan</h2>

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Form Pilih Bulan & Tahun -->
        <form method="GET" action="{{ route('pemasukan') }}" class="mb-4 form-container d-flex gap-3 align-items-end">
            <div class="flex-fill">
                <label for="bulan" class="form-label">Pilih Bulan</label>
                <select name="bulan" id="bulan" class="form-select" required>
                    @foreach ($dropdownBulan as $item)
                        <option value="{{ $item['bulan'] }}" {{ $item['bulan'] == $bulan ? 'selected' : '' }}>
                            {{ $item['nama'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex-fill">
                <label for="tahun" class="form-label">Tahun</label>
                <select name="tahun" id="tahun" class="form-select" required>
                    @foreach ($dropdownBulan->pluck('tahun')->unique()->sortDesc() as $thn)
                        <option value="{{ $thn }}" {{ $thn == $tahun ? 'selected' : '' }}>{{ $thn }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <button type="submit" class="btn btn-primary">Lihat</button>
            </div>
        </form>

        <!-- Total Pemasukan -->
        <div class="card-total mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h5>Total Kas</h5>
                <h3>Rp. {{ number_format($totalPemasukan, 0, ',', '.') }}</h3>
            </div>
            <span class="material-icons">money</span>
        </div>

        <!-- List Pemasukan -->
        <div class="row">
            @forelse ($pemasukan as $item)
                <div class="col-md-6 mb-3">
                    <div class="card p-3 bg-dark text-white">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5>{{ $item->nama }}</h5>
                                <small>Rp. {{ number_format($item->jumlah, 0, ',', '.') }}</small>
                                <p>{{ $item->tanggal->format('d/m/Y') }}</p>
                            </div>
                            <div>
                                <a href="{{ route('pemasukan.edit', $item->id) }}" class="btn btn-edit btn-sm me-2">✏️</a>
                                <form action="{{ route('pemasukan.destroy', $item->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-hapus btn-sm" onclick="return confirm('Yakin ingin menghapus?')">🗑️</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center">Tidak ada data pemasukan untuk bulan ini.</div>
            @endforelse
        </div>

        <!-- Tambah Pemasukan -->
        <div class="mt-4">
            <a href="{{ route('pemasukan.create') }}" class="btn btn-tambah">Tambah</a>
        </div>

        <!-- Kembali ke Dashboard -->
        <div class="mt-4">
            <a href="{{ route('dashboard') }}" class="btn btn-primary">Kembali ke Dashboard</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>