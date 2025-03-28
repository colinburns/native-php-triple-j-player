<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Triple J Player</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
    <style>
        body {
            background-color: #1a1a1a;
            color: white;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }

        .now-playing {
            background-color: #5d4037;
            border-radius: 8px;
            overflow: hidden;
        }

        .player-controls {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 15px 0;
            background-color: #2d2d2d;
            border-top: 1px solid #3d3d3d;
        }

        .cover-art {
            max-width: 100%;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="p-0 m-0">
<div class="container mx-auto p-4 mb-24">
    <!-- Now Playing Section -->
    <div class="now-playing p-4 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="text-xs text-white/70">Now Playing - triple j</div>
            </div>
            <div>
                <button class="text-white/70">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </button>
            </div>
        </div>

        <!-- Playback Indicators -->
        <div class="flex justify-center gap-1 my-2">
            <div class="w-2 h-2 bg-white rounded-full"></div>
            <div class="w-2 h-2 bg-white/30 rounded-full"></div>
            <div class="w-2 h-2 bg-white/30 rounded-full"></div>
            <div class="w-2 h-2 bg-white/30 rounded-full"></div>
        </div>
        <!-- Album Art and Details -->
        <div class="flex justify-center my-4">
            <img id="current-cover-art" src="{{ $currentTrack['artwork'] ?? asset('images/icon.png') }}" alt="Album Cover" class="cover-art w-64 h-64 object-cover">
        </div>

        <h1 id="current-title" class="text-3xl font-bold mt-6 text-center">{{ $currentTrack['title'] ?? 'Leaving for London' }}</h1>
        <h2 id="current-artist" class="text-xl mt-2 mb-4 text-center">{{ $currentTrack['artist'] ?? 'Pacific Avenue' }}</h2>
        <p id="current-album" class="text-sm text-white/70 text-center">{{ $currentTrack['album'] ?? 'Leaving for London' }}</p>
    </div>

    <!-- Recently Played Section -->
    <h3 class="text-2xl font-bold mb-4">Recently Played</h3>
    <div id="recent-tracks">
        @if(isset($recentTracks) && count($recentTracks) > 0)
            @foreach($recentTracks as $track)
                <div class="flex items-center border-b border-white/10 py-4">
                    <div class="w-16 h-16 mr-4">
                        <img src="{{ $track['artwork'] }}" alt="{{ $track['title'] }}" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1">
                        <div class="text-lg font-semibold">{{ $track['title'] }}</div>
                        <div class="text-white/70">{{ $track['artist'] }}</div>
                        <div class="text-xs text-white/50">{{ $track['album'] }} - {{ $track['type'] }}</div>
                    </div>
                    <div class="text-white/50 text-sm">{{ $track['played_at'] }}</div>
                </div>
            @endforeach
        @else
            <div class="text-white/50 text-center py-4">No recent tracks available</div>
        @endif
    </div>
</div>

<!-- Player Controls -->
<div class="player-controls">
    <div class="flex justify-center">
        <button id="play-button" class="bg-red-500 text-white w-16 h-16 rounded-full flex items-center justify-center">
            <svg id="play-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
            <svg id="pause-icon" class="hidden" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="6" y="4" width="4" height="16"></rect><rect x="14" y="4" width="4" height="16"></rect></svg>
        </button>
    </div>
</div>

<!-- Hidden Audio Element for HLS.js -->
<audio id="radio-player" style="display: none;"></audio>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get DOM elements
        const audioElement = document.getElementById('radio-player');
        const playButton = document.getElementById('play-button');
        const playIcon = document.getElementById('play-icon');
        const pauseIcon = document.getElementById('pause-icon');

        // Triple J HLS stream URL
        const streamUrl = 'https://mediaserviceslive.akamaized.net/hls/live/2109456/triplejnsw/v0-221.m3u8';

        // Create HLS.js instance
        let hls = null;
        let isPlaying = false;

        // Initialize HLS.js if supported
        if (Hls.isSupported()) {
            console.log('HLS.js is supported');
            hls = new Hls();
            hls.loadSource(streamUrl);
            hls.attachMedia(audioElement);

            hls.on(Hls.Events.MANIFEST_PARSED, function() {
                console.log('HLS manifest parsed, stream ready to play');
            });

            hls.on(Hls.Events.ERROR, function(event, data) {
                console.error('HLS error:', data.type, data.details);
                if (data.fatal) {
                    switch(data.type) {
                        case Hls.ErrorTypes.NETWORK_ERROR:
                            console.error('Network error, trying to recover');
                            hls.startLoad();
                            break;
                        case Hls.ErrorTypes.MEDIA_ERROR:
                            console.error('Media error, trying to recover');
                            hls.recoverMediaError();
                            break;
                        default:
                            console.error('Fatal error, cannot recover');
                            break;
                    }
                }
            });
        } else if (audioElement.canPlayType('application/vnd.apple.mpegurl')) {
            // Native HLS support (Safari)
            console.log('Using native HLS support');
            audioElement.src = streamUrl;
        } else {
            console.error('HLS is not supported in this browser/environment');
        }

        // Play button click handler
        playButton.addEventListener('click', function() {
            console.log('Play button clicked, current state:', isPlaying);

            if (isPlaying) {
                // Pause playback
                console.log('Pausing playback');
                audioElement.pause();
                playIcon.classList.remove('hidden');
                pauseIcon.classList.add('hidden');
                isPlaying = false;
            } else {
                // Start playback
                console.log('Starting playback');

                const playPromise = audioElement.play();

                if (playPromise !== undefined) {
                    playPromise.then(() => {
                        console.log('Playback started successfully');
                        updateNowPlaying();
                        playIcon.classList.add('hidden');
                        pauseIcon.classList.remove('hidden');
                        isPlaying = true;
                    }).catch(error => {
                        console.error('Error playing audio:', error);
                        // Try again with user interaction
                        console.log('Trying again...');
                        audioElement.play().catch(e => {
                            console.error('Second play attempt failed:', e);
                        });
                    });
                }
            }
        });

        // Update track info every 30 seconds
        setInterval(updateNowPlaying, 30000);

        function updateNowPlaying() {
            fetch('/now-playing')
                .then(response => response.json())
                .then(data => {
                    if (data.current) {
                        document.getElementById('current-title').textContent = data.current.title;
                        document.getElementById('current-artist').textContent = data.current.artist;
                        document.getElementById('current-album').textContent = data.current.album;
                        document.getElementById('current-cover-art').src = data.current.artwork;
                    }

                    if (data.recent && data.recent.length > 0) {
                        const recentContainer = document.getElementById('recent-tracks');
                        recentContainer.innerHTML = '';

                        data.recent.forEach(track => {
                            const trackHtml = `
                                <div class="flex items-center border-b border-white/10 py-4">
                                    <div class="w-16 h-16 mr-4">
                                        <img src="${track.artwork}" alt="${track.title}" class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-1">
                                        <div class="text-lg font-semibold">${track.title}</div>
                                        <div class="text-white/70">${track.artist}</div>
                                        <div class="text-xs text-white/50">${track.album} - ${track.type}</div>
                                    </div>
                                    <div class="text-white/50 text-sm">${track.played_at}</div>
                                </div>`;

                            recentContainer.innerHTML += trackHtml;
                        });
                    }
                })
                .catch(error => console.error('Error fetching now playing info:', error));
        }
    });
</script>
</body>
</html>
