<?php

namespace App\Livewire\Users;

use App\Models\{Classroom, Role, User, Major};
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Helpers\MainRole;
use Livewire\Component;

class Index extends Component
{
    public $name = "";
    public $email = null;
    public $password = "";
    public $role_id = "";
    public $createMainRole = false;
    public $userId = null;
    public $createAdditionRole = false;
    public $first_name = "";
    public $last_name = "";
    public $address = "";
    public $phone_number = "";
    public $role_user = null;
    public $edit = false;
    public $selectClassroom = false;
    public $search = "";
    public $major_id;
    public $classroom_id;

    public function render()
    {
        return view('livewire.users.index', [
            'listUser'  => User::with('roles')->get(),
            'mainRole'  => MainRole::mainRole,
            'listMajor' => Major::get(['id', 'name']),
            'listClassroom'=> Classroom::get(['id', 'name'])
        ]);
    }

    public function openModal() {
        $this->createMainRole = true;
    }

    public function resetFields() {
        $this->reset(['name', 'address', 'email', 'password',
        'last_name', 'first_name', 'major_id', 'phone_number', 'role_id', 'classroom_id', 'role_user', 'selectClassroom']);
    }

    public function resetError() {
        $this->resetErrorBag(['name', 'address', 'phone_number', 'email', 'role_id', 'role_user', 'password', 'last_name', 'first_name', 'major_id', 'classroom_id']);
    }

    public function storeDataUserGeneral() {

        // validasi data user general

            $validated = $this->validate([
                'name'          => 'required|min:3',
                'email'         => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
                'password'      => ['required','string', Rules\Password::defaults()],
                'role_id'       => 'required'
            ], [
                'name.required'             => 'Username wajib diisi..',
                'name.min'                  => 'Username minimal 3 karakter',
                'password.required'         => 'Password wajib diisi..',
                'email.required'            => 'Alamat email wajib diisi',
                'email.unique'              => 'Alamat email sudah digunakan..',
                'email.email'               => 'format email salah..',
                'role_id.required'          => 'Jabatan Wajib dipilih..'
            ]);

            $user = User::create([
                'name'  => $validated['name'],
                'email'  => $validated['email'],
                'role_id'  => $validated['role_id'],
                'password'  => bcrypt($validated['password']),
            ]);
            $this->resetFields();
            $this->dispatch('data-user-general');
            $this->createAdditionRole = true;
            $this->userId = $user->id;

        //       $validated = $this->validate([
        //     'name'          => 'required|min:3',
        //     'first_name'    => 'required|min:4',
        //     'last_name'     => 'required|min:4',
        //     'role_user'     => 'required',
        //     'address'       => 'required|min:4',
        //     'phone_number'  => 'required|numeric|min_digits:9',
        //     'email'         => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
        //     'password'      => ['required','string', Rules\Password::defaults()],
        //     'major_id'      => $roleName === 'siswa' ? 'required' : 'nullable',
        //     'classroom_id'  => $roleName === 'siswa' ? 'required' : 'nullable',
        //     ],[
        //     'name.required'             => 'Username wajib diisi..',
        //     'name.min'                  => 'Username minimal 3 karakter',
        //     'first_name.required'       => 'Nama Depan Wajib diisi..',
        //     'first_name.min'            => 'Nama Depan minimal 4 karakter..',
        //     'last_name.required'        => 'Nama Belakang Wajib diisi..',
        //     'last_name.min'             => 'Nama Belakang minimal 4 karakter..',
        //     'role_user.required'        => 'Nama Jabatan wajib diisi..',
        //     'address.required'          => 'Alamat wajib diisi..',
        //     'address.min'               => 'Alamat minimal 4 karakter',
        //     'phone_number.required'     => 'Nomor Telepon wajib diisi',
        //     'phone_number.numeric'      => 'Nomor Telepon hanya boleh angka',
        //     'phone_number.min_digits'   => 'Nomor Telepon minimal 9 angka',
        //     'email.required'            => 'Alamat email wajib diisi',
        //     'email.unique'              => 'Alamat email sudah digunakan..',
        //     'email.email'               => 'format email salah..',
        //     'password.required'         => 'Password wajib diisi..',
        //     'major_id.required'         => 'Jurusan wajib dipilih..',
        //     'classroom_id.required'     => 'Kelas wajib dipilih..'
        // ]);
        // $roleName = DB::table('roles')->where('id', $this->role_user)->value('name');

        // $validated = $this->validate([
        //     'name'          => 'required|min:3',
        //     'first_name'    => 'required|min:4',
        //     'last_name'     => 'required|min:4',
        //     'role_user'     => 'required',
        //     'address'       => 'required|min:4',
        //     'phone_number'  => 'required|numeric|min_digits:9',
        //     'email'         => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
        //     'password'      => ['required','string', Rules\Password::defaults()],
        //     'major_id'      => $roleName === 'siswa' ? 'required' : 'nullable',
        //     'classroom_id'  => $roleName === 'siswa' ? 'required' : 'nullable',
        //     ],[
        //     'name.required'             => 'Username wajib diisi..',
        //     'name.min'                  => 'Username minimal 3 karakter',
        //     'first_name.required'       => 'Nama Depan Wajib diisi..',
        //     'first_name.min'            => 'Nama Depan minimal 4 karakter..',
        //     'last_name.required'        => 'Nama Belakang Wajib diisi..',
        //     'last_name.min'             => 'Nama Belakang minimal 4 karakter..',
        //     'role_user.required'        => 'Nama Jabatan wajib diisi..',
        //     'address.required'          => 'Alamat wajib diisi..',
        //     'address.min'               => 'Alamat minimal 4 karakter',
        //     'phone_number.required'     => 'Nomor Telepon wajib diisi',
        //     'phone_number.numeric'      => 'Nomor Telepon hanya boleh angka',
        //     'phone_number.min_digits'   => 'Nomor Telepon minimal 9 angka',
        //     'email.required'            => 'Alamat email wajib diisi',
        //     'email.unique'              => 'Alamat email sudah digunakan..',
        //     'email.email'               => 'format email salah..',
        //     'password.required'         => 'Password wajib diisi..',
        //     'major_id.required'         => 'Jurusan wajib dipilih..',
        //     'classroom_id.required'     => 'Kelas wajib dipilih..'
        // ]);

        // $user = User::create([
        //     'name'          => $validated['name'],
        //     'first_name'    => $validated['first_name'],
        //     'last_name'     => $validated['last_name'],
        //     'address'       => $validated['address'],
        //     'phone_number'  => $validated['phone_number'],
        //     'email'         => $validated['email'],
        //     'password'      => Hash::make($validated['password']),
        //     'major_id'      => $validated['major_id'],
        //     'classroom_id'  => $validated['classroom_id'],
        // ]);

        // $newUser = User::find($user->id);
        // $newUser->roles()->attach($validated['role_user'], ['role_id'   => $validated['role_user']]);
    }

    public function resetFormGeneral() {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->role_id = null;
    }

    public function resetErrorFormGeneral() {
        $this->resetErrorBag(['name', 'email', 'password', 'role_id']);
    }
}
