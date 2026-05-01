<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;

class AdminProfile extends Component
{
    public string $name  = '';
    public string $email = '';
    public string $phone = '';

    public string $current_password     = '';
    public string $new_password         = '';
    public string $new_password_confirm = '';

    public function mount(): void
    {
        $user        = auth()->user();
        $this->name  = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone ?? '';
    }

    public function saveProfile(): void
    {
        $user = auth()->user();

        $this->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update([
            'name'  => $this->name,
            'email' => $this->email,
            'phone' => $this->phone ?: null,
        ]);

        session()->flash('success', 'Profile updated.');
    }

    public function savePassword(): void
    {
        $this->validate([
            'current_password'     => 'required',
            'new_password'         => ['required', 'min:8', Password::defaults()],
            'new_password_confirm' => 'required|same:new_password',
        ]);

        $user = auth()->user();

        if (!Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'Current password is incorrect.');
            return;
        }

        $user->update(['password' => Hash::make($this->new_password)]);

        $this->current_password     = '';
        $this->new_password         = '';
        $this->new_password_confirm = '';

        session()->flash('success', 'Password changed.');
    }

    public function render()
    {
        return view('livewire.admin.admin-profile');
    }
}
