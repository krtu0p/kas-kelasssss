<!DOCTYPE html>
<html>
<head>
    <title>Chart Keuangan Bulanan</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h2>Chart Keuangan Bulanan</h2>
    <canvas id="keuanganChart" width="600" height="300"></canvas>

    <script>
        const ctx = document.getElementById('keuanganChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($labels),
                datasets: [
                    {
                        label: 'Pemasukan',
                        borderColor: 'green',
                        backgroundColor: 'rgba(0, 128, 0, 0.1)',
                        data: @json($pemasukanData),
                        tension: 0.3
                    },
                    {
                        label: 'Pengeluaran',
                        borderColor: 'red',
                        backgroundColor: 'rgba(255, 0, 0, 0.1)',
                        data: @json($pengeluaranData),
                        tension: 0.3
                    },
                    {
                        label: 'Total Kas',
                        borderColor: 'blue',
                        backgroundColor: 'rgba(0, 0, 255, 0.1)',
                        data: @json($totalKasData),
                        tension: 0.3
                    }
                ]
            },
            options: {
                responsive: true,
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
