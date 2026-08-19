<?php

namespace App\Http\Controllers;

use App\Models\IdentityVerification;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class VerifikatorController extends Controller
{
    /**
     * Dashboard verifikator.
     */
    public function dashboard()
    {
        $pending = IdentityVerification::with([
            'user',
            'membership',
        ])
        ->whereIn('status', [
            'pending',
            'processing',
        ])
        ->latest('id_identity_verification')
        ->paginate(10);

        return view(
            'verifikator.dashboard',
            compact('pending')
        );
    }

    /**
     * Detail pengajuan.
     */
    public function show($id)
    {
        $registration = IdentityVerification::with([
            'user',
            'membership',
            'verifier',
        ])
        ->findOrFail($id);

        return view(
            'verifikator.detail-pendaftaran',
            compact('registration')
        );
    }

    /**
     * Menyetujui pendaftaran.
     */
    public function approve(Request $request, $id)
    {
        $request->validate([
            'notes' => 'nullable|string|max:2000',
        ]);

        $registration = IdentityVerification::with([
            'user',
            'membership',
        ])->findOrFail($id);

        if ($registration->status === 'approved') {
            return back()->with(
                'info',
                'Pengajuan ini sudah disetujui sebelumnya.'
            );
        }

        $penjualRole = Role::where(
            'role_name',
            'penjual'
        )->first();

        if (! $penjualRole) {
            return back()->with(
                'error',
                'Role penjual belum tersedia di database.'
            );
        }

        DB::transaction(function () use (
            $registration,
            $penjualRole,
            $request
        ) {

            /*
            |--------------------------------------------------------------------------
            | UBAH USER MENJADI PENJUAL
            |--------------------------------------------------------------------------
            */

            $registration->user->update([
                'id_role' => $penjualRole->id_role,
                'id_membership' => $registration->membership_id,
                'status' => 'active',
            ]);

            /*
            |--------------------------------------------------------------------------
            | UPDATE VERIFIKASI
            |--------------------------------------------------------------------------
            */

            $registration->update([
                'verifier_id' => Auth::id(),
                'status' => 'approved',
                'notes' => $request->notes,
                'verified_at' => now(),
            ]);
        });

        return redirect()
            ->route(
                'verifikator.pendaftaran.show',
                $registration->id_identity_verification
            )
            ->with(
                'success',
                'Pendaftaran berhasil disetujui. User sekarang menjadi penjual.'
            );
    }

    /**
     * Menolak pendaftaran.
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'notes' => [
                'required',
                'string',
                'max:2000',
            ],
        ]);

        $registration = IdentityVerification::findOrFail($id);

        if ($registration->status === 'approved') {
            return back()->with(
                'error',
                'Pendaftaran yang sudah disetujui tidak dapat ditolak.'
            );
        }

        $registration->update([
            'verifier_id' => Auth::id(),
            'status' => 'rejected',
            'notes' => $request->notes,
            'verified_at' => now(),
        ]);

        return redirect()
            ->route(
                'verifikator.pendaftaran.show',
                $registration->id_identity_verification
            )
            ->with(
                'success',
                'Pendaftaran berhasil ditolak.'
            );
    }
}
