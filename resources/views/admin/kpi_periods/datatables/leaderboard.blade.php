<div class="d-flex flex-column flex-md-row justify-content-center" style="gap: 0.5rem">
    @can('kpi-read')
        @if ($model->user)
            <button type="button" class="btn btn-sm btn-primary" onclick="openDetail({{ $model->user_id }}, null)">
                Detail
            </button>
        @else
            <button type="button" class="btn btn-sm btn-primary" onclick="openDetail(null, {{ $model->tendik_position_id }})">
                Detail
            </button>
        @endif
    @endcan
</div>
