<?php
/**
 * Make Slug Function 
 * to create URL SEO Friendly
 * 
 * @param string $slug
 * @return string|mixed
 */
function make_slug($slug)
{
    
    // replace non letter or digits by -
    $slug = preg_replace( '~[^\\pL\d]+~u', '-', $slug);
    
    // trim
    $slug = trim($slug, '-');
    
    // transliterate
    $slug = iconv('utf-8', 'us-ascii//TRANSLIT', $slug);
    
    // lowercase
    $slug = strtolower($slug);
    
    // remove unwanted characters
    $slug = preg_replace('~[^-\w]+~', '', $slug);
    
    $slug = preg_replace('/[^-a-z0-9_]+/', $slug);

    if (empty($slug)) return 'n-a';
    
    return $slug;

}