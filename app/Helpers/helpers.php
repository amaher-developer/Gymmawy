<?php

if (!function_exists('is_image')) {
    /**
     * Check if a file is an image based on its MIME type
     *
     * @param string $path
     * @return bool
     */
    function is_image($path)
    {
        if (!file_exists($path)) {
            return false;
        }
        
        $imageInfo = @getimagesize($path);
        
        return $imageInfo !== false;
    }
}

if (!function_exists('Asset')) {
    /**
     * Generate asset URL (alias for asset() with uppercase first letter for backward compatibility)
     *
     * @param string $path
     * @return string
     */
    function Asset($path)
    {
        return asset($path);
    }
}

if (!function_exists('sweet_alert')) {
    /**
     * Sweet Alert helper - wrapper for alert() function from realrashid/sweet-alert package
     *
     * @return \RealRashid\SweetAlert\Toaster
     */
    function sweet_alert()
    {
        return alert();
    }
}

