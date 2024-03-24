<div class="d-flex flex-column flex-md-row justify-content-center" style="gap: 0.5rem">
    @can('feedback-questions-update')
        <a href="{{ route('admin.questions.edit', $model->id) }}" class="btn btn-sm btn-secondary">Edit</a>
    @endcan
    @can('feedback-questions-delete')
        <span class="btn btn-sm btn-danger" id="deleteButton" data-model-id="{{ $model->id }}">Delete</span>
    @endcan
</div>
