<?php

namespace App\Livewire\Users;

use App\Models\{Classroom, Role, User, Major};
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use Livewire\Component;

class Index extends Component
{
    public $name = "";
    public $email = "";
    public $password = "";
    public $firstname = "";
    public $last_name = "";
    public $address = "";
    public $phone_number = "";
    public $role_user = null;
    public $edit = false;
    public $create = false;
    public $selectClassroom = false;
    public $search = "";
    public $major_id;
    public $classroom_id;

    public function render()
    {
        return view('livewire.users.index', [
            'listUser'  => User::with('roles')->get(),
            'listRole'  => Role::get(['id', 'name']),
            'listMajor' => Major::get(['id', 'name']),
            'listClassroom'=> Classroom::get(['id', 'name'])
        ]);
    }

    public function openModal() {
        $this->create = true;
    }

    public function resetFields() {
        $this->reset(['name', 'address', 'email', 'password',
        'last_name', 'firstname', 'major_id', 'phone_number', 'classroom_id', 'role_user', 'selectClassroom']);
    }

    public function resetError() {
        $this->resetErrorBag(['name', 'address', 'phone_number', 'email', 'password', 'last_name', 'firstname', 'major_id', 'classroom_id']);
    }

    public function sendDataUser() {

        $roleName = DB::table('roles')->where('id', $this->role_user)->value('name');

       $this->validate([
            'name'          => 'required|min:3',
            'firstname'    => 'required|min:4',
            'last_name'     => 'required|min:4',
            'role_user'     => 'required',
            'address'       => 'required|min:4',
            'phone_number'  => 'required|number|min:9',
            'email'         => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password'      => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'major_id'      => $roleName === 'siswa' ? 'required' : 'nullable',
            'classroom_id'  => $roleName === 'siswa' ? 'required' : 'nullable',
        ],[
            'name.required'             => 'Username wajib diisi..',
            'name.min'                  => 'Username minimal 3 karakter',
            'firstname.required'       => 'Nama Depan Wajib diisi..',
            'firstname.min'            => 'Nama Depan minimal 4 karakter..',
            'last_name.required'        => 'Nama Belakang Wajib diisi..',
            'last_name.min'             => 'Nama Belakang minimal 4 karakter..',
            'role_user.required'        => 'Nama Jabatan wajib diisi..',
            'address.required'          => 'Alamat wajib diisi..',
            'address.min'               => 'Alamat minimal 4 karakter',
            'phone_number.required'     => 'Nomor telepon wajib diisi..',
            'phone_number.min'          => 'Nomor Telepon minimal 9 angka',
            'email.required'            => 'Alamat email wajib diisi',
            'email.unique'              => 'Alamat email sudah digunakan..',
            'email.email'               => 'format email salah..',
            'password.required'         => 'Password wajib diisi..',
            'password.confirmed'        => 'Password tidak cocok..',
            'major_id.required'         => 'Jurusan wajib dipilih..',
            'classroom_id.required'     => 'Kelas wajib dipilih..'
        ]);

        dd($this->role_user);





    }
}
