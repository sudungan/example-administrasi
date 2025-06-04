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
    public $first_name = "";
    public $last_name = "";
    public $address = "";
    public $phone_number = "";
    public $role_user = null;
    public $additedRole = false;
    public $additionDetailProfile = false;
    public $selectClassroom = false;
    public $search = "";
    public $major_id;
    public $classroom_id;


    public function render()
    {
        return view('livewire.users.index', [
            'listUser'  => User::with('role')->get(),
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
            $this->additionDetailProfile = true;
    }

    public function storeDetailProfile() {
        dd($this->userId);
    }

    public function showDetailUser($idUser = null) {
        if ($idUser) {
            $this->userId = $idUser;
            $this->createMainRole = false;
        } else {
            // Untuk menutup komponen
            $this->userId = null;
        }
        $this->dispatch('close');
        // $this->userId = $idUser;
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

    public function editRole($idUser) {

    }

    public function deleteUser($idUser) {
        User::findOrFail($idUser)->delete();
    }
}
