<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-6 col-xl-4">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa fa-gamepad fa-3x text-primary"></i>
                <div class="ms-3">
                    <p class="mb-2">Playstation</p>
                    <h6 class="mb-0">{{ $playstation }}</h6>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-4">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa fa-dollar-sign fa-3x text-primary"></i>
                <div class="ms-3">
                    <p class="mb-2">Total Transaksi</p>
                    <h6 class="mb-0">@rupiah ($transaksi)</h6>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-4">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa fa-users fa-3x text-primary"></i>
                <div class="ms-3">
                    <p class="mb-2">Users</p>
                    <h6 class="mb-0">{{ $user }}</h6>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body border-top">
                        <div class="alert alert-success" role="alert">
                            <h4 class="alert-heading"> Selamat Datang, {{ auth()->user()->name }}</h4>
                            Aplikasi Rental Playstation dengan hak akses yang anda miliki sebagai
                            <b>{{ auth()->user()->role }}
                            </b>
                            ini adalah halaman utama dari aplikasi Web Programming. Studi Kasus
                            Rental Playstation.
                            <hr>
                            <p class="mb-0">Gabut..? Yuu Rental Di PSONE</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
