<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pengeluaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <h2 class="mb-4">Edit Pengeluaran</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('pengeluaran.update', $pengeluaran->id) }}">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Pengeluaran</label>
                <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama', $pengeluaran->nama) }}" required>
            </div>
            <div class="mb-3">
                <label for="jumlah" class="form-label">Jumlah (Rp)</label>
                <input type="number" name="jumlah" id="jumlah" class="form-control" value="{{ old('jumlah', $pengeluaran->jumlah) }}" step="0.01" required>
            </div>
            <div class="mb-3">
                <label for="tanggal" class="form-label">Tanggal</label>
                <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ old('tanggal', $pengeluaran->tanggal->format('Y-m-d')) }}" required onchange="updateMonthYear()">
            </div>
            <input type="hidden" name="bulan" id="bulan_hidden">
            <input type="hidden" name="tahun" id="tahun_hidden">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('pengeluaran') }}" class="btn btn-secondary">Batal</a>
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

        // Trigger update on page load with the initial date
        window.onload = function() {
            updateMonthYear();
        };
    </script>
</body>
</html>