<x-app-layout>
    <div class="flex flex-col gap-4">
        <h1 class="text-2xl font-bold">Profil</h1>
        <div class="flex flex-col items-center gap-2">
            <img src="{{ Auth::user()->image ? asset('storage/' . Auth::user()->image) : asset('images/profile.png') }}"
                alt="profil" class="w-20">
            <div class="flex w-full flex-col items-center">
                <p class="font-semibold">{{ Auth::user()->name }}</p>
                @role('dosen')
                    <small class="line-clamp-1">NIDN: {{ Auth::user()->identifier_number }}</small>
                @endrole
                @role('mahasiswa')
                    <small class="line-clamp-1">NIM: {{ Auth::user()->identifier_number }}</small>
                @endrole
                @role('staff')
                    <small class="line-clamp-1">NIP: {{ Auth::user()->identifier_number }}</small>
                @endrole
                @role('tendik')
                    <small class="line-clamp-1">NIP: {{ Auth::user()->identifier_number }}</small>
                @endrole
            </div>
        </div>
        <canvas id="myChart"></canvas>
        <h1 class="text-2xl font-bold">Pencapaian</h1>
        <ol class="list-inside list-decimal">
            @foreach ($achievements as $achievement)
                <li>{{ $achievement->title ?? '-' }}</li>
            @endforeach
        </ol>
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
                    borderWidth: 1
                }, {
                    label: 'Poin Feedback',
                    data: feedbackPoints,
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</x-app-layout>
