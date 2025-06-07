<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Keuangan Bulanan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 2rem;
            font-family: 'Segoe UI', sans-serif;
            background: #f5f6fa;
            display: flex;
            gap: 2rem;
        }

        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .filters {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        select, button {
            padding: 0.7rem 1.2rem;
            border: none;
            border-radius: 999px;
            background: white;
            font-weight: bold;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
            cursor: pointer;
        }

        button {
            background-color: #007bff;
            color: white;
        }

        .card {
            background: #fff;
            border-radius: 1.5rem;
            padding: 1.2rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
        }

        .card .info {
            font-weight: bold;
            font-size: 1.2rem;
            color: #000;
        }

        .card .sub {
            font-weight: normal;
            font-size: 0.95rem;
            color: #444;
        }

        .card .actions {
            display: flex;
            gap: 0.7rem;
        }

        .action-icon {
            padding: 0.5rem;
            border-radius: 0.7rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .edit {
            background: #fff3cd;
            color: #ffc107;
        }

        .delete {
            background: #fdecea;
            color: #dc3545;
        }

        .sidebar {
            width: 300px;
            background: white;
            border-radius: 1.5rem;
            padding: 1rem;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .chart-container {
            width: 100%;
            height: 100%;
        }

        canvas {
            max-width: 100%;
            height: 300px !important;
        }
    </style>
</head>
<body>
    <div class="main-content">
        <div class="filters">
            <select>
                <option>Bulan</option>
                <option>Januari</option>
                <option>Februari</option>
                <!-- Tambahkan opsi lain -->
            </select>
            <select>
                <option>Tahun</option>
                <option>2024</option>
                <option>2025</option>
            </select>
            <button>Submit</button>
        </div>

        <!-- Contoh 3 kartu dummy -->
        <div class="card">
            <div>
                <div class="info">IDR. 10.000</div>
                <div class="sub">Judul pemasukan</div>
            </div>
            <div class="actions">
                <div class="action-icon edit">✏️</div>
                <div class="action-icon delete">🗑️</div>
            </div>
        </div>
        <div class="card">
            <div>
                <div class="info">IDR. 10.000</div>
                <div class="sub">Judul pemasukan</div>
            </div>
            <div class="actions">
                <div class="action-icon edit">✏️</div>
                <div class="action-icon delete">🗑️</div>
            </div>
        </div>
        <div class="card">
            <div>
                <div class="info">IDR. 10.000</div>
                <div class="sub">Judul pemasukan</div>
            </div>
            <div class="actions">
                <div class="action-icon edit">✏️</div>
                <div class="action-icon delete">🗑️</div>
            </div>
        </div>
    </div>

    <!-- SIDEBAR CHART -->
    <div class="sidebar">
        <div class="chart-container">
            <canvas id="keuanganChart"></canvas>
        </div>
    </div>

    <script>
    const ctx = document.getElementById('keuanganChart').getContext('2d');

    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($labels), // ['Pemasukan', 'Pengeluaran', 'Total Kas']
            datasets: [
                {
                    label: 'Jumlah',
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(153, 102, 255, 0.7)'
                    ],
                    data: [
                        @json($pemasukanData[0]),
                        @json($pengeluaranData[0]),
                        @json($totalKasData[0])
                    ],
                    borderRadius: 6
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
</script>
</body>
</html>
