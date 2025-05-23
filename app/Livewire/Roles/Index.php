<?php

namespace App\Livewire\Roles;

use Livewire\Attributes\{On, Url};
use Livewire\Component;
use App\Models\Role;
use Livewire\{WithPagination};

class Index extends Component
{
    use WithPagination;

    public $role = [];
    public $roleId = null;
    public $name = "";
    public $create = false;
    public $edit = false;
    public $createSuccess = false;

    #[Url]
    public ?string $search = '';

    public function mount() {

    }
    public function openModalCreate() {
        $this->create = true;
    }

    public function editRole($idRole) {
        $this->edit = true;
    }

    #[On('close-edited')]
    public function closeEdit() {
        $this->edit = false;
    }

    public function closeCreateForm() {
         $this->create = false;
        $this->reset('name');
    }

    public function updatetingCreate() {
        $this->resetPage();
    }

        public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
          $query = Role::query();

        if (!empty(trim($this->search))) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }
            $query = $query->latest();

        return view('livewire.roles.index', [
            'listRole' => $query->paginate(5)
        ]);
    }

    public function cancelSend() {
        $this->reset('name');
        $this->resetErrorBag();
    }

       public function storeDataRole() {
        $this->validate([
            'name' => ['required','min:5', 'string', 'unique:' . Role::class],
        ], [
            'name.required' => 'nama jabatan wajib diisi..',
            'name.min'      => 'Nama jabatan minimal 5 karakter..',
            'name.unique'   => 'Nama jabatan sudah dipakai..',
            'name.string'   => 'Nama Jabatan harus huruf'
        ]);

        Role::create([
            'name'  => $this->name
        ]);
        // $this->dispatch('role-created');
        $this->resetErrorBag('name');
        $this->closeCreateForm();
    }

    public function deleteRole($idRole) {
        Role::findOrFail($idRole)->delete();

    }
}
