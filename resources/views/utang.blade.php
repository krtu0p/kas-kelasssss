<!-- resources/views/utang.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Utang Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container { max-width: 600px; }
        .table-responsive { max-height: 500px; overflow-y: auto; }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <h1>Data Utang Siswa</h1>

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

        <!-- Filter Form -->
        <form method="GET" action="{{ route('utang') }}" class="mb-4 form-container d-flex gap-3 align-items-end">
            <div class="flex-fill">
                <label for="bulan" class="form-label">Pilih Bulan</label>
                <select name="bulan" id="bulan" class="form-select" required>
                    <option value="">Semua Bulan</option>
                    @foreach ($dropdownBulan as $item)
                        <option value="{{ $item['bulan'] }}" {{ $bulan == $item['bulan'] ? 'selected' : '' }}>{{ $item['nama'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-fill">
                <label for="tahun" class="form-label">Tahun</label>
                <select name="tahun" id="tahun" class="form-select" required>
                    @foreach ($yearRange as $year)
                        <option value="{{ $year }}" {{ $tahun == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <button type="submit" class="btn btn-primary">Lihat</button>
            </div>
        </form>

        <!-- Debt Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Nama Siswa</th>
                        <th scope="col">Jumlah Tunggakan (Minggu)</th>
                        <th scope="col">Total Utang (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($utangData as $index => $utang)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $utang['nama'] }}</td>
                            <td>{{ $utang['missed_payments'] }}</td>
                            <td>{{ number_format($utang['total_utang'], 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada utang ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Navigation Buttons -->
        <div class="text-center mt-4">
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Dashboard</a>
            <a href="{{ route('siswa.index') }}" class="btn btn-secondary mx-2">Lihat Halaman Siswa</a>
            <a href="{{ route('pemasukan') }}" class="btn btn-success mx-2">Pemasukan</a>
            <a href="{{ route('pengeluaran') }}" class="btn btn-danger">Pengeluaran</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>