<div>
    <x-loading-indicator />

    <!--Card body-->
    <div class="card">
        <div class="card-body">
            @includeIf($actions['view']['form'])

            <div class="pt-4">
                @if (isset($actions['id']))
                {{-- 삭제는 확인컨펌을 통하여 삭제처리 --}}
                    @if ($confirm)
                        <button type="button" class="btn btn-soft-danger" wire:click="delete">삭제</button>
                        <span>정말로 삭제할까요?</span>
                    @else
                        <button type="button" class="btn btn-danger" wire:click="deleteConfirm">삭제</button>
                    @endif

                    <button type="button" class="btn btn-success" wire:click="submit">수정</button>
                @else
                    <button type="button" class="btn btn-secondary" wire:click="clear">취소</button>
                    <button type="button" class="btn btn-primary" wire:click="submit">저장</button>
                @endif
            </div>
        </div>
    </div>

    @once
        @push('scripts')
        <script>
            // Component Script
        </script>
        @endpush
    @endonce

    {{-- 퍼미션 알람 --}}
    @include('jiny-wire-table::error.popup.permit')

</div>
