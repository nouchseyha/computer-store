<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;

class UserProfile extends Component
{
    public string $name    = '';
    public string $email   = '';
    public string $phone   = '';
    public string $address = '';

    public string $current_password     = '';
    public string $new_password         = '';
    public string $new_password_confirm = '';

    public string $activeTab = 'profile';

    public function mount(): void
    {
        $user          = auth()->user();
        $this->name    = $user->name;
        $this->email   = $user->email;
        $this->phone   = $user->phone   ?? '';
        $this->address = $user->address ?? '';
    }

    public function saveProfile(): void
    {
        $user = auth()->user();

        $this->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:users,email,' . $user->id,
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $user->update([
            'name'    => $this->name,
            'email'   => $this->email,
            'phone'   => $this->phone   ?: null,
            'address' => $this->address ?: null,
        ]);

        session()->flash('profile_success', 'Profile updated successfully.');
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

        session()->flash('password_success', 'Password changed successfully.');
    }

    public function render()
    {
        $orders = auth()->user()->orders()->latest()->take(5)->get();
        return view('livewire.user-profile', compact('orders'));
    }
}
