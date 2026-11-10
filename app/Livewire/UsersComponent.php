<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class UsersComponent extends Component
{
    use WithPagination, WithoutUrlPagination;
    protected $paginationTheme = 'bootstrap';
    public $addPage, $editPage = false;
    public $name, $email, $password, $role, $id, $alamat, $no_telp, $status;
    public function render()
    {
        $data['user'] = User::paginate(10);
        return view('livewire.users-component', $data);
    }
    public function create()
    {
        $this->reset();
        $this->addPage = true;
    }
    public function store()
    {
        $this->validate(
            [
                'name' => 'required',
                'email' => 'required|email',
                'alamat' => 'required',
                'no_telp' => 'required',
                'password' => 'required',
                'role' => 'required',
            ],
            [
                'name.required' => 'Nama Tidak Boleh Kosong!',
                'email.required' => 'Email Tidak Boleh Kosong!',
                'email.email' => 'Format email salah!',
                'password.required' => 'Password Tidak Boleh Kosong!',
                'role.required' => 'Role Tidak Boleh Kosong!',
            ],
        );
        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'alamat' => $this->alamat,
            'no_telp' => $this->no_telp,
            'password' => Hash::make($this->password),
            'role' => $this->role,
            'status' => "1",
        ]);
        session()->flash('success', 'Berhasil didaftarkan!');
        $this->reset();
    }
    public function destroy($id)
    {
        $cari = User::find($id);
        $cari->delete();
        session()->flash('success', 'Berhasil hapus Data!');
        $this->reset();
    }
    public function edit($id)
    {
        $this->reset();
        $cari = User::find($id);
        $this->name = $cari->name;
        $this->email = $cari->email;
        $this->alamat = $cari->alamat;
        $this->no_telp = $cari->no_telp;
        $this->status = $cari->status;
        $this->role = $cari->role;
        $this->id = $cari->id;
        $this->editPage = true;
    }
    public function update()
    {
        $cari = User::find($this->id);
        if ($this->password == '') {
            $cari->update([
                'name' => $this->name,
                'email' => $this->email,
                'alamat' => $this->alamat,
                'no_telp' => $this->no_telp,
                'status' => $this->status,
                'role' => $this->role,
            ]);
        } else {
            $cari->update([
                'name' => $this->name,
                'email' => $this->email,
                'alamat' => $this->alamat,
                'no_telp' => $this->no_telp,
                'status' => $this->status,
                'password' => Hash::make($this->password),
                'role' => $this->role,
            ]);
        }
        session()->flash('success', 'Data Berhasil Diubah');
        $this->reset();
    }
}
