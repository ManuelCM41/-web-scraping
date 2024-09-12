<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\DataFeed;
use App\Models\User;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $dataFeed = new DataFeed();
        $users = User::all();
        $categories = Category::all();
        return view('livewire.admin.dashboard', compact('dataFeed', 'users', 'categories'));
    }
}
