<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Bendahara - Kas Foerda</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --primary-color: #56B9F1;
            --dark-color: #212529;
            --white-color: #FFFFFF;
            --text-primary: #333333;
            --text-secondary: #757575;
            --border-color: #EEEEEE;
            --body-bg: #f8f9fa;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: var(--body-bg);
            color: var(--text-primary);
        }

        a {
            text-decoration: none;
            color: var(--text-primary);
        }

        .page-header {
            background-color: var(--primary-color);
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: var(--white-color);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .page-header .header-title {
            font-size: 1.75rem;
            font-weight: 700;
        }

        .page-header .header-title a {
            text-decoration: none;
            color: var(--white-color);
        }

        .page-header .user-button {
            background-color: var(--white-color);
            color: var(--primary-color);
            border: none;
            border-radius: 999px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            transition: transform 0.2s ease;
        }

        .user-button:hover {
            transform: scale(1.05);
        }

        /* PERBAIKAN: Padding pada .main-container dihapus dari sini dan dipindahkan ke kelas Bootstrap untuk membuatnya responsif */
        .main-container {
            /* padding: 2.5rem; */
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }

        .stat-card {
            background-color: var(--white-color);
            border-radius: 12px;
            padding: 1.5rem;
            transition: all 0.3s ease;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.07);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(86, 185, 241, 0.2);
        }

        .stat-card .amount {
            font-size: 1.6rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .stat-card .label {
            font-size: 0.9rem;
            color: var(--text-secondary);
        }

        .table-container {
            background-color: var(--white-color);
            padding: 2rem;
            border-radius: 16px;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.08);
        }

        .filter-form label {
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
            display: block;
        }

        .btn-lihat {
            background-color: var(--primary-color);
            color: var(--white-color);
            border-radius: 8px;
            font-weight: 600;
            transition: background-color 0.2s ease;
        }

        .btn-lihat:hover {
            background-color: #3C9FDA;
            color: var(--white-color);
        }

        .payment-table {
            width: 100%;
            border-collapse: collapse;
        }

        .payment-table thead {
            background-color: var(--dark-color);
            color: var(--white-color);
        }

        .payment-table th,
        .payment-table td {
            padding: 1rem;
            text-align: left;
            vertical-align: middle;
            border-bottom: 1px solid var(--border-color);
        }

        .payment-table th:not(:first-child),
        .payment-table td:not(:first-child) {
            text-align: center;
        }

        .payment-table tbody tr:hover {
            background-color: #F9FAFB;
        }

        .payment-table .form-check-input {
            transform: scale(1.4);
            cursor: pointer;
        }

        /* PERBAIKAN: Media Query untuk layar kecil (di bawah 768px) */
        @media (max-width: 767.98px) {
            .main-container {
                padding: 1.5rem;
                /* Padding lebih kecil di mobile */
            }

            .page-header {
                padding: 1rem;
                flex-direction: column;
                /* Susun ke bawah di mobile */
                gap: 0.5rem;
            }

            .page-header .header-title {
                font-size: 1.4rem;
                /* Ukuran font lebih kecil */
            }

            .table-container {
                padding: 1rem;
                /* Padding lebih kecil di mobile */
            }

            .stat-card .amount {
                font-size: 1.4rem;
                /* Ukuran font lebih kecil */
            }
        }
    </style>
</head>

