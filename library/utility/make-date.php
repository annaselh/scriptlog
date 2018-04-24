<?php
// make Date
function make_date($value, $locale = null)
{
    $day = substr($value, 8, 2 );
    $month = generate_month(substr( $value, 5, 2 ), $locale);
    $year = substr($value, 0, 4 );
    
    if ($locale == 'id') {
        
        return $day . ' ' . $month . ' ' . $year;
        
    } else {
        
        return $month . ' ' . $day . ', ' . $year;
        
    }
    
}

function generate_month($value, $locale = null)
{
    
    switch ($value) {
        
        case 1 :
            
            return (!empty($locale) && $locale == 'id') ? "Januari" : "January";
            
            //return "January";
            break;
            
        case 2 :
            
            return (!empty($locale) && $locale == 'id') ? "Pebruari" : "February";
            //return "February";
            break;
            
        case 3 :
            
            return (!empty($locale) && $locale == 'id') ? "Maret" : "March";
            //return "March";
            break;
            
        case 4 :
            
            return (!empty($locale) && $locale == 'id') ? "April" : "April";
            //return "April";
            break;
            
        case 5 :
            
            return (!empty($locale) && $locale == 'id') ? "Mei" : "May";
            //return "May";
            break;
            
        case 6 :
            
            return (!empty($locale) && $locale == 'id') ? "Juni" : "June";
            //return "June";
            break;
        case 7 :
            
            return (!empty($locale) && $locale == 'id') ? "Juli" : "July";
            //return "July";
            break;
            
        case 8 :
            
            return (!empty($locale) && $locale == 'id') ? "Agustus" : "August";
            
            //return "August";
            break;
            
        case 9 :
            
            return (!empty($locale) && $locale == 'id') ? "September" : "September";
            //return "September";
            break;
            
        case 10 :
            
            return (!empty($locale) && $locale == 'id') ? "Oktober" : "October";
            //return "October";
            break;
            
        case 11 :
            
            return (!empty($locale) && $locale == 'id') ? "November" : "November";
            //return "November";
            break;
            
        case 12 :
            
            return (!empty($locale) && $locale == 'id') ? "Desember" : "December";
            //return "December";
            break;
            
    }
    
}