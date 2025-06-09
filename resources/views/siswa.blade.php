<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kas Kelas</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* =================================
           Variabel & Gaya Global
        ==================================== */
        :root {
            --primary-color: #56B9F1;
            --dark-color: #121212;
            --light-dark-color: #1E1E1E; /* Warna latar belakang gelap */
            --white-color: #FFFFFF;
            --text-primary: #333333;
            --text-secondary: #757575;
            --green-color: #28a745;
            --red-color: #dc3545;
            --border-color: #EEEEEE;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            color: var(--text-primary);
        }

        a {
            text-decoration: none;
            color: var(--text-primary);
        }
        
        /* =================================
           Header
        ==================================== */
        .page-header {
            background-color: var(--primary-color);
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: var(--white-color);
        }
        .page-header .header-title {
            font-size: 1.5rem;
            font-weight: 700;
        }
        .page-header .header-title a{
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
            font-size: 0.9rem;
            transition: transform 0.2s ease;
        }
        .user-button:hover {
            transform: scale(1.05);
        }

        /* =================================
           Konten Utama
        ==================================== */
        .main-container {
            /* PERUBAHAN: Mengganti .main-content menjadi .main-container agar tidak bentrok */
            padding: 1.5rem;
        }

        .content-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 2rem;
        }

        /* =================================
           Kartu Statistik
        ==================================== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
        }
        .stat-card {
            background-color: var(--white-color);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1.5rem;
            transition: all 0.3s ease;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.08);
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(86,185,241,0.2);
        }
        .stat-card .amount {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }
        .stat-card .label {
            font-size: 0.9rem;
            color: var(--text-secondary);
        }

        /* DITAMBAHKAN: Gaya untuk Container Tabel & Filter */
        .table-container {
            background-color: var(--white-color);
            padding: 2rem;
            border-radius: 16px;
            margin-top: 2.5rem; /* Jarak dari kartu statistik di atas */
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.08);
        }

        /* =================================
           Form Filter
        ==================================== */
        .filter-form {
            display: flex;
            flex-wrap: wrap; /* Agar responsif di layar kecil */
            gap: 1rem;
            align-items: flex-end;
            margin-bottom: 2rem;
        }
        .filter-form .form-group {
            flex-grow: 1;
        }
        .filter-form .form-control, .filter-form .form-select {
            border-radius: 8px;
            border: 1px solid #D1D5DB;
            padding: 0.6rem 1rem;
            background-color: #F9FAFB;
        }
        .filter-form .form-control:focus, .filter-form .form-select:focus {
            box-shadow: 0 0 0 2px rgba(86,185,241,0.3);
            border-color: var(--primary-color);
        }
        .filter-form label {
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
            display: block;
        }
        .filter-form .btn-lihat {
            background-color: var(--primary-color);
            color: var(--white-color);
            border: none;
            border-radius: 8px;
            padding: 0.6rem 2rem;
            font-weight: 600;
            transition: background-color 0.2s ease;
        }
        .btn-lihat:hover {
            background-color: #3C9FDA;
        }
        
        /* =================================
           Tabel Pembayaran
        ==================================== */
        .table-wrapper {
            border-radius: 12px;
            overflow-x: auto; /* Agar tabel bisa di-scroll horizontal di layar kecil */
            border: 1px solid var(--border-color);
        }
        .payment-table {
            width: 100%;
            min-width: 600px; /* Lebar minimum agar tidak terlalu sempit */
            border-collapse: collapse;
        }
        .payment-table thead {
            background-color: var(--dark-color);
            color: var(--white-color);
        }
        .payment-table th, .payment-table td {
            padding: 1rem;
            text-align: left;
            vertical-align: middle;
            white-space: nowrap; /* Agar nama tidak terpotong */
        }
        .payment-table th:first-child, .payment-table td:first-child {
            padding-left: 1.5rem;
        }
        .payment-table tbody tr {
            border-bottom: 1px solid var(--border-color);
        }
        .payment-table tbody tr:last-child {
            border-bottom: none;
        }
        .payment-table tbody tr:hover {
            background-color: #F9FAFB;
        }
        .payment-table .text-center {
            text-align: center;
        }
        .status-icon {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .status-paid {
            color: var(--green-color);
        }
        .status-unpaid {
            color: var(--red-color);
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
        <h1 class="content-title">Data Pembayaran Kas</h1>

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
                <div class="amount">IDR. {{ number_format($totalKasData ?? 0, 0, ',', '.') }}</div>
                <div class="label">Total Kas</div>
            </div>
            <div class="stat-card">
                <a href="{{ route('utang') }}">
                    <div class="amount">Utang</div>
                    <div class="label">Foerda Jaya!!!</div>
                </a>
            </div>
        </div>

        <div class="table-container">

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            
            <form method="GET" action="{{ route('siswa.index') }}" class="filter-form">
                <div class="form-group">
                    <label for="bulan">Bulan</label>
                    <select name="bulan" id="bulan" class="form-select" required>
                        @foreach ($dropdownBulan as $item)
                            <option value="{{ $item['bulan'] }}" {{ $item['bulan'] == $bulan ? 'selected' : '' }}>
                                {{ $item['nama'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="tahun">Tahun</label>
                    <select name="tahun" id="tahun" class="form-select" required>
                         @foreach ($dropdownBulan->pluck('tahun')->unique()->sortDesc() as $thn)
                            <option value="{{ $thn }}" {{ $thn == $tahun ? 'selected' : '' }}>{{ $thn }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="submit" class="btn btn-lihat">Lihat</button>
                </div>
            </form>

            <div class="table-wrapper">
                <table class="payment-table">
                    <thead>
                        <tr>
                            <th scope="col" style="width: 5%;">No</th>
                            <th scope="col">Nama</th>
                            @for ($i = 1; $i <= $maxMinggu; $i++)
                                <th scope="col" class="text-center">M{{ $i }}</th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($siswas as $siswa)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $siswa->nama }}</td>
                                @for ($i = 1; $i <= $maxMinggu; $i++)
                                    <td class="text-center">
                                        @if ($siswa->pembayaran->firstWhere('minggu', $i)?->status)
                                            <span class="status-icon status-paid">✓</span>
                                        @else
                                            <span class="status-icon status-unpaid">✗</span>
                                        @endif
                                    </td>
                                @endfor
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $maxMinggu + 2 }}" class="text-center py-5">
                                    Tidak ada data siswa untuk bulan dan tahun yang dipilih.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 d-flex justify-content-end align-items-center">
                <div class="d-flex gap-2">
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">Kembali ke Dashboard</a>
                </div>
            </div>
        </div> 
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>