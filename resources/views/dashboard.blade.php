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
        .user-profile-dropdown .user-button {
            width: 45px;
            height: 45px;
            padding: 0;
            border: 2px solid var(--white-color);
            border-radius: 50%;
            background-color: var(--primary-color);
            color: var(--white-color);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }
        .user-profile-dropdown .user-button:hover {
            transform: scale(1.1);
            background-color: var(--white-color);
            color: var(--primary-color);
        }
        .user-profile-dropdown .user-button .bi-person-fill {
            font-size: 1.5rem;
        }
        .user-profile-dropdown .dropdown-menu {
            border-radius: 0.75rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border: none;
            padding: 0.5rem;
        }
        .user-profile-dropdown .dropdown-item {
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
        }
        .user-profile-dropdown .dropdown-item .bi {
            margin-right: 0.5rem;
            vertical-align: middle;
        }
        .user-profile-dropdown .dropdown-item.text-danger:hover,
        .user-profile-dropdown .dropdown-item.text-danger:focus {
            background-color: #f8d7da;
            color: #721c24 !important;
        }
        .main-container {
            /* Padding dipindahkan ke kelas Bootstrap untuk responsivitas */
        }
        .content-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 2rem;
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
        table.payment-table thead th {
            background-color: #000000;
            color: var(--white-color);
        }
        .payment-table th, .payment-table td {
            padding: 1rem;
            text-align: left;
            vertical-align: middle;
            border-bottom: 1px solid var(--border-color);
        }
        /* PERBAIKAN: Menyesuaikan perataan teks setelah kolom 'No' ditambahkan */
        .payment-table th:first-child,
        .payment-table td:first-child {
            text-align: center;
            width: 1%; /* Membuat kolom 'No' tidak terlalu lebar */
        }
        .payment-table th:nth-child(n+3),
        .payment-table td:nth-child(n+3) {
            text-align: center; /* Membuat kolom M1, M2, dst tetap di tengah */
        }
        .payment-table tbody tr:hover {
            background-color: #F9FAFB;
        }
        .payment-table .form-check-input {
            transform: scale(1.4);
            cursor: pointer;
        }
        @media (max-width: 767.98px) {
            .main-container {
                padding: 1.5rem;
            }
            .page-header {
                padding: 1rem;
                flex-direction: column;
                gap: 0.5rem;
            }
            .page-header .header-title {
                font-size: 1.4rem;
            }
            .table-container {
                padding: 1rem;
            }
            .stat-card .amount {
                font-size: 1.4rem;
            }
        }
    </style>
</head>

<body>
    <header class="page-header">
        <div class="header-title">
            <a href="{{ route('dashboard') }}">Kas Foerda</a>
        </div>
        
        <div class="dropdown user-profile-dropdown">
            <button class="user-button" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="User Menu">
                <i class="bi bi-person-fill"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <form id="logoutForm" method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="button" class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#logoutConfirmModal">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </header>

    <main class="main-container p-md-4">
        <h1 class="content-title">Dashboard Pembayaran Kas</h1>
        
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
                <div class="label">Total Kas</div>
            </div>
            <div class="stat-card">
                <a href="{{ route('utang.admin') }}">
                    <div class="amount">Utang</div>
                    <div class="label">Lihat Data Utang</div>
                </a>
            </div>
        </div>

        <div class="table-container">
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
                                {{-- PERBAIKAN 1: Tambahkan header kolom 'No.' --}}
                                <th scope="col">No</th>
                                <th scope="col" class="ps-3">Nama Siswa</th>
                                @for ($i = 1; $i <= $maxMinggu; $i++)
                                <th scope="col">M{{ $i }}</th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($siswas as $siswa)
                            <tr>
                                {{-- PERBAIKAN 2: Tambahkan sel data untuk nomor urut --}}
                                <td>{{ $loop->iteration }}</td>
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
                                {{-- PERBAIKAN 3: Sesuaikan colspan --}}
                                <td colspan="{{ $maxMinggu + 2 }}" class="text-center py-5">
                                    <p class="mb-1 fs-5 text-secondary">Tidak ada data siswa.</p>
                                    <small>Silakan tambahkan data siswa terlebih dahulu di halaman siswa.</small>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    @if ($maxMinggu > 0)
                    <button type="submit" class="btn btn-primary py-2 px-4">Update Pembayaran</button>
                    @endif
                </div>
            </form>

            <div class="mt-3 d-flex flex-wrap gap-2">
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
            
            <div class="mt-4 pt-4 border-top d-flex flex-wrap justify-content-center gap-2">
                <a href="{{ route('siswa.index') }}" class="btn btn-secondary">Manajemen Siswa</a>
                 <a href="{{ route('pemasukan') }}" class="btn btn-outline-success">Manajemen Pemasukan</a>
                <a href="{{ route('pengeluaran') }}" class="btn btn-outline-danger">Manajemen Pengeluaran</a>
            </div>
        </div>
    </main>

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
                        </div>
                        <div class="mb-3">
                            <label for="tahunBaru" class="form-label">Tahun</label>
                            <select name="tahun" id="tahunBaru" class="form-select" required>
                                @for ($y = now()->year - 2; $y <= now()->year + 2; $y++)
                                    <option value="{{ $y }}" {{ $y == now()->year ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
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

    <div class="modal fade" id="logoutConfirmModal" tabindex="-1" aria-labelledby="logoutConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="logoutConfirmModalLabel">Konfirmasi Logout</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="fs-5">Apakah Anda yakin akan logout?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmLogoutBtn">Logout</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const confirmLogoutBtn = document.getElementById('confirmLogoutBtn');
        if (confirmLogoutBtn) {
            confirmLogoutBtn.addEventListener('click', function () {
                document.getElementById('logoutForm').submit();
            });
        }
    </script>
</body>
</html>