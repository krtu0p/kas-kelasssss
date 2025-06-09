<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Utang Siswa - Kas Foerda</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        /* (Semua CSS sama seperti kode lama Anda) */
        :root {
            --primary-color: #56B9F1;
            --white-color: #FFFFFF;
            --dark-color: #212529;
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
        }

        .page-header .header-title {
            font-size: 1.5rem;
            font-weight: 700;
        }

        .page-header .header-title a {
            text-decoration: none;
            color: var(--white-color);
        }

        .main-container {
            padding: 1.5rem;
        }

        .content-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 2rem;
        }

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
            box-shadow: 0 8px 20px rgba(86, 185, 241, 0.2);
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

        .table-container {
            background-color: var(--white-color);
            padding: 2rem;
            border-radius: 16px;
            margin-top: 2.5rem;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.08);
        }

        .filter-form {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: flex-end;
            margin-bottom: 2rem;
        }

        .filter-form .form-group {
            flex-grow: 1;
        }

        .filter-form .form-control,
        .filter-form .form-select {
            border-radius: 8px;
            border: 1px solid #D1D5DB;
            padding: 0.6rem 1rem;
            background-color: #F9FAFB;
        }

        .filter-form .form-control:focus,
        .filter-form .form-select:focus {
            box-shadow: 0 0 0 2px rgba(86, 185, 241, 0.3);
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

        .table-wrapper {
            border-radius: 12px;
            overflow-x: auto;
            border: 1px solid var(--border-color);
        }

        .payment-table {
            width: 100%;
            min-width: 600px;
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
            white-space: nowrap;
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
    </style>
</head>

<body>
    <header class="page-header">
        <div class="header-title">
            {{-- PERUBAHAN: Mengarah ke halaman utama siswa --}}
            <a href="{{ route('siswa.index') }}">Kas Foerda</a>
        </div>
        {{-- Tombol Logout & Profil Admin Dihapus --}}
    </header>

    <main class="main-container">
        <h1 class="content-title">Data Utang Siswa</h1>

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
                {{-- PERUBAHAN: Mengarah ke rute siswa --}}
                <a href="{{ route('utang.siswa') }}">
                    <div class="amount">Utang</div>
                    <div class="label">Lihat Data Utang</div>
                </a>
            </div>
        </div>

        <div class="table-container">
            {{-- PERUBAHAN: Action form mengarah ke rute siswa --}}
            <form method="GET" action="{{ route('utang.siswa') }}" class="filter-form">
                <div class="form-group">
                    <label for="bulan">Pilih Bulan</label>
                    <select name="bulan" id="bulan" class="form-select">
                        <option value="">Semua Bulan</option>
                        @foreach ($dropdownBulan as $key => $namaBulan)
                        <option value="{{ $key }}" {{ $bulan == $key ? 'selected' : '' }}>
                            {{ $namaBulan }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="tahun">Tahun</label>
                    <select name="tahun" id="tahun" class="form-select" required>
                        @foreach ($yearRange as $year)
                        <option value="{{ $year }}" {{ $tahun == $year ? 'selected' : '' }}>{{ $year }}</option>
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
                            <th>No</th>
                            <th>Nama Siswa</th>
                            {{-- Kolom jumlah minggu sengaja dihilangkan untuk view publik --}}
                            <th>Total Utang (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($utangData as $index => $utang)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $utang['nama'] }}</td>
                            <td>{{ number_format($utang['total_utang'], 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            {{-- PERUBAHAN: colspan menjadi 3 --}}
                            <td colspan="3" class="text-center p-4">Alhamdulillah! Tidak ada yang punya utang untuk periode ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- Tombol kembali ke dashboard dihapus untuk view publik --}}
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>