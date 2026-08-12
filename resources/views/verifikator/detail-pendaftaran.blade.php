<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Detail Pendaftaran Penjual</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background: #f5f7fb;
            padding: 30px;
        }

        .container {
            max-width: 900px;
            margin: auto;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,.08);
        }

        .row {
            padding: 13px 0;
            border-bottom: 1px solid #eee;
        }

        .label {
            font-weight: bold;
        }

        input,
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-top: 8px;
        }

        textarea {
            min-height: 120px;
        }

        .btn {
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            color: white;
            cursor: pointer;
            font-weight: bold;
        }

        .approve {
            background: #16a34a;
        }

        .reject {
            background: #dc2626;
        }

        .proof {
            max-width: 500px;
            width: 100%;
            margin-top: 15px;
            border-radius: 10px;
        }

    </style>

</head>

<body>

<div class="container">

    <div class="card">

        <h1>
            Detail Pendaftaran
        </h1>


        <div class="row">

            <div class="label">
                Nama
            </div>

            {{ $registration->user->name }}

        </div>


        <div class="row">

            <div class="label">
                Email
            </div>

            {{ $registration->user->email }}

        </div>


        <div class="row">

            <div class="label">
                No. Telepon
            </div>

            {{ $registration->user->phone }}

        </div>


        <div class="row">

            <div class="label">
                NIK
            </div>

            {{ $registration->nik }}

        </div>


        <div class="row">

            <div class="label">
                Alamat
            </div>

            {{ $registration->address }}

        </div>


        <div class="row">

            <div class="label">
                Bank
            </div>

            {{ $registration->bank_name }}

        </div>


        <div class="row">

            <div class="label">
                Nama Rekening
            </div>

            {{ $registration->account_name }}

        </div>


        <div class="row">

            <div class="label">
                Nomor Rekening
            </div>

            {{ $registration->account_number }}

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
                Bukti Pembayaran
            </div>

            @if ($registration->payment_proof)

                <img
                    src="{{ asset('storage/' . $registration->payment_proof) }}"
                    class="proof"
                    alt="Bukti pembayaran"
                >

            @else

                Tidak ada bukti pembayaran.

            @endif

        </div>

    </div>


    @if ($registration->status !== 'approved')

        <div class="card">

            <h2>
                Verifikasi
            </h2>


            <form
                action="{{ route(
                    'verifikator.pendaftaran.approve',
                    $registration->id_identity_verification
                ) }}"
                method="POST"
            >

                @csrf

                <label>
                    Catatan
                </label>

                <textarea
                    name="notes"
                    placeholder="Catatan verifikator..."
                ></textarea>


                <br><br>

                <button
                    type="submit"
                    class="btn approve"
                    onclick="return confirm('Yakin ingin menyetujui pendaftaran ini?')"
                >
                    ✓ Setujui Pendaftaran
                </button>

            </form>


            <br>


            <form
                action="{{ route(
                    'verifikator.pendaftaran.reject',
                    $registration->id_identity_verification
                ) }}"
                method="POST"
            >

                @csrf

                <label>
                    Alasan Penolakan
                </label>

                <textarea
                    name="notes"
                    required
                    placeholder="Masukkan alasan penolakan..."
                ></textarea>


                <br><br>

                <button
                    type="submit"
                    class="btn reject"
                    onclick="return confirm('Yakin ingin menolak pendaftaran ini?')"
                >
                    ✕ Tolak Pendaftaran
                </button>

            </form>

        </div>

    @else

        <div class="card">

            <h2>
                ✓ Pendaftaran Sudah Disetujui
            </h2>

            <p>
                Pendaftaran ini sudah disetujui dan akun pengguna sudah menjadi penjual.
            </p>

        </div>

    @endif

</div>

</body>

</html>
