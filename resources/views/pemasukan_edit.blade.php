<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pemasukan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container {
            max-width: 600px;
        }
    </style>
</head>

<body class="bg-light">
    <div class="container py-5">
        <h2 class="mb-4">Edit Pemasukan</h2>

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('pemasukan.update', $pemasukan->id) }}" class="form-container">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Pemasukan</label>
                <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama', $pemasukan->nama) }}" required>
            </div>
            <div class="mb-3">
                <label for="jumlah" class="form-label">Jumlah (Rp)</label>
                <input type="number" name="jumlah" id="jumlah" class="form-control" value="{{ old('jumlah', $pemasukan->jumlah) }}" step="0.01" required>
            </div>
            <div class="mb-3">
                <label for="tanggal" class="form-label">Tanggal</label>
                <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ old('tanggal', $pemasukan->tanggal->format('Y-m-d')) }}" required onchange="updateMonthYear()">
            </div>
            <input type="hidden" name="bulan" id="bulan_hidden">
            <input type="hidden" name="tahun" id="tahun_hidden">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('pemasukan') }}" class="btn btn-secondary">Batal</a>
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