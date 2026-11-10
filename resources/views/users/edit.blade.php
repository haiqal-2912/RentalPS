<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-12 col-xl-12">
            <div class="bg-light rounded h-100 p-4">
                <h6 class="mb-4">Edit User</h6>
                
                <form>@if (session()->has('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" class="form-control" wire:model="name" id="name"
                            value="{{ old('name') }}">
                        @error('name')
                            <div class="form text text-danger"> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="text" class="form-control" wire:model="email" id="email"
                            value="{{ old('email') }}">
                        @error('email')
                            <div class="form text text-danger"> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" class="form-select" wire:model="status">
                            <option value="">--Pilih--</option>
                            <option value="0">Belum aktif</option>
                            <option value="1">Aktif</option>
                        </select>
                        @error('status')
                            <div class="form text text-danger"> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <input type="text" class="form-control" wire:model="alamat" id="alamat"
                            value="{{ old('alamat') }}">
                        @error('alamat')
                            <div class="form text text-danger"> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="no_telp" class="form-label">No Telp</label>
                        <input type="text" class="form-control" wire:model="no_telp" id="no_telp"
                            value="{{ old('no_telp') }}">
                        @error('no_telp')
                            <div class="form text text-danger"> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" wire:model="password" id="password"
                            value="{{ old('password') }}">
                        @error('password')
                            <div class="form text text-danger"> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select id="role" class="form-select" wire:model="role">
                            <option value="">--Pilih--</option>
                            <option value="owner">Owner</option>
                            <option value="kasir">Kasir</option>
                        </select>
                        @error('role')
                            <div class="form text text-danger"> {{ $message }}</div>
                        @enderror
                    </div>
                    <button type="button" wire:click="update" class="btn btn-primary">Perbarui</button>
                </form>
            </div>
        </div>
    </div>
</div>
