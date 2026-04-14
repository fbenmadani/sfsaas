<?php

use Livewire\Component;

use App\Models\User;

new class extends Component
{
    //

    public $users;

    public function mount(){
        $this->users = User::all();
    }
};
?>

<div>
    List All  Users
    @foreach ($users as $user)
        <p>{{ $user->name }}</p>
    @endforeach
</div>