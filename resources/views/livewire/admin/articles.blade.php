@section('header', __('Tabla'))
@section('section', __('Usuarios'))

<div>

    @include('livewire.admin.modal.article')

    @include('livewire.admin.modal.delete')

    <x-card>
        <div class="relative flex flex-col w-full h-full text-gray-700 dark:text-gray-400">
            <div class="flex flex-col justify-between gap-4 md:flex-row md:items-center mb-3">
                <div class="w-full md:w-72">
                    <x-input-label wire:model.live="search" search label="Buscar" />
                </div>

                <div class="flex gap-2 justify-center">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <span class="inline-flex">
                                <button aria-label="user"
                                    class="px-4 py-2 flex gap-1 items-center rounded-lg bg-gradient-to-r from-emerald-700 to-green-600 focus:from-emerald-700 focus:to-green-600 active:from-green-600 active:to-green-600 text-sm text-white font-semibold tracking-wide cursor-pointer shadow-lg"
                                    type="button">Descargar
                                    <i class="fa-solid fa-chevron-down ms-3"></i>
                                </button>
                            </span>
                        </x-slot>

                        <x-slot name="content">
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('CSV/EXCEL/PDF') }}
                            </div>

                            <button wire:click="createCSV()" target="_blank"
                                class="px-4 py-2 flex gap-2 w-full items-center hover:text-green-600 hover:bg-gray-100 tracking-wide">
                                <i class="fa-regular fa-file-excel"></i> CSV
                            </button>

                            <button wire:click="createExcel()" target="_blank"
                                class="px-4 py-2 flex gap-2 w-full items-center hover:text-green-600 hover:bg-gray-100 tracking-wide">
                                <i class="fa-regular fa-file-excel"></i> EXCEL
                            </button>

                            <a href="{{ URL::to('/articulos/pdf') }}" target="_blank"
                                class="px-4 py-2 flex gap-2 w-full items-center hover:text-blue-700 hover:bg-gray-100 tracking-wide">
                                <i class="fa-regular fa-file-lines"></i> PDF
                            </a>
                        </x-slot>
                    </x-dropdown>

                    @can('admin.articles.create')
                        <x-button-gradient class="flex items-center gap-2" wire:click="create()">
                            <i class="fa-solid fa-plus"></i>
                            <span class="hidden sm:block">Nuevo</span>
                        </x-button-gradient>
                    @endcan
                </div>
            </div>
            <x-table-container>
                <div wire:loading wire:target="articleState, search" class="absolute w-full h-full z-10 pt-10">
                    <div class="relative h-full w-full">
                        <div class="absolute inset-0 bg-white bg-opacity-50 backdrop-blur-[2px]"></div>
                        <div class="absolute inset-0 flex justify-center items-center bg-opacity-0">
                            <div>
                                <i class="fa fa-spinner fa-spin"></i> Cargando...
                            </div>
                        </div>
                    </div>
                </div>
                <table class="w-full text-left table-auto min-w-max">
                    <x-table-thead>
                        <tr>
                            <th class="p-3 font-normal text-center">Título</th>
                            <th class="p-3 font-normal">Imagen</th>
                            <th class="p-3 font-normal">Url</th>
                            <th class="p-3 font-normal">Extracto</th>
                            <th class="p-3 font-normal">Categoría</th>
                            <th class="p-3 font-normal">Autor</th>
                            <th class="p-3 font-normal">Fecha</th>
                            <th class="p-3 font-normal">Actualizado</th>
                            <th class="p-3 font-normal text-center">Acciones</th>
                        </tr>
                    </x-table-thead>
                    <tbody class="text-sm divide-y divide-gray-300">
                        @foreach ($articles as $article)
                            <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                <td class="p-3">{{ $article->titulo }}</td>
                                <td class="p-3">
                                    <div class="flex gap-3">
                                        <div class="relative">
                                            <img class="w-10 h-10 border-2 rounded-full object-cover"
                                                src="{{ $article->imagen }}" alt="{{ $article->titulo }}" />
                                        </div>
                                    </div>
                                </td>
                                <td class="p-3">
                                    <a href="{{ $article->url }}" target="_blank" class="hover:text-blue-700">
                                        Link
                                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                    </a>
                                </td>
                                <td class="p-3">{{ $article->extracto }}</td>
                                <td class="p-3">{{ $article->categoria }}</td>
                                <td class="p-3">{{ $article->autor }}</td>
                                <td class="p-3">{{ $article->fecha }}</td>
                                <td class="p-3">
                                    <div>
                                        <i class="fa-regular fa-calendar fa-fw"></i>
                                        {{ \Carbon\Carbon::parse($article->updated_at)->format('d-m-Y') }}
                                    </div>
                                    <div>
                                        <i class="fa-regular fa-clock fa-fw"></i>
                                        {{ \Carbon\Carbon::parse($article->updated_at)->format('H:i:s') }}
                                    </div>
                                </td>
                                <td class="p-3 w-10">
                                    <div class="flex justify-center relative">
                                        @can('admin.articles.show')
                                            <x-button-tooltip hover="blue" content="Visualizar"
                                                wire:click="showArticleDetail({{ $article }})">
                                                <i class="fa-solid fa-eye fa-fw"></i>
                                            </x-button-tooltip>
                                        @endcan
                                        @can('admin.articles.edit')
                                            <x-button-tooltip hover="green" content="Editar"
                                                wire:click="edit({{ $article }})">
                                                <i class="fa-solid fa-pen fa-fw"></i>
                                            </x-button-tooltip>
                                        @endcan
                                        @can('admin.articles.delete')
                                            <x-button-tooltip hover="red" content="Eliminar"
                                                wire:click="deleteItem({{ $article->id }})">
                                                <i class="fa-solid fa-trash-can fa-fw"></i>
                                            </x-button-tooltip>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        @if (!$articles->count())
                            <tr>
                                <td colspan="7" class="p-3 text-center text-sm">
                                    No existe ningún registro coincidente con la búsqueda.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </x-table-container>
            @if ($articles->count())
                {{ $articles->links() }}
            @endif
        </div>
    </x-card>
</div>
