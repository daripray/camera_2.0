<div>
    <h3>Back Office - Rekaman & Log</h3>

    <h5>Daftar Video:</h5>
    <ul>
        @foreach($videos as $video)
            <li>
                <video width="300" controls>
                    <source src="{{ asset('storage/videos/' . $video) }}" type="video/webm">
                </video>
            </li>
        @endforeach
    </ul>

    <h5>Log Aktivitas:</h5>
    <pre style="background: #eee; padding: 10px;">{{ $logContent }}</pre>
</div>
