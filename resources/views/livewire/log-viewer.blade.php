<div>
    <div class="container py-4">
        <h2 class="mb-4">📋 Back Office - Log & Video</h2>

        <ul class="nav nav-tabs mb-3" id="boTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="videos-tab" data-bs-toggle="tab" data-bs-target="#videos" type="button" role="tab">
                    📹 Video Terdeteksi
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="logs-tab" data-bs-toggle="tab" data-bs-target="#logs" type="button" role="tab">
                    📜 Semua Log Upload
                </button>
            </li>
        </ul>

        <div class="tab-content" id="boTabsContent">
            {{-- Tab Video --}}
            <div class="tab-pane fade show active" id="videos" role="tabpanel">
                <div class="bg-light p-3 rounded" style="max-height: 400px; overflow-y: auto;">
                    <ul class="list-group">
                        @forelse ($videos as $video)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $video['name'] }}</strong><br>

                                    @if (!empty($video['log_detail']))
                                        <small class="text-muted">
                                            Date: {{ $video['log_detail']['datetime'] }} <br>
                                            Size: {{ number_format($video['log_detail']['filesize'] / 1024, 2) }} KB
                                        </small>
                                    @else
                                        <small class="text-muted">Log tidak ditemukan</small>
                                    @endif
                                </div>

                                <button 
                                    class="btn btn-sm btn-primary" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#videoModal" 
                                    data-video="{{ asset('storage/' . $video['path']) }}" 
                                    data-log="{{ $video['name'] }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3">
                                        <path d="m380-300 280-180-280-180v360ZM480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z"/>
                                    </svg>
                                </button>
                            </li>
                        @empty
                            <li class="list-group-item text-muted">Tidak ada video</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            {{-- Tab Log --}}
            <div class="tab-pane fade" id="logs" role="tabpanel">
                <div class="bg-light p-3 rounded" style="max-height: 300px; overflow-y: auto;">
                    <ul class="list-group">
                        @forelse ($logs as $log)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <small>{{ $log }}</small>
                            </li>
                        @empty
                            <li class="list-group-item text-muted">Tidak ada video</li>
                        @endforelse
                    </ul>
                </div>
            </div>
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
