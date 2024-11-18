<?php

namespace App\Livewire\Admin;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;

class Dashboard extends Component
{

    public $articles1, $articles2, $articles3, $articles4;
    public $articles1Today, $articles2Today, $articles3Today, $articles4Today;
    public $articles1Yesterday, $articles2Yesterday, $articles3Yesterday, $articles4Yesterday;
    public $yearSelected, $monthSelected;
    public $dataMes = [], $reviewSelected, $categorySelected, $yearMonths;
    public $years, $meses;

    public function __construct()
    {
        $this->yearSelected = date('Y');
        $this->yearMonths = date('Y');
    }

    public function render()
    {
        $users = User::all();
        $categories = Category::all();
        $articles = Article::all();

        $this->generateYears();
        $this->meses();

        $this->articles1 = Article::where('urlPrincipal', 'https://losandes.com.pe/')
            ->when($this->yearSelected, function ($query) {
                $query->whereYear('created_at', $this->yearSelected);
            })
            ->when($this->monthSelected, function ($query) {
                $query->whereMonth('created_at', $this->monthSelected);
            })
            ->get();

        $this->articles2 = Article::where('urlPrincipal', 'https://diariosinfronteras.com.pe/')
            ->when($this->yearSelected, function ($query) {
                $query->whereYear('created_at', $this->yearSelected);
            })
            ->when($this->monthSelected, function ($query) {
                $query->whereMonth('created_at', $this->monthSelected);
            })
            ->get();

        $this->articles3 = Article::where('urlPrincipal', 'https://larepublica.pe/')
            ->when($this->yearSelected, function ($query) {
                $query->whereYear('created_at', $this->yearSelected);
            })
            ->when($this->monthSelected, function ($query) {
                $query->whereMonth('created_at', $this->monthSelected);
            })
            ->get();

        $this->articles4 = Article::where('urlPrincipal', 'https://elcomercio.pe/')
            ->when($this->yearSelected, function ($query) {
                $query->whereYear('created_at', $this->yearSelected);
            })
            ->when($this->monthSelected, function ($query) {
                $query->whereMonth('created_at', $this->monthSelected);
            })
            ->get();

        $articlesByMonth = [];

        // Consulta el total de artículos por mes
        for ($month = 1; $month <= 12; $month++) {
            $articlesByMonth[] = Article::whereMonth('created_at', $month)
                ->when($this->reviewSelected, function ($query) {
                    $query->where('urlPrincipal', $this->reviewSelected);
                })
                ->when($this->categorySelected, function ($query) {
                    $query->where('categoria', $this->categorySelected);
                })
                ->when($this->yearMonths, function ($query) {
                    $query->whereYear('created_at', $this->yearMonths);
                })
                ->count();
        }

        $this->dataMes = $articlesByMonth;

        $this->articles1Today = Article::whereDate('created_at', Carbon::today())->where('urlPrincipal', 'https://losandes.com.pe/')->get();
        $this->articles2Today = Article::whereDate('created_at', Carbon::today())->where('urlPrincipal', 'https://diariosinfronteras.com.pe/')->get();
        $this->articles3Today = Article::whereDate('created_at', Carbon::today())->where('urlPrincipal', 'https://larepublica.pe/')->get();
        $this->articles4Today = Article::whereDate('created_at', Carbon::today())->where('urlPrincipal', 'https://elcomercio.pe/')->get();

        $this->articles1Yesterday = Article::whereDate('created_at', Carbon::yesterday())->where('urlPrincipal', 'https://losandes.com.pe/')->get();
        $this->articles2Yesterday = Article::whereDate('created_at', Carbon::yesterday())->where('urlPrincipal', 'https://diariosinfronteras.com.pe/')->get();
        $this->articles3Yesterday = Article::whereDate('created_at', Carbon::yesterday())->where('urlPrincipal', 'https://larepublica.pe/')->get();
        $this->articles4Yesterday = Article::whereDate('created_at', Carbon::yesterday())->where('urlPrincipal', 'https://elcomercio.pe/')->get();

        if ($this->yearSelected) {
            $this->dispatch('post-created', [
                'dataAll' => [
                    $this->articles1->count(),
                    $this->articles2->count(),
                    $this->articles3->count(),
                    $this->articles4->count(),
                ],
                'dataToday' => [
                    $this->articles1Today->count(),
                    $this->articles2Today->count(),
                    $this->articles3Today->count(),
                    $this->articles4Today->count(),
                ],
                'dataMonths' => $this->dataMes,
            ]);
        }

        return view('livewire.admin.dashboard', compact('users', 'categories', 'articles'));
    }

    public function generateYears()
    {
        $currentYear = Carbon::now()->year;
        $this->years = range(2020, $currentYear);
    }

    public function meses()
    {
        $this->meses = [
            ['id' => 1,  'name' => 'Enero'],
            ['id' => 2,  'name' => 'Febrero'],
            ['id' => 3,  'name' => 'Marzo'],
            ['id' => 4,  'name' => 'Abril'],
            ['id' => 5,  'name' => 'Mayo'],
            ['id' => 6,  'name' => 'Junio'],
            ['id' => 7,  'name' => 'Julio'],
            ['id' => 8,  'name' => 'Agosto'],
            ['id' => 9,  'name' => 'Septiembre'],
            ['id' => 10, 'name' => 'Octubre'],
            ['id' => 11, 'name' => 'Noviembre'],
            ['id' => 12, 'name' => 'Diciembre'],
        ];
    }
}
