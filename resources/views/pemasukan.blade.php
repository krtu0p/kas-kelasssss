<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemasukan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table-responsive { max-height: 500px; overflow-y: auto; }
        .form-container { max-width: 600px; }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <h2 class="mb-4">Pemasukan</h2>

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Form Pilih Bulan & Tahun -->
        <form method="GET" action="{{ route('pemasukan') }}" class="mb-4 form-container d-flex gap-3 align-items-end">
            <div class="flex-fill">
                <label for="bulan" class="form-label">Pilih Bulan</label>
                <select name="bulan" id="bulan" class="form-select" required @if ($dropdownBulan->isEmpty()) disabled @endif>
                    @if ($dropdownBulan->isEmpty())
                        <option value="">Tidak ada data untuk tahun ini</option>
                    @else
                        @foreach ($dropdownBulan as $key => $value)
                            <option value="{{ $key }}" {{ $key == $bulan ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="flex-fill">
                <label for="tahun" class="form-label">Tahun</label>
                <select name="tahun" id="tahun" class="form-select" required onchange="this.form.submit()">
                    @foreach ($yearRange as $year)
                        <option value="{{ $year }}" {{ $year == $tahun ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <button type="submit" class="btn btn-primary" @if ($dropdownBulan->isEmpty()) disabled @endif>Lihat</button>
            </div>
        </form>

        <!-- Table or Other Content -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Nama</th>
                        <th>Jumlah</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pemasukan as $item)
                        <tr>
                            <td>{{ $item->nama }}</td>
                            <td>{{ number_format($item->jumlah, 2) }}</td>
                            <td>{{ $item->tanggal->format('d-m-Y') }}</td>
                            <td>
                                <!-- <a href="{{ route('pemasukan.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a> -->
                                 <button type="button" class="btn btn-warning btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditPemasukan"
                                    data-id="{{ $item->id }}"
                                    data-nama="{{ $item->nama }}"
                                    data-jumlah="{{ $item->jumlah }}"
                                    data-tanggal="{{ $item->tanggal->format('Y-m-d') }}">
                                    Edit
                                </button>
                                <form action="{{ route('pemasukan.destroy', $item->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada data pemasukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            <p>Total Pemasukan:  Rp. {{ number_format($totalPemasukan, 2) }}</p>
        </div>
        <!-- <a href="{{ route('pemasukan.create') }}" class="btn btn-success mt-3">Tambah Pemasukan</a> -->
         <!-- Ganti dengan tombol trigger modal -->
            <button type="button" class="btn btn-success mt-3" data-bs-toggle="modal" data-bs-target="#modalTambahPemasukan">
                Tambah Pemasukan
            </button>
        <div class="mt-4">
            <a href="{{ route('dashboard') }}" class="btn btn-primary">Kembali ke Dashboard</a>
        </div>
    </div>

    <!-- Modal Tambah Pemasukan -->
    <div class="modal fade" id="modalTambahPemasukan" tabindex="-1" aria-labelledby="modalTambahPemasukanLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-light shadow">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="modalTambahPemasukanLabel">Tambah Pemasukan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <form method="POST" action="{{ route('pemasukan.store') }}" onsubmit="return confirm('Yakin ingin menyimpan pemasukan ini?')">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="modal_nama" class="form-label">Nama Pemasukan</label>
                            <input type="text" name="nama" id="modal_nama" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="modal_jumlah" class="form-label">Jumlah (Rp)</label>
                            <input type="number" name="jumlah" id="modal_jumlah" class="form-control" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label for="modal_tanggal" class="form-label">Tanggal</label>
                            <input type="date" name="tanggal" id="modal_tanggal" class="form-control" value="{{ now()->format('Y-m-d') }}" required onchange="updateModalMonthYear()">
                        </div>
                        <input type="hidden" name="bulan" id="modal_bulan_hidden">
                        <input type="hidden" name="tahun" id="modal_tahun_hidden">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Pemasukan -->
    <div class="modal fade" id="modalEditPemasukan" tabindex="-1" aria-labelledby="modalEditPemasukanLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-light shadow">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="modalEditPemasukanLabel">Edit Pemasukan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <form id="formEditPemasukan" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_nama" class="form-label">Nama Pemasukan</label>
                            <input type="text" name="nama" id="edit_nama" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_jumlah" class="form-label">Jumlah (Rp)</label>
                            <input type="number" name="jumlah" id="edit_jumlah" class="form-control" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_tanggal" class="form-label">Tanggal</label>
                            <input type="date" name="tanggal" id="edit_tanggal" class="form-control" required onchange="updateEditModalMonthYear()">
                        </div>
                        <input type="hidden" name="bulan" id="edit_bulan_hidden">
                        <input type="hidden" name="tahun" id="edit_tahun_hidden">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-warning">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function updateModalMonthYear() {
            const tanggal = document.getElementById('modal_tanggal').value;
            if (tanggal) {
                const dateObj = new Date(tanggal);
                const bulan = String(dateObj.getMonth() + 1).padStart(2, '0');
                const tahun = dateObj.getFullYear();
                document.getElementById('modal_bulan_hidden').value = bulan;
                document.getElementById('modal_tahun_hidden').value = tahun;
            }
        }

        // Auto-update bulan/tahun saat modal dibuka
        const modalElement = document.getElementById('modalTambahPemasukan');
        modalElement.addEventListener('shown.bs.modal', function () {
            updateModalMonthYear();
        });

        function updateEditModalMonthYear() {
            const tanggal = document.getElementById('edit_tanggal').value;
            if (tanggal) {
                const dateObj = new Date(tanggal);
                const bulan = String(dateObj.getMonth() + 1).padStart(2, '0');
                const tahun = dateObj.getFullYear();
                document.getElementById('edit_bulan_hidden').value = bulan;
                document.getElementById('edit_tahun_hidden').value = tahun;
            }
        }

        // Saat modal edit dibuka, isi data
        document.getElementById('modalEditPemasukan').addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const nama = button.getAttribute('data-nama');
            const jumlah = button.getAttribute('data-jumlah');
            const tanggal = button.getAttribute('data-tanggal');

            document.getElementById('edit_nama').value = nama;
            document.getElementById('edit_jumlah').value = jumlah;
            document.getElementById('edit_tanggal').value = tanggal;

            // Update bulan dan tahun otomatis
            updateEditModalMonthYear();

            // Ganti action form
            const form = document.getElementById('formEditPemasukan');
            form.action = `/pemasukan/${id}`; // Pastikan route resource sesuai
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
