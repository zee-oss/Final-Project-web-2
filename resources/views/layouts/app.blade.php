<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SiMart – @yield('title', 'Sistem Informasi Mini Market')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; background: #1e3a5f; }
        .sidebar .nav-link { color: #adb5bd; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background: rgba(255,255,255,0.1); border-radius: 6px; }
        .sidebar .brand { font-size: 1.3rem; font-weight: 700; color: #fff; }
        .badge-role { font-size: 0.7rem; }
        .card-stat { border-left: 4px solid; }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        {{-- Sidebar --}}
        <nav class="col-md-2 sidebar p-3">
            <div class="brand mb-4 ps-2">🛒 SiMart</div>
            <ul class="nav flex-column gap-1">
                <li><a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2 me-2"></i>Dashboard
                </a></li>

                @if(auth()->user()->isCashier())
                <li><a href="{{ route('pos.create') }}" class="nav-link {{ request()->routeIs('pos.*') ? 'active' : '' }}">
                    <i class="bi bi-cart-plus me-2"></i>Transaksi Baru
                </a></li>
                @endif

                @if(auth()->user()->hasRole(['owner','manager','supervisor']))
                <li><a href="{{ route('transactions.index') }}" class="nav-link">
                    <i class="bi bi-receipt me-2"></i>Riwayat Transaksi
                </a></li>
                @endif

                <li><a href="{{ route('stock.index') }}" class="nav-link">
                    <i class="bi bi-boxes me-2"></i>Stok Barang
                </a></li>

                {{--
                @if(auth()->user()->hasRole(['warehouse','supervisor','owner','manager']))
                <li><a href="{{ route('stock.receivings.index') }}" class="nav-link">
                    <i class="bi bi-box-arrow-in-down me-2"></i>Penerimaan Barang
                </a></li>
                @endif
                --}}

                @if(auth()->user()->hasRole(['supervisor','owner','manager']))
                <li><a href="{{ route('stock.opname') }}" class="nav-link">
                    <i class="bi bi-clipboard-check me-2"></i>Stok Opname
                </a></li>
                @endif

                @if(auth()->user()->hasRole(['owner','manager']))
                <li class="mt-2"><small class="text-muted ps-2">LAPORAN</small></li>
                <li><a href="{{ route('reports.transaction') }}" class="nav-link">
                    <i class="bi bi-bar-chart me-2"></i>Lap. Transaksi
                </a></li>
                <li><a href="{{ route('reports.stock') }}" class="nav-link">
                    <i class="bi bi-clipboard-data me-2"></i>Lap. Stok
                </a></li>
                <li><a href="{{ route('reports.profit') }}" class="nav-link">
                    <i class="bi bi-graph-up me-2"></i>Lap. Laba Kotor
                </a></li>
                @endif

                @if(auth()->user()->isOwner())
                <li class="mt-2"><small class="text-muted ps-2">MASTER DATA</small></li>
                <li><a href="{{ route('master.products.index') }}" class="nav-link"><i class="bi bi-box me-2"></i>Produk</a></li>
                <li><a href="{{ route('master.branches.index') }}" class="nav-link"><i class="bi bi-shop me-2"></i>Cabang</a></li>
                <li><a href="{{ route('master.users.index') }}" class="nav-link"><i class="bi bi-people me-2"></i>Pengguna</a></li>
                <li><a href="{{ route('master.suppliers.index') }}" class="nav-link"><i class="bi bi-truck me-2"></i>Supplier</a></li>
                <li><a href="{{ route('master.activity-logs.index') }}" class="nav-link"><i class="bi bi-clock-history me-2"></i>Log Aktivitas</a></li>
                @endif
            </ul>

            <div class="mt-auto pt-4 border-top border-secondary">
                <div class="text-white-50 small ps-2">
                    <div class="fw-semibold text-white">{{ auth()->user()->name }}</div>
                    <span class="badge bg-primary badge-role">{{ auth()->user()->role->display_name }}</span>
                    @if(auth()->user()->branch)
                        <div class="mt-1">{{ auth()->user()->branch->name }}</div>
                    @endif
                </div>
                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button class="btn btn-sm btn-outline-danger w-100">
                        <i class="bi bi-box-arrow-right me-1"></i>Logout
                    </button>
                </form>
            </div>
        </nav>

        {{-- Main Content --}}
        <main class="col-md-10 p-4">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif
            @yield('content')
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>