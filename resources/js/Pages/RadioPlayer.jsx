import React, { useState, useEffect, useRef } from 'react';
import { Head } from '@inertiajs/react';
import axios from 'axios';

export default function RadioPlayer() {
    const [currentTrack, setCurrentTrack] = useState({
        title: 'Loading...',
        artist: 'Loading...',
        album: '',
        artwork: 'https://www.abc.net.au/cm/rimage/11948498-1x1-large.png?v=2'
    });
    const [recentTracks, setRecentTracks] = useState([]);
    const [isPlaying, setIsPlaying] = useState(false);
    const audioRef = useRef(null);

    // Initialize the audio player
    useEffect(() => {
        audioRef.current = new Audio('https://live-radio01.mediahubaustralia.com/2TJW/mp3/');
        audioRef.current.preload = 'auto';

        // Add event listeners
        audioRef.current.addEventListener('playing', () => setIsPlaying(true));
        audioRef.current.addEventListener('pause', () => setIsPlaying(false));
        audioRef.current.addEventListener('error', handleAudioError);

        // Clean up
        return () => {
            if (audioRef.current) {
                audioRef.current.pause();
                audioRef.current.removeEventListener('playing', () => setIsPlaying(true));
                audioRef.current.removeEventListener('pause', () => setIsPlaying(false));
                audioRef.current.removeEventListener('error', handleAudioError);
            }
        };
    }, []);

    // Fetch now playing data on component mount and every 30 seconds
    useEffect(() => {
        fetchNowPlaying();
        const interval = setInterval(fetchNowPlaying, 30000);

        return () => clearInterval(interval);
    }, []);

    const fetchNowPlaying = async () => {
        try {
            const response = await axios.get('/api/now-playing');
            if (response.data.current) {
                setCurrentTrack(response.data.current);
            }
            if (response.data.recent) {
                setRecentTracks(response.data.recent);
            }
        } catch (error) {
            console.error('Error fetching now playing data:', error);
        }
    };

    const handleAudioError = (e) => {
        console.error('Audio playback error:', e);
        setIsPlaying(false);
    };

    const togglePlayback = () => {
        if (audioRef.current) {
            if (isPlaying) {
                audioRef.current.pause();
            } else {
                audioRef.current.play().catch(handleAudioError);
            }
        }
    };

    return (
        <>
            <Head title="Triple J Player" />

            <div className="bg-gray-900 text-white min-h-screen">
                <div className="container mx-auto p-4 mb-24">
                    {/* Now Playing Section */}
                    <div className="bg-amber-950 rounded-lg overflow-hidden p-4 mb-6">
                        <div className="flex items-center justify-between">
                            <div className="flex items-center">
                                <div className="text-xs text-white/70">Now Playing - triple j</div>
                            </div>
                            <div>
                                <button className="text-white/70">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                                        <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {/* Playback Indicators */}
                        <div className="flex justify-center gap-1 my-2">
                            <div className="w-2 h-2 bg-white rounded-full"></div>
                            <div className="w-2 h-2 bg-white/30 rounded-full"></div>
                            <div className="w-2 h-2 bg-white/30 rounded-full"></div>
                            <div className="w-2 h-2 bg-white/30 rounded-full"></div>
                        </div>

                        {/* Album Art and Details */}
                        <div className="flex justify-center my-4">
                            <img
                                src={currentTrack.artwork}
                                alt="Album Cover"
                                className="w-64 h-64 object-cover border border-white/10"
                            />
                        </div>

                        <h1 className="text-3xl font-bold mt-6 text-center">{currentTrack.title}</h1>
                        <h2 className="text-xl mt-2 mb-4 text-center">{currentTrack.artist}</h2>
                        <p className="text-sm text-white/70 text-center">{currentTrack.album}</p>
                    </div>

                    {/* Recently Played Section */}
                    <h3 className="text-2xl font-bold mb-4">Recently Played</h3>
                    <div id="recent-tracks">
                        {recentTracks.map((track, index) => (
                            <div key={index} className="flex items-center border-b border-white/10 py-4">
                                <div className="w-16 h-16 mr-4">
                                    <img src={track.artwork} alt={track.title} className="w-full h-full object-cover" />
                                </div>
                                <div className="flex-1">
                                    <div className="text-lg font-semibold">{track.title}</div>
                                    <div className="text-white/70">{track.artist}</div>
                                    <div className="text-xs text-white/50">{track.album} - {track.type}</div>
                                </div>
                                <div className="text-white/50 text-sm">{track.played_at}</div>
                            </div>
                        ))}
                    </div>
                </div>

                {/* Player Controls */}
                <div className="fixed bottom-0 left-0 right-0 bg-gray-800 border-t border-gray-700 p-4">
                    <div className="flex justify-center">
                        <button
                            onClick={togglePlayback}
                            className="bg-red-500 text-white w-16 h-16 rounded-full flex items-center justify-center"
                        >
                            {isPlaying ? (
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                                    <rect x="6" y="4" width="4" height="16"></rect>
                                    <rect x="14" y="4" width="4" height="16"></rect>
                                </svg>
                            ) : (
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                                    <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                </svg>
                            )}
                        </button>
                    </div>
                </div>
            </div>
        </>
    );
}
