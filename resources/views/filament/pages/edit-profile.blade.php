<x-filament-panels::page>
    <form wire:submit="saveDetailsForm">
        {{ $this->detailsForm }}

        <x-filament::button class="mt-6" type="submit">
            Save changes
        </x-filament::button>
    </form>

    <form wire:submit="saveUpdatePasswordForm">
        {{ $this->updatePasswordForm }}

        <x-filament::button class="mt-6" type="submit">
            Save changes
        </x-filament::button>
    </form>
</x-filament-panels::page>
