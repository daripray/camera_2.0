@push('styles')
<style>
    video {
      width: 50%;
      max-width: 300px;
      margin: 10px auto;
      display: block;
      border: 3px solid #0d6efd;
      border-radius: 10px;
    }
    #status {
      padding-top: 10px;
    }
  </style>
@endpush

<div class="container py-4">
    <h2 class="text-center mb-4">🎥 Deteksi Manusia v2 (canvas.captureStream)</h2>

    <div class="mb-3">
        <label for="cameraSelect" class="form-label">Pilih Kamera:</label>
        <select id="cameraSelect" class="form-select"></select>
    </div>

    <div class="d-flex justify-content-center mb-3">
        <button id="toggleBtn" class="btn btn-success">Start</button>
    </div>

    <div class="text-center">
        <video id="video" autoplay muted playsinline class="d-none"></video>
        <canvas id="canvas" style="display:none;"></canvas>
        <p id="status" class="text-center text-muted"></p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@4.0.0"></script>
<script src="https://cdn.jsdelivr.net/npm/@tensorflow-models/coco-ssd"></script>
<script>
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const statusEl = document.getElementById('status');
    const cameraSelect = document.getElementById('cameraSelect');
    const toggleBtn = document.getElementById('toggleBtn');

    let currentStream = null;
    let model = null;
    let recorder = null;
    let recordedChunks = [];
    let isRecording = false;
    let detectionInterval = null;
    let isDetecting = false;

    async function getCameraDevices() {
        const devices = await navigator.mediaDevices.enumerateDevices();
        const videoDevices = devices.filter(device => device.kind === 'videoinput');
        cameraSelect.innerHTML = '';
        videoDevices.forEach((device, index) => {
            const option = document.createElement('option');
            option.value = device.deviceId;
            option.text = device.label || `Kamera ${index + 1}`;
            cameraSelect.appendChild(option);
        });
    }

    async function setupCamera(deviceId) {
        if (currentStream) currentStream.getTracks().forEach(track => track.stop());
        const constraints = {
            audio: true,
            video: {
                deviceId: deviceId ? { exact: deviceId } : undefined,
                width: { ideal: 1280 },
                height: { ideal: 720 },
                frameRate: { ideal: 15, max: 30 },
            }
        };
        currentStream = await navigator.mediaDevices.getUserMedia(constraints);
        video.srcObject = currentStream;
        return new Promise(resolve => {
            video.onloadedmetadata = () => resolve(video);
        });
    }
    

    function drawToCanvas() {
        const ctx = canvas.getContext('2d');
        function draw() {
            if (video.readyState === 4) {
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            }
            if (isRecording) {
                drawLoopId = requestAnimationFrame(draw);
            }
        }
        draw();
    }

    function startRecording() {
        console.log("startRecording()");        
        recordedChunks = [];
        const canvasStream = canvas.captureStream(15);
        const audioTracks = currentStream.getAudioTracks(); // ambil audio dari kamera/mikrofon

        if (!audioTracks || audioTracks.length === 0) {
            console.warn("Tidak ada track audio yang tersedia!");
        }
        console.log("Audio Tracks:", audioTracks);

        // Gabungkan video dari canvas + audio dari kamera
        const combinedStream = new MediaStream([
            ...canvasStream.getVideoTracks(),
            ...audioTracks
        ]);

        recorder = new MediaRecorder(combinedStream, {
            mimeType: "video/webm",
            videoBitsPerSecond: 1500000
        });

        recorder.ondataavailable = e => e.data.size > 0 && recordedChunks.push(e.data);
        recorder.onstop = () => {
            const blob = new Blob(recordedChunks, { type: "video/webm" });
            const formData = new FormData();
            formData.append("video", blob, "deteksi-manusia.webm");
            formData.append("time", new Date().toISOString());
            fetch("{{ url('/api/upload') }}", {
                method: "POST",
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: formData
            })
            .then(res => res.text())
            .then(msg => console.log("Upload selesai:", msg))
            .then(msg => statusEl.innerHTML = "<br><i>Video telah direkam dan diupload</i>");
            // statusEl.innerHTML += "<br><i>Video telah direkam dan diupload</i>";
        };
        recorder.start();
        isRecording = true;
        drawToCanvas(); // Start drawing while recording
    }

    function stopRecording() {
        if (recorder && isRecording) {
            console.log("stopRecording()");
            recorder.stop();
            isRecording = false;
        }
    }

    async function detectHuman() {
        model = await cocoSsd.load();
        statusEl.innerHTML = "Model diload...";

        detectionInterval = setInterval(async () => {
            if (!model || video.readyState !== 4) return;
            const predictions = await model.detect(video);
            const humanDetected = predictions.some(p => p.class === "person" && p.score > 0.6);
            if (humanDetected) {
                if (!isRecording) {
                    statusEl.innerHTML = '<b class="text-danger">Manusia Terdeteksi!</b>';
                    startRecording();
                } else {
                    statusEl.innerHTML = '<b class="text-success">Sedang Merekam</b>';
                }
            } else {
                if (!isRecording) {
                    statusEl.innerHTML = '<b class="text-success">Tidak Ada Manusia</b>';
                } else {
                    statusEl.innerHTML = '<b class="text-primary">Menyimpan Rekaman</b>';
                    stopRecording();
                }
            }
        }, 1500);
    }

    function stopDetection() {
        if (detectionInterval) clearInterval(detectionInterval);
        detectionInterval = null;
        if (currentStream) {
            currentStream.getTracks().forEach(track => track.stop());
            currentStream = null;
        }
        video.srcObject = null;
        statusEl.innerHTML = "<b>Deteksi dihentikan</b>";
    }

    cameraSelect.addEventListener('change', async () => {
        await setupCamera(cameraSelect.value);
    });

    toggleBtn.addEventListener('click', async () => {
        if (!isDetecting) {
            toggleBtn.textContent = "Stop";
            toggleBtn.classList.remove("btn-success");
            toggleBtn.classList.add("btn-danger");
            statusEl.classList.remove('d-none');
            statusEl.innerHTML = "Mengakses kamera...";
            await setupCamera(cameraSelect.value);
            video.classList.remove('d-none');
            await detectHuman();
            isDetecting = true;
        } else {
            toggleBtn.textContent = "Start";
            toggleBtn.classList.remove("btn-danger");
            toggleBtn.classList.add("btn-success");
            stopRecording();
            stopDetection();
            isDetecting = false;
            video.classList.add('d-none');
            statusEl.classList.add('d-none');
        }
    });

    (async () => {
        await getCameraDevices();
    })();
</script>