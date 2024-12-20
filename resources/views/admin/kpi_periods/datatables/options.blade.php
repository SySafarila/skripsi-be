<div class="d-flex flex-column flex-md-row justify-content-center" style="gap: 0.5rem">
    @can('achievements-create')
        <form action="{{ route('admin.achievements.generate', $model->id) }}" method="post">
            @csrf
            <button type="submit" class="btn btn-sm btn-secondary"
                onclick="this.parentElement.submit(); this.disabled = true;">Bagikan Pencapaian</button>
        </form>
    @endcan
    @can('kpi-read')
        <a href="{{ route('admin.kpi.leaderboard', $model->id) }}" class="btn btn-sm btn-secondary">Leaderboard</a>
    @endcan
    @can('kpi-read')
        <a href="{{ route('admin.kpi.report', $model->id) }}" class="btn btn-sm btn-secondary">Report</a>
    @endcan
    @can('kpi-update')
        <a href="{{ route('admin.kpi.edit', $model->id) }}" class="btn btn-sm btn-secondary">Edit</a>
    @endcan
    @can('kpi-delete')
        <span class="btn btn-sm btn-danger" id="deleteButton" data-model-id="{{ $model->id }}">Delete</span>
    @endcan
</div>
