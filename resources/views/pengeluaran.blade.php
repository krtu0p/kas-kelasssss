<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengeluaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table-responsive { max-height: 500px; overflow-y: auto; }
        .form-container { max-width: 600px; }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <h2 class="mb-4">Pengeluaran</h2>

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
        <form method="GET" action="{{ route('pengeluaran') }}" class="mb-4 form-container d-flex gap-3 align-items-end">
            <div class="flex-fill">
                <label for="bulan" class="form-label">Pilih Bulan</label>
                <select name="bulan" id="bulan" class="form-select" required @if ($dropdownBulan->isEmpty()) disabled @endif>
                    @if ($dropdownBulan->isEmpty())
                        <option value="">Tidak ada data untuk tahun ini</option>
                    @else
                        @foreach ($dropdownBulan as $key => $value)
                            <option value="{{ $key }}" {{ $key == $bulan ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="flex-fill">
                <label for="tahun" class="form-label">Tahun</label>
                <select name="tahun" id="tahun" class="form-select" required onchange="this.form.submit()">
                    @foreach ($yearRange as $year)
                        <option value="{{ $year }}" {{ $year == $tahun ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <button type="submit" class="btn btn-primary" @if ($dropdownBulan->isEmpty()) disabled @endif>Lihat</button>
            </div>
        </form>

        <!-- Table or Other Content -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Nama</th>
                        <th>Jumlah</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pengeluaran as $item)
                        <tr>
                            <td>{{ $item->nama }}</td>
                            <td>{{ number_format($item->jumlah, 2) }}</td>
                            <td>{{ $item->tanggal->format('d-m-Y') }}</td>
                            <td>
                                <a href="{{ route('pengeluaran.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('pengeluaran.destroy', $item->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada data pengeluaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            <p>Total Pengeluaran: {{ number_format($totalPengeluaran, 2) }} Rp</p>
        </div>
        <a href="{{ route('pengeluaran.create') }}" class="btn btn-success mt-3">Tambah Pengeluaran</a>
        <div class="mt-4">
            <a href="{{ route('dashboard') }}" class="btn btn-primary">Kembali ke Dashboard</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>