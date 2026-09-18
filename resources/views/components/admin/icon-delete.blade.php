@props(['action', 'message' => 'Delete this item?'])

<form method="POST" action="{{ $action }}" class="icon-form">
    @csrf
    @method('DELETE')
    <button type="button" class="icon-btn icon-btn-delete" data-confirm="{{ $message }}" title="Delete" aria-label="Delete">
        <x-icon name="trash" />
    </button>
</form>
