<x-theme theme="admin.sidebar">
    {{-- 설정 파일을 생성할 수 있는 출력 템플릿 --}}
    <x-theme-layout>

        <!-- Module Title Bar -->
        @if(Module::has('Titlebar'))
            @livewire('TitleBar', ['actions'=>$actions])
        @endif
        <!-- end -->

        @livewire('WireConfigIni', ['actions'=>$actions])

        {{-- SuperAdmin Actions Setting --}}
        @if(Module::has('Actions'))
            @livewire('setActionRule', ['actions'=>$actions])
        @endif

    </x-theme-layout>
</x-theme>
