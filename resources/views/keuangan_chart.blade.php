<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Chart Keuangan Bulanan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f7fa;
            margin: 0;
            padding: 2rem;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }

        .card {
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
            padding: 2rem;
            width: 100%;
            max-width: 960px;
        }

        h2 {
            text-align: center;
            margin-bottom: 2rem;
            font-weight: 600;
            color: #333;
        }

        canvas {
            max-height: 400px;
        }
    </style>
</head>
<body>
    <div class="card">
        <h2>Chart Keuangan Bulanan</h2>
        <canvas id="keuanganChart"></canvas>
    </div>

    <script>
        const ctx = document.getElementById('keuanganChart').getContext('2d');

        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($labels),
                datasets: [
                    {
                        label: 'Pemasukan',
                        backgroundColor: 'rgba(75, 192, 192, 0.7)',
                        data: @json($pemasukanData),
                        borderRadius: 6
                    },
                    {
                        label: 'Pengeluaran',
                        backgroundColor: 'rgba(255, 99, 132, 0.7)',
                        data: @json($pengeluaranData),
                        borderRadius: 6
                    },
                    {
                        label: 'Total Kas',
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        data: @json($totalKasData),
                        borderRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            color: '#444',
                            font: {
                                size: 14
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: '#fff',
                        titleColor: '#000',
                        bodyColor: '#000',
                        borderColor: '#ccc',
                        borderWidth: 1,
                        padding: 10
                    }
                },
                layout: {
                    padding: 20
                },
                scales: {
                    x: {
                        ticks: {
                            color: '#666',
                            font: { size: 12 }
                        },
                        grid: { display: false }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#666',
                            font: { size: 12 },
                            callback: function(value) {
                                return 'Rp' + value.toLocaleString('id-ID');
                            }
                        },
                        grid: {
                            color: '#eee'
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
