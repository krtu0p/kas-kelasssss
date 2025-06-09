<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemasukan Kas - Foerda</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Mengadopsi gaya dari File B untuk konsistensi */
        :root {
            --primary-color: #56B9F1;
            --dark-color: #121212;
            --light-dark-color: #1E1E1E;
            --white-color: #FFFFFF;
            --text-primary: #333333;
            --text-secondary: #757575;
            --border-color: #EEEEEE;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: var(--light-dark-color);
            /* Latar belakang gelap */
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

        .page-header .user-button {
            background-color: var(--white-color);
            color: var(--primary-color);
            border: none;
            border-radius: 999px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
        }

        /* PERUBAHAN: Menghilangkan grid, menggunakan padding seperti File B */
        .main-container {
            padding: 2rem;
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
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.08);
            text-decoration: none;
            color: var(--text-primary);
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

        /* Kontainer untuk filter dan daftar pemasukan */
        .pemasukan-container {
            background-color: var(--white-color);
            padding: 2rem;
            border-radius: 16px;
            margin-top: 2.5rem;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.08);
        }

        .filter-form {
            display: flex;
            gap: 1rem;
            align-items: center;
            margin-bottom: 2rem;
        }

        .filter-form .form-select-custom {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-color: #F3F4F6;
            border: 1px solid #E5E7EB;
            border-radius: 999px;
            padding: 0.5rem 2.5rem 0.5rem 1rem;
            font-weight: 500;
            color: #374151;
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.75rem center;
            background-repeat: no-repeat;
            background-size: 1.25em 1.25em;
        }

        .filter-form .btn-lihat {
            background-color: var(--primary-color);
            color: var(--white-color);
            border: none;
            border-radius: 999px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
        }

        .pemasukan-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .pemasukan-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.5rem;
            background-color: var(--white-color);
            border: 1px solid #E5E7EB;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.03);
            color: #333;
        }

        .pemasukan-item .info .amount {
            font-size: 1.25rem;
            font-weight: 600;
        }

        .pemasukan-item .info .title {
            font-size: 0.9rem;
            color: var(--text-secondary);
        }

        /* DITAMBAHKAN: Gaya untuk kontainer chart */
        .chart-container {
            background-color: var(--white-color);
            padding: 2rem;
            border-radius: 16px;
            margin-top: 2.5rem;
            /* Jarak dari panel pemasukan */
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.08);
            height: 450px;
            /* Memberi tinggi agar chart terlihat baik */
        }
    </style>
</head>

<body>

    <header class="page-header">
        <div class="header-title">Kas Foerda</div>
        <button class="user-button">User</button>
    </header>

    <main class="main-container">
        <h1 class="content-title">Pemasukan Kas</h1>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="amount">IDR. {{ number_format($totalPemasukan ?? 0, 0, ',', '.') }}</div>
                <div class="label">Pemasukan</div>
            </div>
            <div class="stat-card">
                <div class="amount">IDR. {{ number_format($totalPengeluaran ?? 0, 0, ',', '.') }}</div>
                <div class="label">Pengeluaran</div>
            </div>
            <div class="stat-card">
                <div class="amount">IDR. {{ number_format($totalKas ?? 0, 0, ',', '.') }}</div>
                <div class="label">Total Kas</div>
            </div>
            <div class="stat-card">
                <div class="amount">Daftar Siswa</div>
                <div class="label">Foerda Jaya!!!</div>
            </div>
        </div>

        <div class="pemasukan-container">
            <form method="GET" action="{{ route('pemasukan') }}" class="filter-form">
                <select name="bulan" id="bulan" class="form-select-custom">
                    @foreach ($dropdownBulan as $key => $value)
                    <option value="{{ $key }}" {{ $key == $bulan ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
                <select name="tahun" id="tahun" class="form-select-custom">
                    @foreach ($yearRange as $year)
                    <option value="{{ $year }}" {{ $year == $tahun ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-lihat">Lihat</button>
            </form>

            @if (session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

            <div class="pemasukan-list">
                @forelse ($pemasukan as $item)
                <div class="pemasukan-item">
                    <div class="info">
                        <div class="amount">IDR. {{ number_format($item->jumlah, 0, ',', '.') }}</div>
                        <div class="title">{{ $item->nama }} - {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</div>
                    </div>
                </div>
                @empty
                <div class="text-center p-5 text-secondary">Tidak ada data pemasukan untuk periode ini.</div>
                @endforelse
            </div>
        </div>

        <div class="chart-container">
            @include('chart')
        </div>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>