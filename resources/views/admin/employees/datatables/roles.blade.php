{{-- @foreach ($model->roles as $role)
    <span class="badge badge-secondary">{{ $role->name }}</span>
    <span>{{ $loop->last ? '' : '|' }}</span>
@endforeach --}}
{{ Str::upper($model->roles[0]->name) }}
