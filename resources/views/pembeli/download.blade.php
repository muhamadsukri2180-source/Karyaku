@extends('layouts.pembeli')
@section('title', 'Download Saya')

@section('content')

<h4 class="fw-bold mb-4">Karya yang Sudah Dibeli</h4>

@if ($orderItems->isEmpty())
    <div class="card-box p-5 text-center text-muted">
        <i class="bi bi-cloud-arrow-down fs-1 d-block mb-3"></i>
        Belum ada karya yang bisa diunduh. Karya akan muncul di sini setelah pesanan kamu dibayar.
        <div class="mt-3"><a href="{{ route('pembeli.marketplace') }}" class="btn btn-primary btn-sm">Mulai Belanja</a></div>
    </div>
@else
<div class="card-box p-3">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>ID Pesanan</th>
                    <th>Tanggal Beli</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orderItems as $item)
                <tr>
                    <td class="small fw-semibold">{{ $item->product->title ?? 'Produk telah dihapus' }}</td>
                    <td class="small">{{ $item->order->kode_order ?? '-' }}</td>
                    <td class="small">{{ $item->created_at->translatedFormat('d M Y') }}</td>
                    <td>
                        @if ($item->product && $item->product->file)
                            <a href="{{ asset('storage/' . $item->product->file) }}" target="_blank" class="btn btn-sm btn-primary"><i class="bi bi-download"></i> Unduh</a>
                        @else
                            <span class="text-muted small">File tidak tersedia</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection
