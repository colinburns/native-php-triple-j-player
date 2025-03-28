# Triple J Player for macOS

A native macOS desktop application for streaming the Australian ABC Triple J radio station. Built with NativePHP.

## Features

- Stream Triple J radio directly from your desktop
- Display currently playing track information with album artwork
- View recently played tracks with timestamps
- Native macOS app with system tray integration
- Keyboard shortcuts for playback control

## Development Setup

### Prerequisites

- PHP 8.1 or higher
- Composer
- Node.js and npm

### Installation

1. Clone the starter kit repository:
   ```bash
   git clone https://github.com/NativeCLI/starter-desktop-flux.git triple-j-player
   ```

2. Navigate to the project directory:
   ```bash
   cd triple-j-player
   ```

3. Install PHP dependencies:
   ```bash
   composer install
   ```

4. Install Node.js dependencies:
   ```bash
   npm install
   ```

5. Copy the environment file and generate application key:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

6. Add the project files:
    - Create the RadioController in app/Http/Controllers/
    - Create the radio.blade.php view in resources/views/
    - Update the web and API routes
    - Update the AppServiceProvider and NativePHP config

7. Build the assets:
   ```bash
   npm run build
   ```

8. Start the NativePHP development server:
   ```bash
   php artisan native:serve
   ```

## Keyboard Shortcuts

- `Space`: Play/pause the stream
- `Cmd+R` or `Ctrl+R`: Reload the application
- `Cmd+Q` or `Ctrl+Q`: Quit the application
- `Cmd+U` or `Ctrl+U`: Refresh the now playing information

## Building for Production

To build the app for macOS:

```bash
php artisan native:build
```

This will create a distributable app in the `dist` directory.

## How It Works

The Triple J Player uses:

1. **NativePHP**: To create a native macOS application wrapper around a Laravel PHP application
2. **ABC Triple J API**: To fetch the currently playing tracks and playlist history
3. **HTML5 Audio**: To stream the radio feed directly within the application

The app maintains a clean, focused interface that shows what's currently playing along with recently played tracks - similar to most modern music streaming services.

## System Requirements

- macOS 10.15 (Catalina) or later
- 50MB of free disk space

## License

This project is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Credits

- Radio stream provided by ABC Triple J
- Built with [NativePHP](https://nativephp.com)
- Based on the [NativeCLI/starter-desktop-flux](https://github.com/NativeCLI/starter-desktop-flux) starter kit
