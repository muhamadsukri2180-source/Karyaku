<?php

namespace App\Http\Controllers;

use App\Models\IdentityVerification;
use App\Models\Membership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SellerRegistrationController extends Controller
{
    // ================= FORM PENDAFTARAN =================
    public function create(Request $request)
    {
        $user = Auth::user();

        // Jika sudah penjual, arahkan ke status / dashboard
        if (($user->role?->role_name ?? null) === 'penjual') {
            return redirect()->route('pembeli.seller.registration.status');
        }

        // Jika ada pendaftaran pending, arahkan ke status
        $pending = IdentityVerification::where('user_id', $user->id_user)
            ->where('status', 'pending')
            ->latest('id_identity_verification')
            ->first();

        if ($pending) {
            return redirect()->route('pembeli.seller.registration.status');
        }

        $memberships = Membership::orderBy('price', 'asc')->get();

        $selectedMembershipId = $request->query('membership');
        if ($selectedMembershipId && ! $memberships->contains('id_membership', (int) $selectedMembershipId)) {
            $selectedMembershipId = null;
        }

        if (! $selectedMembershipId && $memberships->isNotEmpty()) {
            $selectedMembershipId = $memberships->first()->id_membership;
        }

        $banks = ['BCA', 'BNI', 'BRI', 'Mandiri', 'CIMB Niaga', 'Permata', 'SeaBank', 'Bank Jago', 'BSI'];

        $paymentMethods = [
            'BCA'     => 'Transfer Bank BCA (0862398284994 a.n KARYAKU)',
            'BNI'     => 'Transfer Bank BNI (8820192019 a.n KARYAKU)',
            'Mandiri' => 'Transfer Bank Mandiri (137001928301 a.n KARYAKU)',
            'BRI'     => 'Transfer Bank BRI (0192019283019 a.n KARYAKU)',
            'QRIS'    => 'QRIS / All E-Wallet & Bank (Scan QR Karyaku)',
            'GOPAY'   => 'GoPay (081234567890 a.n KARYAKU)',
            'DANA'    => 'DANA (081234567890 a.n KARYAKU)',
            'OVO'     => 'OVO (081234567890 a.n KARYAKU)',
        ];

        return view('pembeli.daftar-penjual', compact(
            'user',
            'memberships',
            'selectedMembershipId',
            'banks',
            'paymentMethods',
            'pending'
        ));
    }

    // ================= SIMPAN PENDAFTARAN =================
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'membership_id'     => 'required|exists:memberships,id_membership',
            'nik'               => 'required|digits:16',
            'phone'             => 'required|string|max:20',
            'address'           => 'required|string|max:500',
            'identity_document' => 'required|image|mimes:jpg,jpeg,png,webp|max:3072',
            'bank_name'         => 'required|string|max:50',
            'account_name'      => 'required|string|max:100',
            'account_number'    => 'required|string|max:50',
            'payment_method'    => 'required|string|max:100',
            'payment_proof'     => 'required|image|mimes:jpg,jpeg,png,webp|max:3072',
        ], [
            'nik.digits'              => 'NIK harus berupa 16 digit angka.',
            'identity_document.image' => 'File KTP harus berupa gambar (jpg/jpeg/png/webp).',
            'payment_proof.image'     => 'Bukti pembayaran harus berupa gambar (jpg/jpeg/png/webp).',
            'payment_method.required' => 'Silakan pilih metode pembayaran terlebih dahulu.',
        ]);

        // Cegah submit ganda jika masih pending
        $alreadyPending = IdentityVerification::where('user_id', $user->id_user)
            ->where('status', 'pending')
            ->exists();

        if ($alreadyPending) {
            return redirect()->route('pembeli.seller.registration.status')
                ->with('error', 'Kamu masih memiliki pendaftaran yang sedang diverifikasi.');
        }

        $membership = Membership::findOrFail($validated['membership_id']);

        $identityPath = $request->file('identity_document')->store('identity-verifications/ktp', 'public');
        $paymentPath  = $request->file('payment_proof')->store('identity-verifications/payment', 'public');

        // Update nomor telepon user jika diisi
        if (! empty($validated['phone']) && $user->phone !== $validated['phone']) {
            $user->update(['phone' => $validated['phone']]);
        }

        IdentityVerification::create([
            'user_id'              => $user->id_user,
            'identity_document'    => $identityPath,
            'status'               => 'pending',
            'nik'                  => $validated['nik'],
            'address'              => $validated['address'],
            'bank_name'            => $validated['bank_name'],
            'account_name'         => $validated['account_name'],
            'account_number'       => $validated['account_number'],
            'membership_id'        => $membership->id_membership,
            'payment_method'       => $validated['payment_method'],
            'payment_proof'        => $paymentPath,
            'payment_amount'       => $membership->price,
            'payment_submitted_at' => now(),
            'submitted_at'         => now(),
        ]);

        return redirect()->route('pembeli.seller.registration.status')
            ->with('success', 'Pendaftaran sebagai penjual berhasil dikirim! Silakan tunggu proses verifikasi dari admin.');
    }

    // ================= STATUS PENDAFTARAN =================
    public function status()
    {
        $user = Auth::user();

        $registration = IdentityVerification::with(['user', 'membership'])
            ->where('user_id', $user->id_user)
            ->latest('id_identity_verification')
            ->first();

        return view('pembeli.status-daftar-penjual', compact('registration', 'user'));
    }

    // ================= BATALKAN PENDAFTARAN =================
    public function cancel()
    {
        $user = Auth::user();

        $registration = IdentityVerification::where('user_id', $user->id_user)
            ->where('status', 'pending')
            ->latest('id_identity_verification')
            ->first();

        if ($registration) {
            if ($registration->identity_document) {
                Storage::disk('public')->delete($registration->identity_document);
            }
            if ($registration->payment_proof) {
                Storage::disk('public')->delete($registration->payment_proof);
            }
            $registration->delete();

            return redirect()->route('pembeli.seller.registration.create')
                ->with('success', 'Pengajuan pendaftaran penjual telah dibatalkan.');
        }

        return back()->with('error', 'Tidak ada pengajuan pending yang dapat dibatalkan.');
    }
}