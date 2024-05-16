@php
    function rolesProcessor($roles)
    {
        $string = '';
        foreach ($roles as $role) {
            $string = $role->name;
        }
        return $string;
    }
@endphp
<x-app-layout>
    <div class="flex flex-col gap-4">
        <h1 class="text-2xl font-bold">Profil</h1>
        <div class="flex flex-col items-center gap-2">
            <img src="{{ $user->image ? asset('storage/' . $user->image) : asset('images/profile.png') }}" alt="profil"
                class="w-20">
            <div class="flex w-full flex-col items-center">
                <p class="font-semibold capitalize">{{ $user->name }}</p>
                <small class="line-clamp-1 uppercase">{{ $user->identifier }}: {{ $user->identifier_number }}</small>
                <div class="mt-1.5 flex">
                    <small
                        class="rounded-full bg-yellow-400 px-2 pb-0.5 capitalize">{{ rolesProcessor($user->roles) }}</small>
                </div>
            </div>
        </div>
        <canvas id="myChart"></canvas>
        <h1 class="text-2xl font-bold">Pencapaian</h1>

        @if (count($achievements) == 0)
            <p>Belum ada pencapaian</p>
        @else
            <ol class="list-inside list-decimal">
                @foreach ($achievements as $achievement)
                    <li>{{ $achievement->title ?? '-' }}</li>
                @endforeach
            </ol>
        @endif
    </div>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const ctx = document.getElementById('myChart');
        const labels = [];
        const presencePoints = [];
        const feedbackPoints = [];
        const rawData = {{ Js::from($points) }}

        rawData.forEach(kpi => {
            const endDate = kpi.end_date;
            const date = new Date(endDate).getDate()
            const month = new Date(endDate).getMonth() + 1
            const year = new Date(endDate).getFullYear()
            labels.push(`${date}/${month}/${year}`)
            presencePoints.push(kpi.points[0]?.presence_points ?? undefined)
            feedbackPoints.push(kpi.points[0]?.feedback_points ?? undefined)
        });

        while (labels.length < 5) {
            labels.push('-')
        }

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Poin Kehadiran',
                    data: presencePoints,
                    borderWidth: 1,
                    pointRadius: 5,
                }, {
                    label: 'Poin Feedback',
                    data: feedbackPoints,
                    borderWidth: 1,
                    pointRadius: 5,
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Performa dalam 5 periode KPI'
                    }
                }
            }
        });
    </script>
</x-app-layout>
