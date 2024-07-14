<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link href="/assets/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        #context-menu {
            display: none;
            position: absolute;
            z-index: 1000;
            background: white;
            border: 1px solid #ccc;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
        }

        html,
        body {
            overflow: hidden;
            font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
            font-size: 14px;
        }

        #calendar-container {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            margin: 10px;
        }

        .fc-header-toolbar {
            padding-top: 1em;
            padding-left: 1em;
            padding-right: 1em;
        }
    </style>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @auth
        @if (auth()->user()->role === 'admin')
            @vite(['resources/js/v1/fullcalendar.js'])
        @else
            @vite(['resources/js/user/fullcalendar.js'])
        @endif
    @endauth
</head>

<body>
    <div id="calendar-container">
        <div id="calendar"></div>
    </div>

    <div class="modal fade" id="tambahUbahJadwalModal" tabindex="-1" aria-labelledby="tambahUbahJadwalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="tambahUbahJadwalLabel">Tambah Jadwal</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="modal-body was-validated">
                    <div>
                        <div id="alertMessage"></div>
                        <div class="mb-3">
                            <label for="nama-jadwal" class="form-label">Nama Jadwal</label>
                            <input type="text" class="form-control" id="nama-jadwal"
                                placeholder="Masukkan nama jadwal" required>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" role="switch" id="datetime">
                            <label class="form-check-label" for="datetime">Datetime</label>
                        </div>
                        <div class="mb-3">
                            <label for="tanggal-mulai" class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control" id="tanggal-mulai" required>
                        </div>
                        <div class="mb-3">
                            <label for="tanggal-akhir" class="form-label">Tanggal akhir <small
                                    class="text-muted">(Optional)</small></label>
                            <input type="date" class="form-control" id="tanggal-akhir">
                        </div>
                        <div class="mb-3">
                            <label for="link" class="form-label">Link <small
                                    class="text-muted">(Optional)</small></label>
                            <input type="url" class="form-control" id="link">
                        </div>
                        <div class="mb-3">
                            <label for="warna-jadwal" class="form-label">Warna Jadwal</label>
                            <input type="color" class="form-control" id="warna-jadwal" value="#3788d8">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Catatan <small
                                    class="text-muted">(Optional)</small></label>
                            <textarea class="form-control" id="description" style="height: 100px; min-height: 100px;"></textarea>
                        </div>
                    </div>
                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-outline-success" id="simpan-ubah-jadwal">Simpan Jadwal</button>
                </div>
            </div>
        </div>
    </div>

    <script src="/assets/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
