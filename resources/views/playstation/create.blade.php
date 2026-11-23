<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-12 col-xl-12">
            <div class="bg-light rounded h-100 p-4">
                <h6 class="mb-4">Add Playstation</h6>

                <form>
                    @if (session()->has('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div class="mb-3">
                        <label for="Type" class="form-label">Type</label>
                        <input type="text" class="form-control" wire:model="Type" id="Type"
                            value="{{ old('Type') }}">
                        @error('Type')
                            <div class="form text text-danger"> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="no_seri" class="form-label">Serial Number</label>
                        <input type="text" class="form-control" wire:model="no_seri" id="no_seri"
                            value="{{ old('no_seri') }}">
                        @error('no_seri')
                            <div class="form text text-danger"> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="foto" class="form-label">Foto Playstation</label>
                        <input type="file" class="form-control" wire:model="foto" id="foto"
                            value="{{ old('foto') }}">
                        @error('foto')
                            <div class="form text text-danger"> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="harga_sewa" class="form-label">Harga Sewa per Jam</label>
                        <input type="text" class="form-control" wire:model="harga_sewa" id="harga_sewa"
                            value="{{ old('harga_sewa') }}">
                        @error('harga_sewa')
                            <div class="form text text-danger"> {{ $message }}</div>
                        @enderror
                    </div>
                    <button type="button" wire:click="store" class="btn btn-primary">Tambah</button>
                </form>
            </div>
            </form>
        </div>
    </div>
</div>
</div>
