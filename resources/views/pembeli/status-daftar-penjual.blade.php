<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Status Pendaftaran Penjual</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background: #f5f7fb;
            margin: 0;
            padding: 40px 20px;
        }

        .container {
            max-width: 800px;
            margin: auto;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,.08);
        }

        .status {
            display: inline-block;
            padding: 8px 14px;
            border-radius: 20px;
            font-weight: bold;
        }

        .pending {
            background: #fef3c7;
            color: #92400e;
        }

        .approved {
            background: #dcfce7;
            color: #166534;
        }

        .rejected {
            background: #fee2e2;
            color: #991b1b;
        }

        .info {
            margin-top: 25px;
        }

        .row {
            padding: 12px 0;
            border-bottom: 1px solid #eee;
        }

        .label {
            font-weight: bold;
        }

        .btn {
            display: inline-block;
            margin-top: 25px;
            padding: 12px 18px;
            background: #4f46e5;
            color: white;
            text-decoration: none;
            border-radius: 8px;
        }

    </style>

</head>

<body>

<div class="container">

    <div class="card">

        <h1>Status Pendaftaran Penjual</h1>

        @if (!$registration)

            <p>
                Kamu belum pernah mengajukan pendaftaran sebagai penjual.
            </p>

            <a
                href="{{ route('pembeli.seller.registration.create') }}"
                class="btn"
            >
                Daftar Sebagai Penjual
            </a>

        @else

            @php
                $status = strtolower($registration->status);
            @endphp


            <p>

                Status:

                <span class="status {{ $status }}">
                    {{ ucfirst($status) }}
                </span>

            </p>


            <div class="info">

                <div class="row">

                    <div class="label">
                        Nama
                    </div>

                    {{ $registration->user->name }}

                </div>


                <div class="row">

                    <div class="label">
                        Paket
                    </div>

                    {{ $registration->membership->name ?? '-' }}

                </div>


                <div class="row">

                    <div class="label">
                        Nominal Pembayaran
                    </div>

                    Rp
                    {{ number_format($registration->payment_amount, 0, ',', '.') }}

                </div>


                <div class="row">

                    <div class="label">
                        Tanggal Pengajuan
                    </div>

                    {{ optional($registration->submitted_at)->format('d-m-Y H:i') }}

                </div>


                @if ($registration->notes)

                    <div class="row">

                        <div class="label">
                            Catatan Verifikator
                        </div>

                        {{ $registration->notes }}

                    </div>

                @endif


                @if ($registration->verified_at)

                    <div class="row">

                        <div class="label">
                            Tanggal Verifikasi
                        </div>

                        {{ $registration->verified_at->format('d-m-Y H:i') }}

                    </div>

                @endif

            </div>


            @if ($status === 'pending' || $status === 'processing')

                <p>
                    Pengajuan kamu sedang diperiksa oleh verifikator.
                    Silakan tunggu sampai proses verifikasi selesai.
                </p>

                <form
                    action="{{ route('pembeli.seller.registration.cancel') }}"
                    method="POST"
                    onsubmit="return confirm('Yakin ingin membatalkan pengajuan?')"
                >

                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="btn"
                    >
                        Batalkan Pengajuan
                    </button>

                </form>

            @elseif ($status === 'approved')

                <p>
                    🎉 Selamat! Pendaftaran kamu telah disetujui.
                    Sekarang akun kamu sudah menjadi penjual.
                </p>

                <a
                    href="{{ route('penjual.dashboard') }}"
                    class="btn"
                >
                    Masuk Dashboard Penjual
                </a>

            @elseif ($status === 'rejected')

                <p>
                    Pendaftaran kamu ditolak oleh verifikator.
                    Silakan periksa catatan verifikator dan lakukan pendaftaran kembali jika diperlukan.
                </p>

                <a
                    href="{{ route('pembeli.seller.registration.create') }}"
                    class="btn"
                >
                    Daftar Kembali
                </a>

            @endif

        @endif


            <a
            href="{{ route('pembeli.dashboard') }}"
            class="btn"
            >
            ← Kembali ke Dashboard Pembeli
            </a>


    </div>

</div>

</body>

</html>
