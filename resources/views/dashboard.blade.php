<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Bendahara</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table-responsive { max-height: 500px; overflow-y: auto; }
        .form-container { max-width: 600px; }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <h2 class="mb-4">Dashboard Pembayaran Kas Kelas</h2>

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
        <form method="GET" action="{{ route('dashboard') }}" class="mb-4 form-container d-flex gap-3 align-items-end">
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

        <!-- Form Update Pembayaran -->
        <form method="POST" action="{{ route('pembayaran.update') }}" class="mb-4">
            @csrf
            <input type="hidden" name="bulan" value="{{ $bulan }}">
            <input type="hidden" name="tahun" value="{{ $tahun }}">

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
                                        <input type="checkbox" name="pembayaran[{{ $siswa->id }}][]" value="{{ $i }}"
                                               {{ $siswa->pembayaran->firstWhere('minggu', $i)?->status ? 'checked' : '' }}>
                                    </td>
                                @endfor
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $maxMinggu + 1 }}" class="text-center">Tidak ada data siswa.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($maxMinggu > 0)
                <button type="submit" class="btn btn-primary">Update Pembayaran</button>
            @endif
        </form>

        <!-- Tambah/Hapus Minggu -->
        <div class="d-flex gap-2 mb-4">
            <form method="POST" action="{{ route('pembayaran.tambah_minggu') }}" class="d-inline">
                @csrf
                <input type="hidden" name="bulan" value="{{ $bulan }}">
                <input type="hidden" name="tahun" value="{{ $tahun }}">
                <button type="submit" class="btn btn-success">Tambah Minggu</button>
            </form>
            @if ($maxMinggu > 1)
                <form method="POST" action="{{ route('pembayaran.hapus_minggu') }}" class="d-inline">
                    @csrf
                    <input type="hidden" name="bulan" value="{{ $bulan }}">
                    <input type="hidden" name="tahun" value="{{ $tahun }}">
                    <button type="submit" class="btn btn-warning">Hapus Minggu Terakhir (M{{ $maxMinggu }})</button>
                </form>
            @endif
        </div>

        <!-- Tombol Tambah Bulan Baru -->
        <div class="mb-4">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahBulanModal">
                Tambah Bulan Baru
            </button>
        </div>

        <!-- Modal Tambah Bulan Baru -->
        <div class="modal fade" id="tambahBulanModal" tabindex="-1" aria-labelledby="tambahBulanLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content shadow-lg">
                    <div class="modal-header bg-secondary text-white">
                        <h5 class="modal-title" id="tambahBulanLabel">Tambah Bulan Baru</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('dashboard.tambahBulan') }}" method="POST">
                        @csrf
                        <div class="modal-body      bg-light">
                            <div class="mb-3">
                                <label for="bulanBaru" class="form-label">Pilih Bulan</label>
                                <select name="bulan" id="bulanBaru" class="form-select" required>
                                    @foreach ($bulanIndo as $blnKey => $blnNama)
                                        <option value="{{ $blnKey }}">{{ $blnNama }}</option>
                                    @endforeach
                                </select>
                                @error('bulan')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="tahunBaru" class="form-label">Tahun</label>
                                <select name="tahun" id="tahunBaru" class="form-select" required>
                                    @for ($y = now()->year - 5; $y <= now()->year + 5; $y++)
                                        <option value="{{ $y }}" {{ $y == $tahun ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                                @error('tahun')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer bg-light">
                            <button type="submit" class="btn btn-primary">Tambah</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <!-- Logout -->
        <form method="POST" action="{{ route('logout') }}" class="mb-4">
            @csrf
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>

        <!-- Navigation Buttons -->
        <div class="text-center">
            <a href="{{ route('siswa.index') }}" class="btn btn-secondary">Lihat Halaman Siswa</a>
            <a href="{{ route('pemasukan') }}" class="btn btn-success mx-2">Pemasukan</a>
            <a href="{{ route('pengeluaran') }}" class="btn btn-danger">Pengeluaran</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>