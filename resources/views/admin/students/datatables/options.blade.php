<div class="d-flex flex-column flex-md-row justify-content-center" style="gap: 0.5rem">
    @can('students-update')
        <a href="{{ route('admin.students.edit', $model->id) }}" class="btn btn-sm btn-secondary">Edit</a>
    @endcan
    {{-- @can('students-read')
        <a href="{{ route('admin.students.show', $model->id) }}" class="btn btn-sm btn-secondary">Show</a>
    @endcan --}}
    @can('students-delete')
        <span class="btn btn-sm btn-danger" id="deleteButton" data-model-id="{{ $model->id }}">Delete</span>
    @endcan
</div>
