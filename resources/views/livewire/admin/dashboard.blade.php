@section('header', __('Dashboard'))
@section('section', __('General'))

<div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 mb-4">
        <x-card>
            <x-card-counter-section url="{{ route('admin.users') }}" label="Usuarios" counter="{{ $users->count() }}"
                color="blue" />
        </x-card>
        <x-card>
            <x-card-counter-section url="{{ route('admin.categories') }}" label="Categorias"
                counter="{{ $categories->count() }}" color="red" />
        </x-card>
        <x-card>
            <x-card-counter-section url="{{ route('admin.articles') }}" label="Noticias"
                counter="{{ $articles->count() }}" color="yellow" />
        </x-card>
        <x-card>
            <x-card-counter-section url="#0" label="Usuarios" counter="{{ $users->count() }}" color="green" />
        </x-card>
    </div>

    <div class="grid grid-cols-3 gap-4 mb-4">
        <div class="flex flex-col gap-4">
            <x-card>
                <div>
                    <header class="pb-4 border-b border-gray-100 dark:border-gray-700/60">
                        <h2 class="font-semibold text-gray-800 dark:text-gray-100">Resumen de Noticias</h2>
                    </header>
                    <div class="grow flex flex-col justify-center mt-3">
                        <div>
                            <canvas id="chartBar" width="389" height="260"></canvas>
                        </div>
                    </div>
                </div>
            </x-card>
            <x-card>
                <div>
                    <header class="pb-4 border-b border-gray-100 dark:border-gray-700/60">
                        <h2 class="font-semibold text-gray-800 dark:text-gray-100">Scrapeo de Noticias de hoy</h2>
                    </header>
                    <div class="grow flex flex-col justify-center mt-3">
                        <div>
                            <canvas id="polarArea" width="389" height="260"></canvas>
                        </div>
                    </div>
                </div>
            </x-card>
        </div>
        <div class="flex flex-col gap-4 col-span-2">
            <x-card>
                <header class="pb-4 border-b border-gray-100 dark:border-gray-700/60">
                    <h2 class="font-semibold text-gray-800 dark:text-gray-100">Actividad Reciente</h2>
                </header>
                <div class="mt-3">
                    <div>
                        <header
                            class="text-xs uppercase text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-700 dark:bg-opacity-50 rounded-sm font-semibold p-2">
                            Today</header>
                        <ul class="my-1">
                            <!-- Item -->
                            <li class="flex px-2">
                                <div
                                    class="w-9 h-9 rounded-full shrink-0 bg-red-500 my-2 mr-3 text-white flex items-center justify-center">
                                    <i class="fa-solid fa-dove fa-fw"></i>
                                </div>
                                <div
                                    class="grow flex items-center border-b border-gray-100 dark:border-gray-700/60 text-sm py-2">
                                    <div class="grow flex justify-between">
                                        <div class="self-center flex gap-1">
                                            En el diario <b>Los Andes</b> se escrapeo
                                            <x-counter-animation>{{ $articles1Today->count() }}</x-counter-animation>
                                            noticias en total
                                        </div>
                                        <div class="shrink-0 self-end ml-2">
                                            <a class="font-medium text-violet-500 hover:text-violet-600 dark:hover:text-violet-400"
                                                href="#0">View<span class="hidden sm:inline"> -&gt;</span></a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <!-- Item -->
                            <li class="flex px-2">
                                <div
                                    class="w-9 h-9 rounded-full shrink-0 bg-blue-500 my-2 mr-3 text-white flex items-center justify-center">
                                    <i class="fa-solid fa-arrow-down-up-across-line fa-fw"></i>
                                </div>
                                <div
                                    class="grow flex items-center border-b border-gray-100 dark:border-gray-700/60 text-sm py-2">
                                    <div class="grow flex justify-between">
                                        <div class="self-center flex gap-1">
                                            En el diario <b>Sin Fronteras</b> se escrapeo
                                            <x-counter-animation>{{ $articles2Today->count() }}</x-counter-animation>
                                            noticias en total
                                        </div>
                                        <div class="shrink-0 self-end ml-2">
                                            <a class="font-medium text-violet-500 hover:text-violet-600 dark:hover:text-violet-400"
                                                href="#0">View<span class="hidden sm:inline"> -&gt;</span></a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <!-- Item -->
                            <li class="flex px-2">
                                <div
                                    class="w-9 h-9 rounded-full shrink-0 bg-yellow-500 my-2 mr-3 text-white flex items-center justify-center">
                                    <i class="fa-solid fa-building-columns fa-fw"></i>
                                </div>
                                <div class="grow flex items-center text-sm py-2">
                                    <div class="grow flex justify-between">
                                        <div class="self-center flex gap-1">
                                            En el diario <b>La República</b> se escrapeo
                                            <x-counter-animation>{{ $articles3Today->count() }}</x-counter-animation>
                                            noticias en total
                                        </div>
                                        <div class="shrink-0 self-end ml-2">
                                            <a class="font-medium text-violet-500 hover:text-violet-600 dark:hover:text-violet-400"
                                                href="#0">View<span class="hidden sm:inline"> -&gt;</span></a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <!-- "Yesterday" group -->
                    <div>
                        <header
                            class="text-xs uppercase text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-700 dark:bg-opacity-50 rounded-sm font-semibold p-2">
                            Yesterday</header>
                        <ul class="my-1">
                            <!-- Item -->
                            <li class="flex px-2">
                                <div
                                    class="w-9 h-9 rounded-full shrink-0 bg-red-500 my-2 mr-3 text-white flex items-center justify-center">
                                    <i class="fa-solid fa-dove fa-fw"></i>
                                </div>
                                <div
                                    class="grow flex items-center border-b border-gray-100 dark:border-gray-700/60 text-sm py-2">
                                    <div class="grow flex justify-between">
                                        <div class="self-center flex gap-1">
                                            En el diario <b>Los Andes</b> se obtuvo del escrapeo
                                            @if ($articles1Yesterday->count() <= 2)
                                                {{ $articles1Yesterday->count() }}
                                            @else
                                                <x-counter-animation>{{ $articles1Yesterday->count() }}</x-counter-animation>
                                            @endif
                                            noticias en total
                                        </div>
                                        <div class="shrink-0 self-end ml-2">
                                            <a class="font-medium text-violet-500 hover:text-violet-600 dark:hover:text-violet-400"
                                                href="#0">View<span class="hidden sm:inline"> -&gt;</span></a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <!-- Item -->
                            <li class="flex px-2">
                                <div
                                    class="w-9 h-9 rounded-full shrink-0 bg-blue-500 my-2 mr-3 text-white flex items-center justify-center">
                                    <i class="fa-solid fa-arrow-down-up-across-line fa-fw"></i>
                                </div>
                                <div
                                    class="grow flex items-center border-b border-gray-100 dark:border-gray-700/60 text-sm py-2">
                                    <div class="grow flex justify-between">
                                        <div class="self-center flex gap-1">
                                            En el diario <b>Sin Fronteras</b> se obtuvo del escrapeo
                                            @if ($articles2Yesterday->count() <= 2)
                                                {{ $articles2Yesterday->count() }}
                                            @else
                                                <x-counter-animation>{{ $articles2Yesterday->count() }}</x-counter-animation>
                                            @endif
                                            noticias en total
                                        </div>
                                        <div class="shrink-0 self-end ml-2">
                                            <a class="font-medium text-violet-500 hover:text-violet-600 dark:hover:text-violet-400"
                                                href="#0">View<span class="hidden sm:inline"> -&gt;</span></a>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li class="flex px-2">
                                <div
                                    class="w-9 h-9 rounded-full shrink-0 bg-yellow-500 my-2 mr-3 text-white flex items-center justify-center">
                                    <i class="fa-solid fa-building-columns fa-fw"></i>
                                </div>
                                <div class="grow flex items-center text-sm py-2">
                                    <div class="grow flex justify-between">
                                        <div class="self-center flex gap-1">
                                            En el diario <b>La República</b> se obtuvo del escrapeo
                                            @if ($articles3Yesterday->count() <= 2)
                                                {{ $articles3Yesterday->count() }}
                                            @else
                                                <x-counter-animation>{{ $articles3Yesterday->count() }}</x-counter-animation>
                                            @endif
                                            noticias en total
                                        </div>
                                        <div class="shrink-0 self-end ml-2">
                                            <a class="font-medium text-violet-500 hover:text-violet-600 dark:hover:text-violet-400"
                                                href="#0">View<span class="hidden sm:inline"> -&gt;</span></a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </x-card>
            <x-card>
                <div>
                    <header class="pb-4 border-b border-gray-100 dark:border-gray-700/60">
                        <h2 class="font-semibold text-gray-800 dark:text-gray-100">Resumen de Noticias por meses</h2>
                    </header>
                    <div class="grow flex flex-col justify-center mt-3">
                        <div>
                            <canvas id="chartBarMonths" width="389" height="150"></canvas>
                        </div>
                    </div>
                </div>
            </x-card>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const chartBardata = {
            labels: ["Los Andes", "Sin Fronteras", "La Republica"],
            datasets: [{
                label: 'Total de Noticias',
                data: [{{ $articles1->count() }}, {{ $articles2->count() }}, {{ $articles3->count() }}],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.5)',
                    'rgba(54, 162, 235, 0.5)',
                    'rgba(255, 205, 86, 0.5)'
                ],
                borderColor: [
                    'rgb(255, 99, 132)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 205, 86)'
                ],
                borderWidth: 1
            }]
        };

        const configchartBar = {
            type: 'bar',
            data: chartBardata,
            options: {},
        };

        var chartBar = new Chart(
            document.getElementById("chartBar"),
            configchartBar
        );
    </script>

    <script>
        const polarAreadata = {
            labels: ["Los Andes", "Sin Fronteras", "La Republica"],
            datasets: [{
                label: 'Total de Noticias',
                data: [{{ $articles1Today->count() }}, {{ $articles2Today->count() }},
                    {{ $articles3Today->count() }}
                ],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.5)',
                    'rgba(54, 162, 235, 0.5)',
                    'rgba(255, 205, 86, 0.5)'
                ],
                borderColor: [
                    'rgb(255, 99, 132)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 205, 86)'
                ],
                borderWidth: 1
            }]
        };

        const configpolarArea = {
            type: 'polarArea',
            data: polarAreadata,
            options: {},
        };

        var polarArea = new Chart(
            document.getElementById("polarArea"),
            configpolarArea
        );
    </script>

    <script>
        const chartBarMonthsdata = {
            labels: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Setiembre", "Octubre", "Noviembre", "Diciembre"],
            datasets: [{
                label: 'Total de Noticias',
                data: [65, 59, 80, 81, 56, 55, 40, 65, 59, 80, 81, 56],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(255, 205, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(201, 203, 207, 0.2)'
                ],
                borderColor: [
                    'rgb(255, 99, 132)',
                    'rgb(255, 159, 64)',
                    'rgb(255, 205, 86)',
                    'rgb(75, 192, 192)',
                    'rgb(54, 162, 235)',
                    'rgb(153, 102, 255)',
                    'rgb(201, 203, 207)'
                ],
                borderWidth: 1
            }]
        };

        const configchartBarMonths = {
            type: 'bar',
            data: chartBarMonthsdata,
            options: {},
        };

        var chartBar = new Chart(
            document.getElementById("chartBarMonths"),
            configchartBarMonths
        );
    </script>

</div>
