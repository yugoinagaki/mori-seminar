<x-filament-panels::page>
    <div class="flex flex-col items-center gap-6 py-8">
        <div @class([
            'flex items-center gap-3 rounded-xl px-6 py-4 text-sm font-medium',
            'bg-danger-50 text-danger-700 dark:bg-danger-950 dark:text-danger-300' => $isMaintenance,
            'bg-success-50 text-success-700 dark:bg-success-950 dark:text-success-300' => ! $isMaintenance,
        ])>
            @if ($isMaintenance)
                <x-heroicon-o-wrench-screwdriver class="h-5 w-5" />
                <span>メンテナンスモード: <strong>有効</strong> — サイトは一般公開されていません</span>
            @else
                <x-heroicon-o-check-circle class="h-5 w-5" />
                <span>メンテナンスモード: <strong>無効</strong> — サイトは通常公開中です</span>
            @endif
        </div>

        <p class="max-w-md text-center text-sm text-gray-500 dark:text-gray-400">
            有効にすると、すべてのアクセスがメンテナンス画面にリダイレクトされます。<br>
            PHPを経由しないため、PHPの脆弱性対応中でも安全に使用できます。
        </p>

        @if ($isMaintenance)
            <x-filament::button
                wire:click="disable"
                color="success"
                size="lg"
                icon="heroicon-o-check-circle"
            >
                メンテナンスモードを解除する
            </x-filament::button>
        @else
            <x-filament::button
                wire:click="enable"
                color="danger"
                size="lg"
                icon="heroicon-o-wrench-screwdriver"
            >
                メンテナンスモードを有効にする
            </x-filament::button>
        @endif
    </div>
</x-filament-panels::page>
