<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pengeluaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container { max-width: 600px; }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <h2 class="mb-4">Tambah Pengeluaran</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('pengeluaran.store') }}" class="form-container" onsubmit="return confirm('Yakin ingin menyimpan pengeluaran ini?')">
            @csrf
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Pengeluaran</label>
                <input type="text" name="nama" id="nama" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="jumlah" class="form-label">Jumlah (Rp)</label>
                <input type="number" name="jumlah" id="jumlah" class="form-control" step="0.01" required>
            </div>
            <div class="mb-3">
                <label for="tanggal" class="form-label">Tanggal</label>
                <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ now()->format('Y-m-d') }}" required onchange="updateMonthYear()">
            </div>
            <input type="hidden" name="bulan" id="bulan_hidden">
            <input type="hidden" name="tahun" id="tahun_hidden">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('pengeluaran') }}" class="btn btn-secondary" onclick="return confirm('Yakin ingin membatalkan? Data yang belum disimpan akan hilang.')">Batal</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function updateMonthYear() {
            const tanggalInput = document.getElementById('tanggal').value;
            if (tanggalInput) {
                const date = new Date(tanggalInput);
                const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are 0-based
                const year = date.getFullYear();

                document.getElementById('bulan_hidden').value = month;
                document.getElementById('tahun_hidden').value = year;
            }
        }

        // Trigger update on page load
        window.onload = function() {
            updateMonthYear();
        };
    </script>
</body>
</html>