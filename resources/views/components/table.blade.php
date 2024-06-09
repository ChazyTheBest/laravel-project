<div class="align-middle min-w-full overflow-x-auto shadow overflow-hidden sm:rounded-lg">
    <table class="w-full divide-y divide-gray-200">
        @if (!empty($header))
            <thead class="table-head">
                {{ $header }}
            </thead>
        @endif
        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200">
            {{ $body ?? '' }}
        </tbody>
        @if (!empty($footer))
            <tfoot class="table-foot">
                {{ $footer }}
            </tfoot>
        @endif
    </table>
</div>