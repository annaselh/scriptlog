<?php
/**
 * Application URL Function
 * 
 * @return string
 * 
 */
function app_url()
{
  if(filter_var(app_info()['app_url'], FILTER_SANITIZE_FULL_SPECIAL_CHARS)) {
      
      if(filter_var(app_info()['app_url'], FILTER_VALIDATE_URL)) {
          
        return app_info()['app_url'];
        
      }

  }
  
}