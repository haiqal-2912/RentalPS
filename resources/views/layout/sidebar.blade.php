<style>
    .navbar-nav .nav-link.active {
    color: #007bff;
}
</style>
<div class="sidebar pe-4 pb-3">
    <nav class="navbar bg-light navbar-light">
        <a href="index.html" class="navbar-brand mx-4 mb-3">
            <h3 class="text-primary"><i class="fa fa-map-marker-alt me-3"></i>PS ONE</h3>
        </a>
        <div class="d-flex align-items-center ms-4 mb-4">
            <div class="position-relative">
                <img class="rounded-circle" src="{{ asset ('assets/img/img-default.jpg')}}" alt="" style="width: 40px; height: 40px;">
                <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
            </div>
            <div class="ms-3">
                <h6 class="mb-0">{{ auth()->user()->email }}</h6>
                <span>{{ auth()->user()->role }}</span>
            </div>
        </div>
        <div class="navbar-nav w-100">
            <a href="{{ route('beranda') }}" class="nav-item nav-link {{ request()->route()->getName() == 'beranda' ? 'active' : '' }}"><i class="fa fa-tachometer-alt me-2"></i>Dashboard</a>
            <a href="{{ route('playstation') }}" class="nav-item nav-link {{ request()->route()->getName() == 'playstation' ? 'active' : '' }}"><i class="fa fa-gamepad me-2"></i>Playstation</a>
            <a href="{{ route('transaksi') }}" class="nav-item nav-link {{ request()->route()->getName() == 'transaksi' ? 'active' : '' }}"><i class="fa fa-shopping-cart me-2"></i>Transaksi</a>
            @if (auth()->user()->role === 'kasir')
                <a href="{{ route('laporan') }}" class="nav-item nav-link {{ request()->route()->getName() == 'laporan' ? 'active' : '' }}"><i class="fa fa-file-invoice-dollar me-2"></i>Laporan Transaksi</a>
                <a href="{{ route('users') }}" class="nav-item nav-link {{ request()->route()->getName() == 'users' ? 'active' : '' }}"><i class="fa fa-users me-2"></i>User</a>
            @endif
            <a href="{{ route('menu') }}" class="nav-item nav-link {{ request()->route()->getName() == 'menu' ? 'active' : '' }}"><i class="fa fa-utensils me-2"></i>Menu</a>
            <a href="{{ route('pesanan') }}" class="nav-item nav-link {{ request()->route()->getName() == 'pesanan' ? 'active' : '' }}"><i class="fa fa-receipt me-2"></i>{{ auth()->user()->role === 'kasir' ? 'Pesanan Menu' : 'Pesanan Saya' }}</a>
        </div>
    </nav>
</div>