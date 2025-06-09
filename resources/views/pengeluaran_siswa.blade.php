<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengeluaran - Kas Foerda</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --primary-color: #56B9F1;
            --white-color: #FFFFFF;
            --dark-color: #212529;
            --text-primary: #333333;
            --text-secondary: #757575;
            --border-color: #DEE2E6;
            --body-bg: #f8f9fa;

            /* DITAMBAHKAN: Warna untuk tombol aksi dari Anda */
            --edit-bg-color: #FFF4D4;
            --edit-icon-color: #F79009;
            --delete-bg-color: #FEF3F2;
            --delete-icon-color: #F04438;
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
            background-color: var(--primary-color); padding: 1.5rem 2rem;
            display: flex; justify-content: space-between; align-items: center;
            color: var(--white-color); box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .page-header .header-title { font-size: 1.5rem; font-weight: 700; }
        .page-header .header-title a { text-decoration: none; color: var(--white-color); }
        .page-header .user-button { 
            background-color: var(--white-color); color: var(--primary-color); border: none; 
            border-radius: 999px; padding: 0.5rem 1.5rem; font-weight: 600; 
            transition: transform 0.2s ease;
        }
        .user-button:hover { transform: scale(1.05); }
        .main-container { padding: 1.5rem; }
        .content-title {
            font-size: 1.75rem; font-weight: 700;
            color: var(--primary-color); margin-bottom: 2rem;
        }
        .stats-grid {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem; margin-bottom: 2.5rem;
        }
        .stat-card {
            background-color: var(--white-color); border-radius: 12px; padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.07); text-decoration: none;
            color: var(--text-primary); transition: all 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(86,185,241,0.2);
        }
        .stat-card .amount { font-size: 1.5rem; font-weight: 700; margin-bottom: 0.25rem; }
        .stat-card .label { font-size: 0.9rem; color: var(--text-secondary); }
        .content-container {
            background-color: var(--white-color); padding: 2rem;
            border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.07);
        }
        .filter-form {
            display: flex; gap: 1rem; align-items: flex-end;
            margin-bottom: 2rem; flex-wrap: wrap;
        }
        .filter-form .form-group { flex: 1; min-width: 150px; }
        .filter-form label { font-weight: 500; margin-bottom: 0.5rem; display: block; }
        .filter-form .btn-primary {
            background-color: var(--primary-color); border-color: var(--primary-color);
            transition: background-color 0.2s ease, border-color 0.2s ease;
        }
        .filter-form .btn-primary:hover {
            background-color: #3C9FDA; border-color: #3C9FDA;
        }
        .table-wrapper { overflow-x: auto; }
        .custom-table { width: 100%; border-collapse: collapse; }
        .custom-table thead { background-color: var(--dark-color); color: var(--white-color); }
        .custom-table th, .custom-table td {
            padding: 1rem; vertical-align: middle;
            border-bottom: 1px solid var(--border-color);
        }
        .custom-table tbody tr { transition: background-color 0.2s ease; }
        .custom-table tbody tr:hover { background-color: #f5f5f5; }

        /* LANGKAH 2: DITAMBAHKAN - Gaya CSS untuk Tombol Aksi Baru */
        .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            border: none;
            border-radius: 8px; /* Membuat sudut lebih rounded */
            font-size: 18px;   /* Ukuran ikon */
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .action-btn:hover {
            transform: scale(1.1); /* Efek zoom saat disentuh mouse */
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .action-btn-edit {
            background-color: var(--edit-bg-color);
            color: var(--edit-icon-color);
        }
        .action-btn-delete {
            background-color: var(--delete-bg-color);
            color: var(--delete-icon-color);
        }
    </style>
</head>
<body>
    <header class="page-header">
        <div class="header-title">
            <a href="{{ route('dashboard') }}">Kas Foerda</a>
        </div>
        <button class="user-button">User</button>
    </header>
    
    <main class="main-container">
        <h1 class="content-title">Data Pengeluaran</h1>

        <div class="stats-grid">
            <div class="stat-card">
                <a href="{{ route('pemasukan') }}">
                    <div class="amount">IDR. {{ number_format($totalPemasukan ?? 0, 0, ',', '.') }}</div>
                    <div class="label">Pemasukan</div>
                </a>
            </div>
            <div class="stat-card">
                <a href="{{ route('pengeluaran') }}">
                    <div class="amount">IDR. {{ number_format($totalPengeluaran ?? 0, 0, ',', '.') }}</div>
                    <div class="label">Pengeluaran</div>
                </a>
            </div>
            <div class="stat-card">
                <div class="amount">IDR. {{ number_format($totalKas ?? 0, 0, ',', '.') }}</div>
                <div class="label">Total Kas</div>
            </div>
            <div class="stat-card">
                <a href="{{ route('utang') }}">
                    <div class="amount">Utang</div>
                    <div class="label">Foerda Jaya!!!</div>
                </a>
            </div>
        </div>
        
        <div class="content-container">
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

            <form method="GET" action="{{ route('pengeluaran') }}" class="filter-form">
                <div class="form-group">
                    <label for="bulan">Pilih Bulan</label>
                    <select name="bulan" id="bulan" class="form-select" required @if ($dropdownBulan->isEmpty()) disabled @endif>
                        @forelse ($dropdownBulan as $key => $value)
                            <option value="{{ $key }}" {{ $key == $bulan ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                        @empty
                            <option value="">Tidak ada data</option>
                        @endforelse
                    </select>
                </div>
                <div class="form-group">
                    <label for="tahun">Tahun</label>
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

            <div class="table-wrapper">
                <table class="table custom-table">
                    <thead>
                        <tr>
                            <th>Nama Pengeluaran</th>
                            <th>Jumlah</th>
                            <th>Tanggal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pengeluaran as $item)
                            <tr>
                                <td>{{ $item->nama }}</td>
                                <td>IDR. {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d F Y') }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('pengeluaran.edit', $item->id) }}" class="action-btn action-btn-edit" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('pengeluaran.destroy', $item->id) }}" method="POST" class="m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-btn action-btn-delete" title="Hapus" onclick="return confirm('Yakin ingin menghapus data ini?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center p-4 text-secondary">Tidak ada data pengeluaran untuk periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 d-flex justify-content-between align-items-center">
                <div>
                    <!-- <strong class="fs-5">Total Pengeluaran Bulan Ini: IDR. {{ number_format($totalPengeluaran, 0, ',', '.') }}</strong> -->
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('pengeluaran.create') }}" class="btn btn-success">Tambah Pengeluaran</a>
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">Kembali ke Dashboard</a>
                </div>
            </div>

        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>