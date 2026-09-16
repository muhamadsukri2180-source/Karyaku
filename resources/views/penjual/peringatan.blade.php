@extends('layouts.penjual')
@section('title', 'Peringatan Saya - Karyaku')

@section('content')

<style>
    :root{
        --primary:#2563eb;
        --primary-dark:#1e3a8a;
        --primary-light:#eff6ff;
        --primary-soft:#dbeafe;

        --danger:#dc2626;
        --danger-light:#fef2f2;
        --danger-soft:#fee2e2;

        --success:#16a34a;
        --success-light:#f0fdf4;
        --success-soft:#dcfce7;

        --border-color:#e5e7eb;
        --border-soft:#eef2f7;

        --text-dark:#1e293b;
        --text-muted:#64748b;
        --text-soft:#94a3b8;

        --shadow:0 5px 20px rgba(15,23,42,.06);
        --shadow-hover:0 12px 30px rgba(15,23,42,.09);
    }

    /* ================================
       HEADER
    ================================= */
    .seller-page-head{
        margin-bottom:24px;
    }

    .seller-page-head h4{
        font-size:22px;
        font-weight:800;
        letter-spacing:-.3px;
        color:var(--text-dark);
        margin-bottom:6px;
    }

    .seller-page-head h4 i{
        font-size:21px;
    }

    .seller-page-head p{
        color:var(--text-muted);
        line-height:1.7;
        max-width:850px;
    }

    /* ================================
       CARD BASE
    ================================= */
    .warning-card{
        background:#fff;
        border:1px solid var(--border-color);
        border-radius:18px;
        box-shadow:var(--shadow);
        overflow:hidden;
    }

    /* ================================
       EMPTY STATE
    ================================= */
    .empty-warning{
        padding:55px 25px;
        text-align:center;
    }

    .empty-warning-icon{
        width:76px;
        height:76px;
        border-radius:50%;
        background:var(--success-light);
        color:var(--success);
        display:inline-flex;
        align-items:center;
        justify-content:center;
        margin-bottom:18px;
        border:7px solid #f7fee9;
    }

    .empty-warning-icon i{
        font-size:34px;
    }

    .empty-warning h5{
        color:var(--text-dark);
        font-size:18px;
        font-weight:800;
        margin-bottom:7px;
    }

    .empty-warning p{
        color:var(--text-muted);
        font-size:13px;
        line-height:1.7;
        max-width:600px;
        margin:0 auto;
    }

    /* ================================
       WARNING LIST
    ================================= */
    .warning-list{
        padding:8px;
    }

    .warning-item{
        position:relative;
        display:flex;
        gap:16px;
        align-items:flex-start;
        padding:18px;
        border-radius:14px;
        transition:.2s ease;
    }

    .warning-item:hover{
        background:#fafcff;
    }

    .warning-item:not(:last-child){
        border-bottom:1px solid var(--border-soft);
        border-radius:14px 14px 0 0;
    }

    /* ================================
       WARNING ICON
    ================================= */
    .warning-icon{
        width:48px;
        height:48px;
        min-width:48px;
        border-radius:14px;
        background:var(--danger-light);
        color:var(--danger);
        display:flex;
        align-items:center;
        justify-content:center;
        border:1px solid var(--danger-soft);
    }

    .warning-icon i{
        font-size:20px;
    }

    /* ================================
       CONTENT
    ================================= */
    .warning-content{
        flex:1;
        min-width:0;
    }

    .warning-top{
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:12px;
        flex-wrap:wrap;
        margin-bottom:8px;
    }

    .warning-title{
        font-size:14px;
        font-weight:800;
        color:var(--text-dark);
        line-height:1.5;
    }

    .warning-title .reason{
        color:var(--danger);
    }

    /* ================================
       STATUS BADGE
    ================================= */
    .warning-badge{
        display:inline-flex;
        align-items:center;
        gap:5px;

        padding:6px 11px;
        border-radius:999px;

        background:var(--danger-light);
        color:var(--danger);
        border:1px solid var(--danger-soft);

        font-size:11px;
        font-weight:800;
        white-space:nowrap;
    }

    /* ================================
       STAFF NOTE
    ================================= */
    .warning-note{
        position:relative;
        background:#f8fafc;
        border:1px solid var(--border-soft);
        border-left:4px solid var(--danger);
        border-radius:10px;

        padding:12px 14px;
        margin:10px 0 12px;

        color:#475569;
        font-size:12px;
        line-height:1.7;
    }

    .warning-note strong{
        color:var(--text-dark);
        font-weight:800;
    }

    /* ================================
       META
    ================================= */
    .warning-meta{
        display:flex;
        align-items:center;
        flex-wrap:wrap;
        gap:8px 18px;

        color:var(--text-soft);
        font-size:11px;
    }

    .warning-meta span{
        display:inline-flex;
        align-items:center;
    }

    .warning-meta i{
        color:#94a3b8;
        margin-right:5px;
    }

    .warning-product{
        max-width:100%;
    }

    .warning-product strong{
        color:var(--text-muted);
        font-weight:700;
    }

    /* ================================
       PAGINATION
    ================================= */
    .warning-pagination{
        margin-top:20px;
        display:flex;
        justify-content:center;
    }

    .warning-pagination .pagination{
        margin-bottom:0;
        gap:5px;
    }

    .warning-pagination .page-link{
        border:1px solid var(--border-color);
        color:var(--text-muted);
        border-radius:9px !important;
        font-size:12px;
        font-weight:700;
        min-width:36px;
        height:36px;

        display:flex;
        align-items:center;
        justify-content:center;

        background:#fff;
        transition:.2s ease;
    }

    .warning-pagination .page-link:hover{
        background:var(--primary-light);
        color:var(--primary);
        border-color:#bfdbfe;
    }

    .warning-pagination .page-item.active .page-link{
        background:var(--primary);
        border-color:var(--primary);
        color:#fff;
    }

    .warning-pagination .page-item.disabled .page-link{
        color:#cbd5e1;
        background:#f8fafc;
    }

    /* ================================
       RESPONSIVE
    ================================= */
    @media(max-width:768px){

        .seller-page-head h4{
            font-size:19px;
        }

        .seller-page-head p{
            font-size:12px !important;
        }

        .warning-list{
            padding:5px;
        }

        .warning-item{
            gap:12px;
            padding:15px 12px;
        }

        .warning-icon{
            width:42px;
            height:42px;
            min-width:42px;
            border-radius:12px;
        }

        .warning-icon i{
            font-size:18px;
        }

        .warning-title{
            font-size:13px;
        }

        .warning-top{
            align-items:flex-start;
        }

        .warning-badge{
            font-size:10px;
            padding:5px 9px;
        }

        .warning-note{
            font-size:11px;
            padding:10px 11px;
        }

        .warning-meta{
            display:block;
            font-size:10px;
        }

        .warning-meta span{
            margin-bottom:5px;
        }

        .empty-warning{
            padding:45px 20px;
        }
    }
