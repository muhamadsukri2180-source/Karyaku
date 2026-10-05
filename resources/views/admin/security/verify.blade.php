@extends('layouts.admin')

@section('title', 'Verifikasi Keamanan System')
@section('header_title', 'Otentikasi Keamanan')
@section('header_subtitle', 'Verifikasi identitas Admin sebelum membuka Pusat Keamanan.')

@section('content')
<div class="p-6 sm:p-8 flex items-center justify-center min-h-[70vh]">
    <div class="bg-white rounded-3xl border border-sky-200 shadow-2xl w-full max-w-md p-8 text-center relative">
        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-sky via-skyHover to-skyDeep rounded-t-3xl"></div>
        
        <div class="w-16 h-16 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center mx-auto mb-4 text-2xl border border-sky-200 shadow-inner">
            <i class="fa-solid fa-shield-halved"></i>
        </div>

        <h3 class="font-extrabold text-slate-900 text-xl font-display">Verifikasi Akses Admin</h3>
        <p class="text-xs text-slate-500 font-medium mt-1 mb-6">Masukkan Password Akun & Kode PIN 6-Digit rahasia Anda untuk melanjutkan.</p>

        <form action="{{ route('admin.security.process_verify') }}" method="POST" class="space-y-5 text-left">
            @csrf
            <div>
                <label class="text-[11px] font-extrabold text-slate-600 uppercase tracking-wider block mb-1.5">Password Admin</label>
                <div class="relative">
                    <input type="password" name="password" required placeholder="Masukkan password akun admin..." class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-3 text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-100 transition-all shadow-sm">
                    <i class="fa-solid fa-key absolute left-3.5 top-3.5 text-slate-400 text-xs"></i>
                </div>
            </div>

            <div>
                <label class="text-[11px] font-extrabold text-slate-600 uppercase tracking-wider block mb-1.5">Kode PIN Keamanan 6-Digit</label>
                <div class="relative">
                    <input type="password" name="pin" maxlength="6" required placeholder="******" class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-3 text-sm font-bold text-slate-800 font-mono tracking-widest focus:outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-100 transition-all shadow-sm">
                    <i class="fa-solid fa-lock absolute left-3.5 top-3.5 text-slate-400 text-xs"></i>
                </div>
            </div>

            <!-- TOMBOL 3D SOLID BLUE STYLE -->
            <div class="pt-2">
                <button type="submit" class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-blue-600 text-white text-[13px] font-bold rounded-xl shadow-[0_4px_0_0_#cbd5e1] hover:bg-blue-700 active:translate-y-[4px] active:shadow-[0_0_0_0_#cbd5e1] transition-all cursor-pointer w-full">
                    <i class="fa-solid fa-lock-open"></i> Verifikasi & Buka Akses
                </button>
            </div>
        </form>
    </div>
</div>
@endsection