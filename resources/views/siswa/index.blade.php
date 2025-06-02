<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Data Kas Kelas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">
    <div class="container py-5">
        <h2 class="mb-4">Data Pembayaran Kas Kelas</h2>

        <div class="mb-4">
            <a href="{{ route('dashboard') }}" class="btn btn-primary">
                Kembali ke Dashboard
            </a>
        </div>

        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Nama Siswa</th>
                    @for ($i = 1; $i <= $maxMinggu; $i++)
                        <th>M{{ $i }}</th>
                    @endfor
                </tr>
            </thead>
            <tbody>
                @foreach ($siswas as $siswa)
                    <tr>
                        <td>{{ $siswa->nama }}</td>
                        @for ($i = 1; $i <= $maxMinggu; $i++)
                            @php
                                $status = $siswa->pembayaran->firstWhere('minggu_ke', $i)->status ?? false;
                            @endphp
                            <td>{!! $status ? '✅' : '❌' !!}</td>
                        @endfor
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
