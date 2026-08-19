<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Daftar Sebagai Penjual - Karyaku</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f7fb;
            color: #1f2937;
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
        }

        .card {
            background: white;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,.08);
        }

        h1 {
            margin-top: 0;
        }

        .subtitle {
            color: #6b7280;
            margin-bottom: 30px;
        }

        .section {
            margin-top: 30px;
        }

        .section h2 {
            font-size: 20px;
            margin-bottom: 20px;
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        .full {
            grid-column: 1 / -1;
        }

        label {
            display: block;
            font-weight: 600;
            margin-bottom: 7px;
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            font-size: 14px;
        }

        textarea {
            min-height: 100px;
            resize: vertical;
        }

        .package {
            border: 2px solid #e5e7eb;
            padding: 18px;
            border-radius: 12px;
            margin-bottom: 15px;
        }

        .package:hover {
            border-color: #6366f1;
        }

        .package input {
            width: auto;
        }

        .package-title {
            font-size: 18px;
            font-weight: bold;
        }

        .price {
            color: #4f46e5;
            font-weight: bold;
            margin-top: 6px;
        }

        .benefit {
            color: #6b7280;
            margin-top: 8px;
            white-space: pre-line;
        }

        .payment-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
        }

        .bank-name {
            font-size: 20px;
            font-weight: bold;
        }

        .account-number {
            font-size: 25px;
            font-weight: bold;
            margin: 8px 0;
        }

        .warning {
            background: #fff7ed;
            border: 1px solid #fed7aa;
            padding: 15px;
            border-radius: 10px;
            margin-top: 15px;
        }

        .btn {
            border: none;
            background: #4f46e5;
            color: white;
            padding: 14px 22px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 15px;
            font-weight: bold;
        }

        .btn:hover {
            background: #4338ca;
        }

        .errors {
            background: #fee2e2;
            color: #991b1b;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .success {
            background: #dcfce7;
            color: #166534;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        @media(max-width: 700px) {
            .grid {
                grid-template-columns: 1fr;
            }

            .full {
                grid-column: auto;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <div class="card">

        <h1>Daftar Sebagai Penjual</h1>

        <p class="subtitle">
            Lengkapi data berikut dan lakukan pembayaran paket untuk mengajukan pendaftaran sebagai penjual Karyaku.
        </p>

        @if ($errors->any())
            <div class="errors">
                <strong>Periksa kembali data kamu:</strong>

                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="success">
                {{ session('success') }}
            </div>
        @endif


        <form
            action="{{ route('pembeli.seller.registration.store') }}"
            method="POST"
            enctype="multipart/form-data"
        >

            @csrf


            {{-- ==========================================
                 DATA DIRI
            ========================================== --}}

            <div class="section">

                <h2>1. Data Diri</h2>

                <div class="grid">

                    <div>
                        <label>Nama Lengkap</label>

                        <input
                            type="text"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            required
                        >
                    </div>


                    <div>
                        <label>Email</label>

                        <input
                            type="email"
                            name="email"
                            value="{{ old('email', $user->email) }}"
                            required
                        >
                    </div>


                    <div>
                        <label>NIK</label>

                        <input
                            type="text"
                            name="nik"
                            value="{{ old('nik') }}"
                            maxlength="50"
                            required
                        >
                    </div>


                    <div>
                        <label>No. Telepon</label>

                        <input
                            type="text"
                            name="phone"
                            value="{{ old('phone', $user->phone) }}"
                            required
                        >
                    </div>


                    <div class="full">
                        <label>Alamat</label>

                        <textarea
                            name="address"
                            required
                        >{{ old('address') }}</textarea>
                    </div>

                </div>

            </div>


            {{-- ==========================================
                 REKENING
            ========================================== --}}

            <div class="section">

                <h2>2. Data Rekening</h2>

                <div class="grid">

                    <div>
                        <label>Nama Bank</label>

                        <input
                            type="text"
                            name="bank_name"
                            value="{{ old('bank_name') }}"
                            placeholder="Contoh: BCA"
                            required
                        >
                    </div>


                    <div>
                        <label>Nama Pemilik Rekening</label>

                        <input
                            type="text"
                            name="account_name"
                            value="{{ old('account_name') }}"
                            required
                        >
                    </div>


                    <div class="full">
                        <label>Nomor Rekening</label>

                        <input
                            type="text"
                            name="account_number"
                            value="{{ old('account_number') }}"
                            required
                        >
                    </div>

                </div>

            </div>


            {{-- ==========================================
                 PAKET
            ========================================== --}}

            <div class="section">

                <h2>3. Pilih Paket Penjual</h2>

                @foreach ($memberships as $membership)

                    <label class="package">

                        <div>

                            <input
                                type="radio"
                                name="membership_id"
                                value="{{ $membership->id_membership }}"

                                @checked(
                                    old('membership_id') == $membership->id_membership ||
                                    (
                                        $selectedMembership &&
                                        $selectedMembership->id_membership == $membership->id_membership
                                    )
                                )

                                required
                            >

                            <span class="package-title">
                                {{ $membership->name }}
                            </span>

                        </div>

                        <div class="price">
                            Rp {{ number_format($membership->price, 0, ',', '.') }}
                        </div>

                        <div>
                            Durasi:
                            {{ $membership->duration_days }} hari
                        </div>

                        <div>
                            Maksimal upload:
                            {{ $membership->max_upload }} produk
                        </div>

                        <div class="benefit">
                            {{ $membership->benefit }}
                        </div>

                    </label>

                @endforeach

            </div>


            {{-- ==========================================
                 PEMBAYARAN
            ========================================== --}}

            <div class="section">

                <h2>4. Pembayaran</h2>

                <div class="payment-box">

                    <p>
                        Silakan transfer sesuai harga paket yang kamu pilih ke rekening resmi Karyaku.
                    </p>

                    <div class="bank-name">
                        BANK KARYAKU
                    </div>

                    <div class="account-number">
                        0862398284994
                    </div>

                    <div>
                        Atas Nama:
                        <strong>KARYAKU</strong>
                    </div>

                    <div class="warning">
                        <strong>Penting:</strong>

                        Pastikan nominal transfer sesuai dengan harga paket yang kamu pilih.
                        Setelah melakukan transfer, upload bukti pembayaran di bawah.
                    </div>

                </div>

            </div>


            {{-- ==========================================
                 BUKTI PEMBAYARAN
            ========================================== --}}

            <div class="section">

                <h2>5. Bukti Pembayaran</h2>

                <label>
                    Upload Bukti Transfer
                </label>

                <input
                    type="file"
                    name="payment_proof"
                    accept=".jpg,.jpeg,.png,.webp"
                    required
                >

                <small>
                    Format JPG, JPEG, PNG, atau WEBP. Maksimal 5 MB.
                </small>

            </div>


            {{-- ==========================================
                 SUBMIT
            ========================================== --}}

            <div class="section">

                <button
                    type="submit"
                    class="btn"
                >
                    Kirim Pendaftaran
                </button>

            </div>

        </form>

    </div>

</div>

</body>
</html>
