<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Verifikator - Pendaftaran Penjual</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background: #f5f7fb;
            margin: 0;
            padding: 30px;
        }

        .container {
            max-width: 1200px;
            margin: auto;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,.08);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 14px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }

        th {
            background: #f8fafc;
        }

        .status {
            padding: 6px 10px;
            border-radius: 20px;
            background: #fef3c7;
            color: #92400e;
        }

        .btn {
            display: inline-block;
            padding: 8px 12px;
            background: #4f46e5;
            color: white;
            text-decoration: none;
            border-radius: 7px;
        }

    </style>

</head>

<body>

<div class="container">

    <div class="card">

        <h1>
            Verifikasi Pendaftaran Penjual
        </h1>

        <table>

            <thead>

                <tr>

                    <th>
                        Nama
                    </th>

                    <th>
                        Email
                    </th>

                    <th>
                        Paket
                    </th>

                    <th>
                        Pembayaran
                    </th>

                    <th>
                        Status
                    </th>

                    <th>
                        Aksi
                    </th>

                </tr>

            </thead>

            <tbody>

            @forelse ($pending as $registration)

                <tr>

                    <td>
                        {{ $registration->user->name ?? '-' }}
                    </td>

                    <td>
                        {{ $registration->user->email ?? '-' }}
                    </td>

                    <td>
                        {{ $registration->membership->name ?? '-' }}
                    </td>

                    <td>
                        Rp
                        {{ number_format($registration->payment_amount, 0, ',', '.') }}
                    </td>

                    <td>

                        <span class="status">
                            {{ ucfirst($registration->status) }}
                        </span>

                    </td>

                    <td>

                        <a
                            href="{{ route(
                                'verifikator.pendaftaran.show',
                                $registration->id_identity_verification
                            ) }}"
                            class="btn"
                        >
                            Periksa
                        </a>

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="6">

                        Belum ada pendaftaran penjual yang perlu diverifikasi.

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>


        <div style="margin-top:20px;">

            {{ $pending->links() }}

        </div>

    </div>

</div>

</body>

</html>
