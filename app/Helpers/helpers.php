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

if (!function_exists('calorie_units')) {
    /**
     * Get calorie measurement units in different languages
     *
     * @param string $lang Language code ('en' or 'ar')
     * @return array Array of unit names indexed by unit ID
     */
    function calorie_units($lang = 'en')
    {
        $units = [
            'en' => [
                0 => 'Gram',
                1 => 'Kilogram',
                2 => 'Ounce',
                3 => 'Pound',
                4 => 'Cup',
                5 => 'Tablespoon',
                6 => 'Teaspoon',
                7 => 'Piece',
                8 => 'Liter',
                9 => 'Milliliter',
                10 => 'Slice',
                11 => 'Serving',
            ],
            'ar' => [
                0 => 'جرام',
                1 => 'كيلوجرام',
                2 => 'أونصة',
                3 => 'رطل',
                4 => 'كوب',
                5 => 'ملعقة طعام',
                6 => 'ملعقة صغيرة',
                7 => 'قطعة',
                8 => 'لتر',
                9 => 'ميليلتر',
                10 => 'شريحة',
                11 => 'حصة',
            ],
        ];

        return $units[$lang] ?? $units['en'];
    }
}

