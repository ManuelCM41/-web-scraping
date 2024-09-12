<x-dialog-modal wire:model="isOpen" maxWidth="lg">
    <x-slot name="title">
        <i class="fa-solid fa-{{ $showArticle ? 'address-card fa-lg' : ($itemId ? 'user-pen' : 'user-plus') }} mr-2"></i>
        {{ $showArticle ? 'Detalle del artículo' : ($itemId ? 'Actualizar artículo' : 'Registrar nuevo artículo') }}
    </x-slot>
    <x-slot name="content">
        <form autocomplete="off">
            <div class="flex flex-col gap-3">
                <div class="grid {{ $itemId ? 'grid-cols-3' : 'grid-cols-1' }} gap-3">
                    <div class="col-span-2 flex flex-col gap-3">
                        <x-input-label for="form.name" label="Título" wire:model.live="form.titulo" type="text"
                            disabled="{{ $showArticle }}" />
                        <x-input-label for="form.url" label="Url" wire:model.live="form.url" type="text"
                            disabled="{{ $showArticle }}" />
                        <x-input-label for="form.urlPrincipal" label="Url Principal" wire:model.live="form.urlPrincipal"
                            type="text" disabled="{{ $showArticle }}" />
                    </div>
                </div>
                <div class="grid sm:grid-cols-2 gap-3">
                    <x-input-label for="form.path" label="Ruta" wire:model.live="form.path"
                        type="text" disabled="{{ $showArticle }}" />
                    <x-input-label for="form.extracto" label="Extracto" wire:model.live="form.extracto"
                        type="text" disabled="{{ $showArticle }}" />
                </div>
                <div class="grid sm:grid-cols-2 gap-3">
                    <x-input-label for="form.categoria" label="Categoría" wire:model.live="form.categoria"
                        type="text" disabled="{{ $showArticle }}" />
                    <x-input-label for="form.autor" label="Extracto" wire:model.live="form.autor"
                        type="text" disabled="{{ $showArticle }}" />
                </div>
            </div>
        </form>
    </x-slot>
    <x-slot name="footer">
        <button data-ripple-dark="true" x-on:click="show = false" wire:click="closeModals"
            class="px-4 py-2.5 mr-1 font-sans text-xs font-bold text-red-500 uppercase transition-all rounded-lg middle none center hover:bg-red-500/10 active:bg-red-500/30 disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none">
            Cancelar
        </button>

        @if (!$showArticle)
            <x-button-gradient color="green" wire:click="store()" wire:loading.attr="disabled" wire:target="store">
                <span wire:loading wire:target="store" class="mr-2">
                    <i class="fa fa-spinner fa-spin"></i>
                </span>
                {{ $itemId ? 'Actualizar' : 'Registrar' }}
            </x-button-gradient>
        @endif
    </x-slot>
    {{-- <script>
        const selectElement = document.getElementById('custom-select');

        selectElement.addEventListener('change', function() {
            const options = selectElement.querySelectorAll('.custom-option');
            options.forEach(option => option.classList.remove('selected'));

            const selectedOption = selectElement.querySelector(`option[value="${selectElement.value}"]`);
            selectedOption.classList.add('selected');
        });
    </script> --}}
</x-dialog-modal>
