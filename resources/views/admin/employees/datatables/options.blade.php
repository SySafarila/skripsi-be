<div class="d-flex flex-column flex-md-row justify-content-center" style="gap: 0.5rem">
    @can('employees-update')
        <a href="{{ route('admin.employees.edit', $model->id) }}" class="btn btn-sm btn-secondary">Edit</a>
    @endcan
    {{-- @can('employees-read')
        <a href="{{ route('admin.employees.show', $model->id) }}" class="btn btn-sm btn-secondary">Show</a>
    @endcan --}}
    @can('employees-delete')
        <span class="btn btn-sm btn-danger" id="deleteButton" data-model-id="{{ $model->id }}">Delete</span>
    @endcan
</div>
