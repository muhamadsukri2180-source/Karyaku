<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Banding Akun - Karyaku</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f1f5f9; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #1e293b; -webkit-font-smoothing: antialiased;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f1f5f9; padding: 30px 15px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width: 600px; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.06); border: 1px solid #e2e8f0;">
                    
                    <!-- Header Banner -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); padding: 32px 28px; text-align: center;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td align="center">
                                        <div style="display: inline-block; background: #ffffff; padding: 8px 18px; border-radius: 30px; margin-bottom: 12px;">
                                            <span style="font-weight: 800; font-size: 18px; color: #0284c7; letter-spacing: 0.5px;">KARYAKU</span>
                                        </div>
                                        <h1 style="margin: 0; color: #ffffff; font-size: 20px; font-weight: 700; line-height: 1.3;">
                                            Pengajuan Banding Penangguhan Akun
                                        </h1>
                                        <p style="margin: 6px 0 0 0; color: #e0f2fe; font-size: 13px;">
                                            Pemberitahuan resmi pengajuan pembelaan / pemulihan akun pengguna
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 28px;">
                            
                            <!-- Alert Notice -->
                            <div style="background-color: #eff6ff; border-left: 4px solid #0284c7; border-radius: 8px; padding: 14px 16px; margin-bottom: 24px;">
                                <p style="margin: 0; font-size: 13px; color: #1e40af; line-height: 1.5;">
                                    <strong>Halo Tim Admin &amp; CS Karyaku,</strong><br>
                                    Terdapat pengajuan banding penangguhan akun baru yang masuk melalui sistem. Berikut adalah rincian lengkapnya:
                                </p>
                            </div>

                            <!-- Detail Pengguna -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin-bottom: 20px; border-collapse: separate; border-spacing: 0;">
                                <tr>
                                    <td colspan="2" style="padding-bottom: 10px; font-size: 14px; font-weight: 700; color: #0f172a; border-bottom: 2px solid #e2e8f0;">
                                        Informasi Akun Pengguna
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px 0; font-size: 13px; color: #64748b; width: 40%; border-bottom: 1px solid #f1f5f9;">ID Pengguna:</td>
                                    <td style="padding: 10px 0; font-size: 13px; color: #0f172a; font-weight: 600; border-bottom: 1px solid #f1f5f9;">#{{ $user->id_user ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px 0; font-size: 13px; color: #64748b; border-bottom: 1px solid #f1f5f9;">Nama / Username:</td>
                                    <td style="padding: 10px 0; font-size: 13px; color: #0f172a; font-weight: 600; border-bottom: 1px solid #f1f5f9;">{{ $user->name ?? 'Pengguna' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px 0; font-size: 13px; color: #64748b; border-bottom: 1px solid #f1f5f9;">Alamat Email:</td>
                                    <td style="padding: 10px 0; font-size: 13px; color: #0284c7; font-weight: 600; border-bottom: 1px solid #f1f5f9;">{{ $user->email ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px 0; font-size: 13px; color: #64748b; border-bottom: 1px solid #f1f5f9;">No. Telepon:</td>
                                    <td style="padding: 10px 0; font-size: 13px; color: #0f172a; font-weight: 600; border-bottom: 1px solid #f1f5f9;">{{ $user->phone ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px 0; font-size: 13px; color: #64748b; border-bottom: 1px solid #f1f5f9;">Alasan Penangguhan:</td>
                                    <td style="padding: 10px 0; font-size: 13px; color: #dc2626; font-weight: 600; border-bottom: 1px solid #f1f5f9;">
                                        {{ $user->suspend_reason ?? 'Pelanggaran aturan komunitas Karyaku' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px 0; font-size: 13px; color: #64748b; border-bottom: 1px solid #f1f5f9;">Durasi Sanksi:</td>
                                    <td style="padding: 10px 0; font-size: 13px; color: #475569; font-weight: 600; border-bottom: 1px solid #f1f5f9;">
                                        {{ $countdown['formatted'] ?? 'Permanen' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px 0; font-size: 13px; color: #64748b; border-bottom: 1px solid #f1f5f9;">Waktu Pengajuan:</td>
                                    <td style="padding: 10px 0; font-size: 13px; color: #475569; font-weight: 600; border-bottom: 1px solid #f1f5f9;">
                                        {{ now()->translatedFormat('d F Y, H:i') . ' WIB' }}
                                    </td>
                                </tr>
                            </table>

                            <!-- Alasan Pembelaan -->
                            <div style="margin-top: 24px; margin-bottom: 24px;">
                                <div style="font-size: 14px; font-weight: 700; color: #0f172a; margin-bottom: 10px;">
                                    Alasan Pembelaan / Permohonan dari Pengguna:
                                </div>
                                <div style="background-color: #f8fafc; border: 1px solid #cbd5e1; border-radius: 12px; padding: 16px; font-size: 13px; line-height: 1.6; color: #334155; white-space: pre-wrap;">{{ $userReason }}</div>
                            </div>

                            @if(!empty($imagePath))
                            <div style="background-color: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px; padding: 12px 16px; margin-bottom: 24px; font-size: 12px; color: #166534;">
                                <strong>Bukti Gambar Terlampir:</strong> File screenshot / bukti pendukung telah disertakan pada lampiran email ini.
                            </div>
                            @endif

                            <!-- Tindakan Admin -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin-top: 28px;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ url('/admin/laporan') }}" style="display: inline-block; background-color: #0284c7; color: #ffffff; text-decoration: none; font-size: 14px; font-weight: 700; padding: 14px 28px; border-radius: 10px; box-shadow: 0 4px 12px rgba(2, 132, 199, 0.3);">
                                            Buka Panel Admin &amp; Tindak Banding
                                        </a>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 20px 28px; text-align: center;">
                            <p style="margin: 0; font-size: 12px; color: #94a3b8; line-height: 1.5;">
                                Email ini dibuat secara otomatis oleh sistem keamanan <strong>Karyaku Platform</strong>.<br>
                                Hubungi kami di <a href="mailto:karyakuustore@gmail.com" style="color: #0284c7; text-decoration: none; font-weight: 600;">karyakuustore@gmail.com</a> jika butuh bantuan lebih lanjut.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
