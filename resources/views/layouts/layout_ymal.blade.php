<x-theme theme="admin.sidebar">
    {{-- 설정 파일을 생성할 수 있는 출력 템플릿 --}}
    <x-theme-layout>

        {{-- Title --}}
        @if(isset($actions['view']['title']))
            @includeIf($actions['view']['title'])
        @else
            @includeIf("jiny-wire-table::table_popup_forms.title")
        @endif

        @livewire('WireConfigYaml', ['actions'=>$actions])

        {{-- SuperAdmin Actions Setting --}}
        {{-- @if(Module::has('Actions'))
            @livewire('setActionRule', ['actions'=>$actions])
        @endif --}}

    </x-theme-layout>
</x-theme>
