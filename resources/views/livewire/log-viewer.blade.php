
<div class="container py-4">
    <h2 class="mb-4">📋 Back Office - Log & Video</h2>

    <h4>📹 Video Terdeteksi</h4>
    <ul class="list-group mb-4">
        @forelse ($videos as $video)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                {{ basename($video) }}
                <a class="btn btn-sm btn-primary" href="{{ asset('storage/' . $video) }}" target="_blank">Lihat</a>
            </li>
        @empty
            <li class="list-group-item text-muted">Tidak ada video</li>
        @endforelse
    </ul>

    <h4>📜 Log Upload</h4>
    <div class="bg-light p-3 rounded" style="max-height: 300px; overflow-y: auto;">
        @forelse ($logs as $log)
            <div>{{ $log }}</div>
        @empty
            <div class="text-muted">Belum ada log</div>
        @endforelse
    </div>
</div>
