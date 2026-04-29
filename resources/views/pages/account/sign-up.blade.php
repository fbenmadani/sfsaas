<?php

use Livewire\Component;

use Illuminate\Contracts\Auth\StatefulGuard;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.marketing')] class extends Component
{
    //

    public $name = '';
    public $account_name = '';
    public $subdomain = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    protected $guard;


 
    public function mount()
    {
        //
        $this->guard = app(StatefulGuard::class);
    }
    public function updatedAccountName($value)
    {
        $this->subdomain = str()->slug($value);
    }

      public function createAccount()
    {   
        $this->validate([
            'name' => 'required|string|max:255',
            'account_name' => 'required|string|max:255|unique:tenants,name',
            'subdomain' => 'required|string|max:255|unique:tenants,subdomain|regex:/^[a-zA-Z0-9-]+$/',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        


         //dd($this->subdomain);
        // Logic to create account and user...

        // Reset form fields
    //     $this->name = '';
      //  $this->account_name = '';
     //   $this->subdomain = '';
       // $this->email = '';
       // $this->password = '';
       // $this->password_confirmation = '';


       //Start Transaction
       DB::beginTransaction();
       $this->subdomain = str()->slug($this->account_name);
        $user = \App\Models\User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => bcrypt($this->password),
        ]);
        $user->save();
        $tenant = \App\Models\Tenant::create([
            'id' => $this->subdomain,
            'name' => $this->account_name,
            'subdomain' => $this->subdomain,
            'owner_id' => $user->id,
        ]);
        $user->tenant_id = $tenant->id;
        
        $user->save();
        $tenant->domains()->create([
            'domain' => $this->subdomain,
        ]);
        $appUrl = preg_replace('#https?://#', '', config('app.url'));
        


        
        //$user->save();
         //$this->guard->login$user, true);

         //Commit Transaction
         DB::commit();
        $this->redirect('http://' . $this->subdomain . '.' . $appUrl);
    
        // $redirect()->to('http://' . $this->subdomain . '.' . config('app.domain'));
        // Flash success message
       //  session()->flash('status', 'Account created successfully!');
    }
}; ?><div>
    <x-slot:title>All-in-One SMB Management</x-slot:title>
<div class="bg-background flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Create an account')" :description="__('Enter your details below to create your account')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form wire:submit="createAccount" class="flex flex-col gap-6">
            @csrf

            <!-- Name -->
            <flux:input
                name="name" wire:model="name"
                :label="__('Name')"
                :value="old('name')"
                type="text"
                required
                 

                autofocus
                autocomplete="name"
                :placeholder="__('Full name')"
            />

            <!-- Store -->
            <flux:input
                name="account_name" wire:model.live="account_name"
                :label="__('Store')"
                :value="old('account_name')"
                type="text"
                required
                autofocus
                autocomplete="account_name"
                :placeholder="__('Your store name')"
            />


            <flux:input.group>
   
    <flux:input name="subdomain"  wire:model="subdomain" required placeholder="yourstore" />
    <flux:input.group.suffix class="bg-zinc-100 dark:bg-zinc-800">.sfsaas.test</flux:input.group.suffix>
</flux:input.group>
            <!-- Email Address -->
            <flux:input
                name="email" wire:model="email"
                :label="__('Email address')"
                :value="old('email')"
                type="email"
                required
                autocomplete="email"
                placeholder="email@example.com"
            />

            <!-- Password -->
            <flux:input
                name="password" wire:model="password"
                :label="__('Password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Password')"
                viewable
            />

            <!-- Confirm Password -->
            <flux:input wire:model="password_confirmation"
                name="password_confirmation"
                :label="__('Confirm password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Confirm password')"
                viewable
            />

            <div class="flex items-center justify-end">
                <flux:button type="submit" variant="primary" class="w-full">
                    {{ __('Create account') }}
                </flux:button>
            </div>
        </form>

        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
            <span>{{ __('Already have an account?') }}</span>
            <flux:link :href="route('login')" wire:navigate>{{ __('Log in') }}</flux:link>
        </div>
    </div>
</div>
</div>
