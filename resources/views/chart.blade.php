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

        .sidebar {
            width: 380px;
            height: 450px;
            /* ✅ Tambahkan tinggi sidebar */
            background: white;
            border-radius: 1.5rem;
            padding: 1.5rem;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .chart-container {
            width: 450px height: 450px;
            position: relative;
        }

        canvas {
            max-width: 100%;
            height: 100% !important;
            /* ✅ Lebihkan tinggi canvas */
        }
    </style>
</head>

<body>
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
                datasets: [{
                    label: 'Jumlah',
                    backgroundColor: [
                        'rgb(23, 255, 147)',
                        'rgb(255, 40, 40)',
                        'rgba(140, 82, 255, 0.7)'
                    ],
                    data: [
                        @json($pemasukanData[0]),
                        @json($pengeluaranData[0]),
                        @json($totalKasData[0])
                    ],
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
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