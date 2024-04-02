<div class="d-flex flex-column flex-md-row justify-content-center" style="gap: 0.5rem">
    @can('achievements-create')
        @if ($model->achievements->count() == 0)
            <form action="{{ route('admin.achievements.generate', $model->id) }}" method="post">
                @csrf
                <button type="submit" class="btn btn-sm btn-secondary">Generate Achievements</button>
            </form>
        @endif
    @endcan
    @can('kpi-update')
        <a href="{{ route('admin.kpi.edit', $model->id) }}" class="btn btn-sm btn-secondary">Edit</a>
    @endcan
    @can('kpi-delete')
        <span class="btn btn-sm btn-danger" id="deleteButton" data-model-id="{{ $model->id }}">Delete</span>
    @endcan
</div>
