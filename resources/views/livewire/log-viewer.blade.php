<div>
    <div class="container py-4">
        <h2 class="mb-4">📋 Back Office - Log & Video</h2>

        <h4>📹 Video Terdeteksi</h4>
        
        <div class="bg-light p-3 rounded" style="max-height: 500px; overflow-y: auto;">
            <ul class="list-group">
                @forelse ($videos as $video)
                    @php
                        $logDetail = collect($logs)->first(fn($log) => str_contains($log, $video['name']));
                    @endphp
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $video['name'] }}</strong><br>
                            <small class="text-muted">
                                {{ $logDetail ?? 'Log tidak ditemukan' }}
                            </small>
                        </div>
                        <button 
                            class="btn btn-sm btn-primary" 
                            data-bs-toggle="modal" 
                            data-bs-target="#videoModal" 
                            data-video="{{ asset('storage/' . $video['path']) }}" 
                            data-log="{{ $logDetail }}">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="m380-300 280-180-280-180v360ZM480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z"/></svg></button>
                    </li>
                @empty
                    <li class="list-group-item text-muted">Tidak ada video</li>
                @endforelse
            </ul>
        </div>

        <h4 class="d-none">📜 Semua Log Upload</h4>
        <div class="bg-light p-3 rounded d-none" style="max-height: 300px; overflow-y: auto;">
            @forelse ($logs as $log)
                <div>{{ $log }}</div>
            @empty
                <div class="text-muted">Belum ada log</div>
            @endforelse
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Tampilan Video</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body text-center">
            <video id="modalVideo" width="100%" controls>
            <source src="" type="video/webm">
            Browser Anda tidak mendukung tag video.
            </video>
            <div id="modalLogDetail" class="mt-3 text-muted text-start"></div>
        </div>
        </div>
    </div>
    </div>

</div>

<script>
    const videoModal = document.getElementById('videoModal');
    const modalVideo = document.getElementById('modalVideo');
    const modalLogDetail = document.getElementById('modalLogDetail');

    videoModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const videoSrc = button.getAttribute('data-video');
        const logDetail = button.getAttribute('data-log');

        modalVideo.querySelector('source').setAttribute('src', videoSrc);
        modalVideo.load();
        modalLogDetail.textContent = logDetail || 'Log tidak tersedia';
    });

    videoModal.addEventListener('hidden.bs.modal', function () {
        modalVideo.pause();
        modalVideo.currentTime = 0;
    });
</script>
