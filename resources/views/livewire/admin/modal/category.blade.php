<x-dialog-modal wire:model="isOpen" maxWidth="lg">
    <x-slot name="title">
        <i
            class="fa-solid fa-{{ $showCategory ? 'address-card fa-lg' : ($itemId ? 'user-pen' : 'user-plus') }} mr-2"></i>
        {{ $showCategory ? 'Detalle de la Categoría' : ($itemId ? 'Actualizar categoría' : 'Registrar nueva categoría') }}
    </x-slot>
    <x-slot name="content">
        <form autocomplete="off">
            <div class="flex flex-col gap-3">
                <div class="grid gap-3">
                    <x-input-label for="form.name" label="Título" wire:model.live="form.name" type="text"
                        disabled="{{ $showCategory }}" />
                </div>
            </div>
        </form>
    </x-slot>
    <x-slot name="footer">
        <button data-ripple-dark="true" x-on:click="show = false" wire:click="closeModals"
            class="px-4 py-2.5 mr-1 font-sans text-xs font-bold text-red-500 uppercase transition-all rounded-lg middle none center hover:bg-red-500/10 active:bg-red-500/30 disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none">
            Cancelar
        </button>

        @if (!$showCategory)
            <x-button-gradient color="green" wire:click="store()" wire:loading.attr="disabled" wire:target="store">
                <span wire:loading wire:target="store" class="mr-2">
                    <i class="fa fa-spinner fa-spin"></i>
                </span>
                {{ $itemId ? 'Actualizar' : 'Registrar' }}
            </x-button-gradient>
        @endif
    </x-slot>
</x-dialog-modal>
