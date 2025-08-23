<?php

namespace App\Services;

use App\Models\SiteContent;

class ContentService
{
    public static function getAllContent()
    {
        $contents = SiteContent::all();
        
        $result = [];
        
        foreach ($contents as $content) {
            if ($content->type === 'json') {
                $result[$content->key] = json_decode($content->value, true);
            } else {
                $result[$content->key] = $content->value;
            }
        }
        
        return $result;
    }
    
    public static function getContentBySection($section)
    {
        return SiteContent::where('section', $section)->get();
    }
}