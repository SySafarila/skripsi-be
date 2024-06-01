<div class="d-flex flex-column flex-md-row justify-content-center" style="gap: 0.5rem">
    @if ($model->id != 1)
        @can('tendik-positions-update')
            <a href="{{ route('admin.tendik-positions.edit', $model->id) }}" class="btn btn-sm btn-secondary">Edit</a>
        @endcan
        @can('tendik-positions-delete')
            <span class="btn btn-sm btn-danger" id="deleteButton" data-model-id="{{ $model->id }}">Delete</span>
        @endcan
    @endif
</div>
