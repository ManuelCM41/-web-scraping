<?php

namespace App\Livewire\Admin;

use App\Models\Article;
use App\Models\Category;
use App\Models\DataFeed;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;

class Dashboard extends Component
{

    public $articles1, $articles2, $articles3;
    public $articles1Today, $articles2Today, $articles3Today;
    public $articles1Yesterday, $articles2Yesterday, $articles3Yesterday;

    public function render()
    {
        $dataFeed = new DataFeed();
        $users = User::all();
        $categories = Category::all();
        $articles = Article::all();

        $this->articles1 = Article::where('urlPrincipal', 'https://losandes.com.pe/')->get();
        $this->articles2 = Article::where('urlPrincipal', 'https://diariosinfronteras.com.pe/')->get();
        $this->articles3 = Article::where('urlPrincipal', 'https://larepublica.pe/')->get();

        $this->articles1Today = Article::whereDate('created_at', Carbon::today())->where('urlPrincipal', 'https://losandes.com.pe/')->get();
        $this->articles2Today = Article::whereDate('created_at', Carbon::today())->where('urlPrincipal', 'https://diariosinfronteras.com.pe/')->get();
        $this->articles3Today = Article::whereDate('created_at', Carbon::today())->where('urlPrincipal', 'https://larepublica.pe/')->get();

        $this->articles1Yesterday = Article::whereDate('created_at', Carbon::yesterday())->where('urlPrincipal', 'https://losandes.com.pe/')->get();
        $this->articles2Yesterday = Article::whereDate('created_at', Carbon::yesterday())->where('urlPrincipal', 'https://diariosinfronteras.com.pe/')->get();
        $this->articles3Yesterday = Article::whereDate('created_at', Carbon::yesterday())->where('urlPrincipal', 'https://larepublica.pe/')->get();

        return view('livewire.admin.dashboard', compact('dataFeed', 'users', 'categories', 'articles'));
    }
}
