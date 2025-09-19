<?php

namespace App\Services;

use App\Models\Video;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Symfony\Component\Process\Process;
use RuntimeException;

class VideoService
{
    public function processAndStore(
        string $absoluteLocalPath,
        string $title,
        ?string $description,
        array $tagsArray,
        int $userId
    ): Video {
        // Extract metadata
        $meta = $this->getVideoMetadata($absoluteLocalPath);

        // Build filenames
        $filename = now()->format('YmdHis') . '_' . basename($absoluteLocalPath);
        $s3Path = 'videos/' . $filename;

        // Create thumbnail
        $thumbnailFilename = pathinfo($filename, PATHINFO_FILENAME) . '.jpg';
        $localThumbnailPath = $this->createThumbnail($absoluteLocalPath, $meta, $thumbnailFilename);

        // Upload thumbnail to S3
        $thumbnailS3Path = "thumbnails/{$thumbnailFilename}";
        Storage::disk('s3')->put(
            $thumbnailS3Path,
            file_get_contents($localThumbnailPath),
            ['ContentType' => 'image/jpeg']
        );

        // Cleanup local thumbnail
        unlink($localThumbnailPath);

        // Upload video to S3
        Storage::disk('s3')->putFileAs(
            'videos',
            new File($absoluteLocalPath),
            $filename,
            ['ContentType' => mime_content_type($absoluteLocalPath)]
        );

        // Save DB record
        return Video::create([
            'user_id' => $userId,
            'title' => $title,
            'description' => $description,
            'path' => $s3Path,
            'metadata' => $meta,
            'tags' => $tagsArray,
            'thumbnail_path' => $thumbnailS3Path,
        ]);
    }

    protected function createThumbnail(string $absoluteLocalPath, array $meta, string $thumbnailFilename): string
    {
        $thumbDir = storage_path('app/tmp');
        if (! is_dir($thumbDir)) {
            mkdir($thumbDir, 0755, true);
        }
        $localThumbnailPath = $thumbDir . '/' . $thumbnailFilename;

        $duration = $meta['duration'] ?? 0;
        $seekTime = $duration > 0 ? $duration / 2 : 1;

        $process = new Process([
            'ffmpeg',
            '-ss', (string) $seekTime,
            '-i', $absoluteLocalPath,
            '-frames:v', '1',
            '-q:v', '2',
            '-update', '1',
            '-y',
            $localThumbnailPath
        ]);

        $process->run();

        if (! $process->isSuccessful() || ! file_exists($localThumbnailPath)) {
            throw new RuntimeException('FFmpeg failed: ' . $process->getErrorOutput());
        }

        return $localThumbnailPath;
    }

    protected function getVideoMetadata(string $filePath): array
    {
        $cmd = [
            'ffprobe',
            '-v', 'quiet',
            '-print_format', 'json',
            '-show_format',
            '-show_streams',
            $filePath
        ];

        $process = new Process($cmd);
        $process->setTimeout(120);
        $process->run();

        if (!$process->isSuccessful()) {
            return ['error' => 'ffprobe failed'];
        }

        $json = json_decode($process->getOutput(), true);
        if (!$json) {
            return ['error' => 'invalid ffprobe output'];
        }

        $format = $json['format'] ?? [];
        $streams = $json['streams'] ?? [];

        $videoStream = collect($streams)->firstWhere('codec_type', 'video');
        $audioStream = collect($streams)->firstWhere('codec_type', 'audio');

        return [
            'format' => $format,
            'duration' => isset($format['duration']) ? (float) $format['duration'] : null,
            'size' => isset($format['size']) ? (int) $format['size'] : null,
            'bit_rate' => isset($format['bit_rate']) ? (int) $format['bit_rate'] : null,
            'video' => $videoStream,
            'audio' => $audioStream,
            'raw' => $json,
        ];
    }
}
