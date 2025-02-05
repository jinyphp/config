<x-admin>
    <x-flex-between>
        <div class="page-title-box">
            <x-flex class="align-items-center gap-2">
                <h1 class="align-middle h3 d-inline">
                    @if (isset($actions['title']))
                        {{ $actions['title'] }}
                    @endif
                </h1>

            </x-flex>
            <p>
                @if (isset($actions['subtitle']))
                    {{ $actions['subtitle'] }}
                @endif
            </p>
        </div>

        <div class="page-title-box">
            <x-breadcrumb-item>
                {{ $actions['route']['uri'] }}
            </x-breadcrumb-item>

            <div class="mt-2 d-flex justify-content-end gap-2">
                <button class="btn btn-sm btn-danger">Video</button>
                <button class="btn btn-sm btn-secondary">Manual</button>
            </div>
        </div>
    </x-flex-between>

    @livewire('WireConfigPHP', ['actions' => $actions])

</x-admin>
