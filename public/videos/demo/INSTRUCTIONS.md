# Demo Video Setup Instructions

## Folder Structure
```
public/
└── videos/
    └── demo/
        ├── demo-video.mp4      (Main video file)
        ├── demo-video.webm     (WebM format for better browser support)
        ├── demo-video.ogg      (OGG format for older browsers)
        └── poster.jpg          (Video thumbnail/poster image)
```

## How to Add Your Video

1. **Place your video files in this folder:**
   - `demo-video.mp4` (recommended format)
   - `demo-video.webm` (optional, for better browser support)
   - `demo-video.ogg` (optional, for older browsers)

2. **Add a poster image:**
   - `poster.jpg` (thumbnail image that shows before video plays)
   - Recommended size: 800x500 pixels
   - This will be displayed as a preview before the video starts

3. **Video specifications:**
   - Format: MP4 (H.264 codec recommended)
   - Resolution: 1920x1080 or 1280x720
   - Duration: 2-5 minutes recommended
   - File size: Keep under 50MB for better loading

## Current Setup

The welcome page is already configured to:
- Display the video with controls
- Show a play button overlay
- Handle video playback with JavaScript
- Support multiple video formats for browser compatibility

## Testing

After adding your video files:
1. Clear browser cache
2. Visit the welcome page
3. Click the play button to test video playback
4. Verify the video loads and plays correctly

## Troubleshooting

If video doesn't play:
1. Check file paths are correct
2. Ensure video format is supported
3. Check file permissions
4. Verify video file isn't corrupted