<body>
    <header class="page-header">
        <div class="header-title">
            <a href="{{ route('dashboard') }}">Kas Foerda</a>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="user-button">Logout</button>
        </form>
    </header>

    {{-- PERBAIKAN: Menggunakan kelas padding responsif dari Bootstrap --}}
    <main class="main-container p-3 p-md-4 p-lg-5">

        <div class="stats-grid">
            <div class="stat-card">
                <a href="{{ route('pemasukan') }}">
                    <div class="amount">IDR. {{ number_format($totalPemasukan ?? 0, 0, ',', '.') }}</div>
                    <div class="label">Total Pemasukan</div>
                </a>
            </div>
            <div class="stat-card">
                <a href="{{ route('pengeluaran') }}">
                    <div class="amount">IDR. {{ number_format($totalPengeluaran ?? 0, 0, ',', '.') }}</div>
                    <div class="label">Total Pengeluaran</div>
                </a>
            </div>
            <div class="stat-card">
                <div class="amount">IDR. {{ number_format($totalKas ?? 0, 0, ',', '.') }}</div>
                <div class="label">Total Kas Saat Ini</div>
            </div>
            <div class="stat-card">
                <a href="{{ route('utang') }}">
                    <div class="amount">Utang</div>
                    <div class="label">Lihat Data Utang</div>
                </a>
            </div>
        </div>

        <div class="table-container">
            <h2 class="mb-4 fw-bold">Dashboard Pembayaran Kas Kelas</h2>

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

            {{-- PERBAIKAN: Menggunakan Grid System Bootstrap untuk form filter --}}
            <form method="GET" action="{{ route('dashboard') }}" class="mb-4">
                <div class="row g-3 align-items-end">
                    <div class="col-12 col-sm-6 col-md-5">
                        <label for="bulan" class="form-label fw-bold">Pilih Bulan</label>
                        <select name="bulan" id="bulan" class="form-select" required>
                            @foreach ($dropdownBulan as $item)
                            <option value="{{ $item['bulan'] }}" {{ $item['bulan'] == $bulan ? 'selected' : '' }}>
                                {{ $item['nama'] }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-sm-6 col-md-5">
                        <label for="tahun" class="form-label fw-bold">Pilih Tahun</label>
                        <select name="tahun" id="tahun" class="form-select" required>
                            @foreach ($dropdownBulan->pluck('tahun')->unique()->sortDesc() as $thn)
                            <option value="{{ $thn }}" {{ $thn == $tahun ? 'selected' : '' }}>{{ $thn }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-2">
                        <button type="submit" class="btn btn-lihat w-100">Lihat</button>
                    </div>
                </div>
            </form>

            <form method="POST" action="{{ route('pembayaran.update') }}" class="mt-4">
                @csrf
                <input type="hidden" name="bulan" value="{{ $bulan }}">
                <input type="hidden" name="tahun" value="{{ $tahun }}">

                <div class="table-responsive border rounded-3">
                    <table class="table payment-table mb-0">
                        <thead>
                            <tr>
                                <th scope="col" class="ps-3">Nama Siswa</th>
                                @for ($i = 1; $i <= $maxMinggu; $i++)
                                    <th scope="col">M{{ $i }}</th>
                                    @endfor
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($siswas as $siswa)
                            <tr>
                                <td class="ps-3">{{ $siswa->nama }}</td>
                                @for ($i = 1; $i <= $maxMinggu; $i++)
                                    <td>
                                    <input type="checkbox" name="pembayaran[{{ $siswa->id }}][]" value="{{ $i }}" class="form-check-input"
                                        {{ $siswa->pembayaran->firstWhere('minggu', $i)?->status ? 'checked' : '' }}>
                                    </td>
                                    @endfor
                            </tr>
                            @empty
                            <tr>
                                <td colspan="{{ $maxMinggu + 1 }}" class="text-center py-5">
                                    <p class="mb-1 fs-5 text-secondary">Tidak ada data siswa.</p>
                                    <small>Silakan tambahkan data siswa terlebih dahulu di halaman siswa.</small>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    <div class="row gy-3">
                        <div class="col-12 col-md-auto">
                            @if ($maxMinggu > 0)
                            <button type="submit" class="btn btn-primary w-100 py-2">Update Pembayaran</button>
                            @endif
                        </div>
                        <div class="col-12 col-md d-flex flex-wrap gap-2">
                            <form method="POST" action="{{ route('pembayaran.tambah_minggu') }}" class="m-0">
                                @csrf
                                <input type="hidden" name="bulan" value="{{ $bulan }}">
                                <input type="hidden" name="tahun" value="{{ $tahun }}">
                                <button type="submit" class="btn btn-success">Tambah Minggu</button>
                            </form>
                            @if ($maxMinggu > 1)
                            <form method="POST" action="{{ route('pembayaran.hapus_minggu') }}" class="m-0">
                                @csrf
                                <input type="hidden" name="bulan" value="{{ $bulan }}">
                                <input type="hidden" name="tahun" value="{{ $tahun }}">
                                <button type="submit" class="btn btn-warning">Hapus Minggu Terakhir (M{{ $maxMinggu }})</button>
                            </form>
                            @endif
                            <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#tambahBulanModal">
                                Tambah Bulan Baru
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            <div class="mt-4 pt-4 border-top d-flex flex-wrap justify-content-center gap-2">
                <a href="{{ route('siswa.index') }}" class="btn btn-secondary">Lihat Siswa</a>
            </div>
        </div>
    </main>

    {{-- Kode Modal tidak diubah --}}
    <div class="modal fade" id="tambahBulanModal" tabindex="-1" aria-labelledby="tambahBulanLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="tambahBulanLabel">Tambah Bulan Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('dashboard.tambahBulan') }}" method="POST">
                    @csrf
                    <div class="modal-body">
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
                                @for ($y = now()->year - 2; $y <= now()->year + 2; $y++)
                                    <option value="{{ $y }}" {{ $y == now()->year ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                            </select>
                            @error('tahun')
                            <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>