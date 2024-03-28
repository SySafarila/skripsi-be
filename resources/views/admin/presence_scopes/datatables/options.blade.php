<div class="d-flex flex-column flex-md-row justify-content-center" style="gap: 0.5rem">
    @can('presence-scopes-update')
        <a href="{{ route('admin.presence-scopes.edit', $model->id) }}" class="btn btn-sm btn-secondary">Edit</a>
    @endcan
    @can('presence-scopes-delete')
        <span class="btn btn-sm btn-danger" id="deleteButton" data-model-id="{{ $model->id }}">Delete</span>
    @endcan
</div>
