<div class="d-flex flex-column flex-md-row justify-content-center" style="gap: 0.5rem">
    {{-- @can('feedbacks-update')
        <a href="{{ route('admin.feedbacks.edit', $model->id) }}" class="btn btn-sm btn-secondary">Edit</a>
    @endcan --}}
    @can('feedbacks-delete')
        <span class="btn btn-sm btn-danger" id="deleteButton" data-model-id="{{ $model->id }}">Delete</span>
    @endcan
</div>
