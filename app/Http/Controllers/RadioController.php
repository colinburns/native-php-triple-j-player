<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class RadioController extends Controller
{
    /**
     * Show the radio player view
     */
    public function index()
    {
        $data = $this->fetchPlaylistData();

        \Log::info('Now Playing Data:', $data);

        return view('radio', [
            'currentTrack' => $data['current'] ?? null,
            'recentTracks' => $data['recent'] ?? [],
        ]);
    }

    /**
     * API endpoint to get current playing track
     */
    public function nowPlaying()
    {
        return response()->json($this->fetchPlaylistData());
    }

    /**
     * Fetch playlist data from Triple J
     */
    private function fetchPlaylistData()
    {
        // Cache for 20 seconds to avoid hammering the API
        return Cache::remember('triple_j_playlist', 20, function () {
            try {
                // Use the official Triple J API endpoint
                $response = Http::get('https://music.abcradio.net.au/api/v1/plays/triplej/now.json?tz=Australia%2FSydney');

                if ($response->successful()) {
                    $data = $response->json();

                    \Log::info('Now Playing Data:', $data);

                    // Process the current track
                    $currentTrack = null;
                    if (!empty($data['now'])) {
                        $currentTrack = $this->processTrackData($data['now']);
                    }

                    // Process recent tracks
                    $recentTracks = [];
                    if (!empty($data['prev'])) {
                        foreach ($data['prev'] as $track) {
                            $recentTracks[] = $this->processTrackData($track);
                        }
                    }

                    return [
                        'current' => $currentTrack,
                        'recent' => $recentTracks,
                    ];
                }
            } catch (\Exception $e) {
                \Log::error('Error fetching Triple J data: ' . $e->getMessage());
            }

            // Return fallback data if API fails
            return [
                'current' => [],
                'recent' => [],
            ];
        });
    }

    /**
     * Process track data from the API
     */
    private function processTrackData($track)
    {
        $recording = $track['recording'] ?? [];
        $release = $track['release'] ?? [];

        // Get the artwork URL
        $artworkUrl = asset('images/missing-music.jpg'); // Default fallback

        // Try to get artwork from releases
        if (!empty($release['artwork']) && is_array($release['artwork'])) {
            foreach ($release['artwork'] as $artwork) {
                if (isset($artwork['sizes']) && is_array($artwork['sizes'])) {
                    // Look for a good size - prefer 580x580
                    foreach ($artwork['sizes'] as $size) {
                        if (isset($size['aspect_ratio']) && $size['aspect_ratio'] === '1x1' && isset($size['width']) && $size['width'] === 580) {
                            $artworkUrl = $size['url'];
                            break 2;
                        }
                    }
                }
            }
        }

        // Get artist name
        $artistName = 'Unknown Artist';
        if (!empty($recording['artists']) && is_array($recording['artists'])) {
            foreach ($recording['artists'] as $artist) {
                if (isset($artist['type']) && $artist['type'] === 'primary') {
                    $artistName = $artist['name'] ?? 'Unknown Artist';
                    break;
                }
            }
        }

        // Get album name
        $albumName = '';
        if (!empty($release['title'])) {
            $albumName = $release['title'];
        }

        // Format played time
        $playedAt = 'Just now';
        if (!empty($track['played_time'])) {
            $playedAt = $this->formatTimestamp($track['played_time']);
        }

        return [
            'title' => $recording['title'] ?? 'Unknown Track',
            'artist' => $artistName,
            'album' => $albumName,
            'artwork' => $artworkUrl,
            'played_at' => $playedAt,
            'type' => $release['format'] ?? 'Track',
        ];
    }

    /**
     * Format timestamp into a readable format
     */
    private function formatTimestamp($timestamp)
    {
        if (!$timestamp) {
            return 'Unknown';
        }

        $time = strtotime($timestamp);
        $diff = time() - $time;

        if ($diff < 60) {
            return 'Just now';
        } elseif ($diff < 3600) {
            $minutes = floor($diff / 60);
            return $minutes . 'm ago';
        } elseif ($diff < 86400) {
            $hours = floor($diff / 3600);
            return $hours . 'h ago';
        } else {
            return date('H:i', $time);
        }
    }
}
