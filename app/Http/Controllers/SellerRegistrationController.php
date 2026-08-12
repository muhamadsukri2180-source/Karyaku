<?php

namespace App\Http\Controllers;

use App\Models\IdentityVerification;
use App\Models\Membership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SellerRegistrationController extends Controller
{
    /**
     * Menampilkan halaman pendaftaran penjual.
     */
    public function create(Request $request)
    {
        $memberships = Membership::orderBy('price')->get();

        $selectedMembership = null;

        if ($request->filled('membership')) {
            $selectedMembership = Membership::find(
                $request->membership
            );
        }

        $user = Auth::user();

        return view(
            'pembeli.daftar-penjual',
            compact(
                'memberships',
                'selectedMembership',
                'user'
            )
        );
    }

    /**
     * Menyimpan pengajuan pendaftaran penjual.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | CEK ROLE
        |--------------------------------------------------------------------------
        */

        if (($user->role->role_name ?? null) === 'penjual') {
            return redirect()
                ->route('penjual.dashboard')
                ->with('error', 'Akun kamu sudah menjadi penjual.');
        }

        /*
        |--------------------------------------------------------------------------
        | CEK PENDAFTARAN AKTIF
        |--------------------------------------------------------------------------
        */

        $existing = IdentityVerification::where(
            'user_id',
            $user->id_user
        )
        ->whereIn('status', [
            'pending',
            'processing',
        ])
        ->latest('id_identity_verification')
        ->first();

        if ($existing) {
            return redirect()
                ->route('pembeli.seller.registration.status')
                ->with(
                    'info',
                    'Pengajuan kamu sedang diproses oleh verifikator.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
            ],

            'nik' => [
                'required',
                'string',
                'max:50',
            ],

            'phone' => [
                'required',
                'string',
                'max:20',
            ],

            'address' => [
                'required',
                'string',
                'max:1000',
            ],

            'bank_name' => [
                'required',
                'string',
                'max:100',
            ],

            'account_name' => [
                'required',
                'string',
                'max:150',
            ],

            'account_number' => [
                'required',
                'string',
                'max:100',
            ],

            'membership_id' => [
                'required',
                'exists:memberships,id_membership',
            ],

            'payment_proof' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | CARI PAKET
        |--------------------------------------------------------------------------
        */

        $membership = Membership::findOrFail(
            $validated['membership_id']
        );

        /*
        |--------------------------------------------------------------------------
        | UPLOAD BUKTI PEMBAYARAN
        |--------------------------------------------------------------------------
        */

        $paymentProofPath = $request
            ->file('payment_proof')
            ->store(
                'seller-registration/payment-proofs',
                'public'
            );

        /*
        |--------------------------------------------------------------------------
        | UPDATE DATA USER
        |--------------------------------------------------------------------------
        |
        | Nama dan nomor telepon tetap disimpan di users.
        | Role BELUM diubah.
        |
        */

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | SIMPAN PENGAJUAN
        |--------------------------------------------------------------------------
        */

        IdentityVerification::create([
            'user_id' => $user->id_user,

            'verifier_id' => null,

            'identity_document' => $validated['nik'],

            'nik' => $validated['nik'],

            'address' => $validated['address'],

            'bank_name' => $validated['bank_name'],

            'account_name' => $validated['account_name'],

            'account_number' => $validated['account_number'],

            'membership_id' => $membership->id_membership,

            'payment_proof' => $paymentProofPath,

            'payment_amount' => $membership->price,

            'payment_submitted_at' => now(),

            'submitted_at' => now(),

            'status' => 'pending',

            'notes' => null,

            'verified_at' => null,
        ]);

        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('pembeli.seller.registration.status')
            ->with(
                'success',
                'Pendaftaran berhasil dikirim. Silakan tunggu proses verifikasi.'
            );
    }

    /**
     * Menampilkan status pendaftaran.
     */
    public function status()
    {
        $registration = IdentityVerification::with([
            'membership',
            'verifier',
        ])
        ->where(
            'user_id',
            Auth::id()
        )
        ->latest('id_identity_verification')
        ->first();

        return view(
            'pembeli.status-daftar-penjual',
            compact('registration')
        );
    }

    /**
     * Membatalkan pengajuan pending.
     */
    public function cancel()
    {
        $registration = IdentityVerification::where(
            'user_id',
            Auth::id()
        )
        ->whereIn('status', [
            'pending',
            'processing',
        ])
        ->latest('id_identity_verification')
        ->first();

        if (! $registration) {
            return back()->with(
                'error',
                'Tidak ada pengajuan yang bisa dibatalkan.'
            );
        }

        if ($registration->payment_proof) {
            Storage::disk('public')->delete(
                $registration->payment_proof
            );
        }

        $registration->delete();

        return redirect()
            ->route('pembeli.membership')
            ->with(
                'success',
                'Pengajuan pendaftaran berhasil dibatalkan.'
            );
    }
}