</style>


{{-- ================================
     PAGE HEADER
================================ --}}
<div class="seller-page-head">

    <h4>
        <i class="bi bi-shield-exclamation text-danger me-2"></i>
        Peringatan & Teguran Akun
    </h4>

    <p class="small mb-0">
        Daftar catatan teguran resmi dari Tim Verifikator / Admin terkait aktivitas
        atau laporan yang masuk terhadap akun penjual Anda.
    </p>

</div>


{{-- ================================
     EMPTY STATE
================================ --}}
@if ($peringatan->isEmpty())

    <div class="warning-card">

        <div class="empty-warning">

            <div class="empty-warning-icon">
                <i class="bi bi-shield-check"></i>
            </div>

            <h5>Status Akun Anda Bersih</h5>

            <p>
                Tidak ada teguran atau peringatan aktif untuk akun kamu.
                Terus patuhi syarat dan ketentuan komunitas Karyaku ya!
            </p>

        </div>

    </div>


@else

    {{-- ================================
         WARNING LIST
    ================================= --}}
    <div class="warning-card">

        <div class="warning-list">

            @foreach ($peringatan as $p)

                <div class="warning-item">

                    {{-- ICON --}}
                    <div class="warning-icon flex-shrink-0">
                        <i class="bi bi-exclamation-octagon-fill"></i>
                    </div>


                    {{-- CONTENT --}}
                    <div class="warning-content">

                        {{-- TITLE + STATUS --}}
                        <div class="warning-top">

                            <div class="warning-title">

                                Peringatan Pelanggaran:

                                <span class="reason">
                                    {{ $p->reason }}
                                </span>

                            </div>


                            {{-- STATUS --}}
                            <span class="warning-badge">

                                <i class="bi bi-exclamation-circle-fill"></i>

                                {{ $p->action_taken ? ucfirst($p->action_taken) : 'Peringatan' }}

                            </span>

                        </div>


                        {{-- CATATAN PETUGAS --}}
                        <div class="warning-note">

                            <strong>
                                <i class="bi bi-chat-left-text me-1"></i>
                                Catatan Petugas:
                            </strong>

                            {{ $p->admin_note }}

                        </div>


                        {{-- META INFORMATION --}}
                        <div class="warning-meta">

                            <span>
                                <i class="bi bi-calendar3"></i>

                                {{ optional($p->reviewed_at ?? $p->updated_at)->translatedFormat('d F Y, H:i') }}
                                WIB
                            </span>


                            @if($p->product)

                                <span class="warning-product">

                                    <i class="bi bi-box"></i>

                                    Terkait:

                                    <strong class="ms-1">
                                        {{ $p->product->title }}
                                    </strong>

                                </span>

                            @endif

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    </div>


    {{-- ================================
         PAGINATION
    ================================= --}}
    <div class="warning-pagination">
        {{ $peringatan->links() }}
    </div>

@endif

@endsection