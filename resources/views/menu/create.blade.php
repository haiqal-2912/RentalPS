<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-12 col-xl-12">
            <div class="bg-light rounded h-100 p-4">
                <h6 class="mb-4">Add Menu</h6>
                <form>
                    @include('menu.form')
                    <button type="button" wire:click="store" class="btn btn-primary">Tambah</button>
                    <button type="button" wire:click="batal" class="btn btn-secondary">Batal</button>
                </form>
            </div>
        </div>
    </div>
</div>
