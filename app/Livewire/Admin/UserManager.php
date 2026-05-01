<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;

class UserManager extends Component
{
    use WithPagination;

    public string $search = '';
    public string $modal  = ''; // create | edit | delete

    // Form fields
    public ?int   $editId   = null;
    public string $name     = '';
    public string $email    = '';
    public string $password = '';
    public bool   $is_admin = false;

    public function updatedSearch(): void { $this->resetPage(); }

    protected function rules(): array
    {
        $emailRule = $this->editId
            ? 'required|email|unique:users,email,' . $this->editId
            : 'required|email|unique:users,email';

        $passwordRule = $this->editId
            ? 'nullable|string|min:8'
            : 'required|string|min:8';

        return [
            'name'     => 'required|string|max:255',
            'email'    => $emailRule,
            'password' => $passwordRule,
            'is_admin' => 'boolean',
        ];
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->modal = 'create';
    }

    public function openEdit(int $id): void
    {
        // Prevent editing yourself
        if ($id === auth()->id()) {
            session()->flash('error', 'You cannot edit your own account here.');
            return;
        }
        $user           = User::findOrFail($id);
        $this->editId   = $user->id;
        $this->name     = $user->name;
        $this->email    = $user->email;
        $this->password = '';
        $this->is_admin = (bool) $user->is_admin;
        $this->modal    = 'edit';
    }

    public function openDelete(int $id): void
    {
        if ($id === auth()->id()) {
            session()->flash('error', 'You cannot delete your own account.');
            return;
        }
        $this->editId = $id;
        $this->modal  = 'delete';
    }

    public function save(): void
    {
        $this->validate();

        if ($this->editId) {
            $data = [
                'name'     => $this->name,
                'email'    => $this->email,
                'is_admin' => $this->is_admin,
            ];
            if ($this->password) {
                $data['password'] = Hash::make($this->password);
            }
            User::findOrFail($this->editId)->update($data);
            session()->flash('success', 'User updated.');
        } else {
            User::create([
                'name'     => $this->name,
                'email'    => $this->email,
                'password' => Hash::make($this->password),
                'is_admin' => $this->is_admin,
            ]);
            session()->flash('success', 'User created.');
        }

        $this->closeModal();
    }

    public function delete(): void
    {
        $user = User::findOrFail($this->editId);

        if ($user->id === auth()->id()) {
            session()->flash('error', 'You cannot delete your own account.');
            $this->closeModal();
            return;
        }

        $user->delete();
        session()->flash('success', 'User deleted.');
        $this->closeModal();
    }

    public function toggleRole(int $id): void
    {
        if ($id === auth()->id()) {
            session()->flash('error', 'You cannot change your own role.');
            return;
        }
        $user = User::findOrFail($id);
        $user->update(['is_admin' => !$user->is_admin]);
        session()->flash('success', 'Role updated.');
    }

    public function closeModal(): void
    {
        $this->modal = '';
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->editId   = null;
        $this->name     = '';
        $this->email    = '';
        $this->password = '';
        $this->is_admin = false;
        $this->resetValidation();
    }

    public function render()
    {
        $users = User::withCount('orders')
            ->when($this->search, fn($q) => $q->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            }))
            ->latest()
            ->paginate(20);

        return view('livewire.admin.user-manager', compact('users'));
    }
}
