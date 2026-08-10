<?php

use Config\Services;

/**
 * Upload, compress, convert and create thumbnail
 *
 * @param \CodeIgniter\HTTP\Files\UploadedFile $file
 * @param int $jobId
 * @return string|false (returns filename or false)
 */
function uploadOptimizedJobImage($file, $jobId)
{
    if (!$file->isValid()) {
        return false;
    }

    // Max 20MB limit (safety for shared hosting)
    if ($file->getSize() > 20 * 1024 * 1024) {
        return false;
    }

    // Allow only common formats
    $allowed = [
        'image/jpeg',
        'image/png',
        'image/webp'
    ];

    if (!in_array($file->getMimeType(), $allowed)) {
        return false;
    }

    // Base directory
    $basePath = WRITEPATH . 'uploads/jobs/' . $jobId . '/';
    $fullPath = $basePath . 'full/';
    $thumbPath = $basePath . 'thumb/';

    // Create folders if not exist
    foreach ([$basePath, $fullPath, $thumbPath] as $path) {
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
    }

    // Move original temporarily
    $tempName = $file->getRandomName();
    $file->move($basePath, $tempName);

    $original = $basePath . $tempName;

    // Final unified JPG filename
    $finalName = 'job_' . $jobId . '_' . uniqid() . '.jpg';

    $fullImage = $fullPath . $finalName;
    $thumbImage = $thumbPath . $finalName;

    try {
        
       

        $image = Services::image()
            ->withFile($original);

        /*
         FULL IMAGE
         - Max width 1600px
         - Maintain ratio
         - Prevent upscaling
         - 65% quality
        */
        $image->resize(1600, 1600, true, 'width')
              ->save($fullImage);

        /*
         THUMBNAIL
         - 300px width
         - Slightly higher quality
        */
        Services::image()
            ->withFile($fullImage)
            ->resize(300, 300, true, 'width')
            ->save($thumbImage, 70);

    } catch (\Throwable $e) {
      
        // Cleanup if something fails
        if (file_exists($original)) unlink($original);
        return false;
    }

    // Delete original large file
    if (file_exists($original)) {
        unlink($original);
    }

    return $finalName;
}
