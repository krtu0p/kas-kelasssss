<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kas Kelas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table-responsive { max-height: 500px; overflow-y: auto; }
        .form-container { max-width: 600px; }
        .status-paid { color: green; }
        .status-unpaid { color: red; }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <h2 class="mb-4">Data Pembayaran Kas Kelas</h2>

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
        <form method="GET" action="{{ route('siswa.index') }}" class="mb-4 form-container d-flex gap-3 align-items-end">
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

        <!-- Payment Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">Nama Siswa</th>
                        @for ($i = 1; $i <= $maxMinggu; $i++)
                            <th scope="col" class="text-center">M{{ $i }}</th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    @forelse ($siswas as $siswa)
                        <tr>
                            <td>{{ $siswa->nama }}</td>
                            @for ($i = 1; $i <= $maxMinggu; $i++)
                                <td class="text-center">
                                    <span class="{{ $siswa->pembayaran->firstWhere('minggu', $i)?->status ? 'status-paid' : 'status-unpaid' }}">
                                        {{ $siswa->pembayaran->firstWhere('minggu', $i)?->status ? '✅' : '❌' }}
                                    </span>
                                </td>
                            @endfor
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $maxMinggu + 1 }}" class="text-center">Tidak ada data siswa untuk bulan ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Back to Dashboard -->
        <div class="mt-4">
            <a href="{{ route('dashboard') }}" class="btn btn-primary">Kembali ke Dashboard</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>