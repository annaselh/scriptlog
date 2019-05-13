<?php

function cp_tag_title($value) 
{
    switch($value) {

         case 'posts':

             echo 'Post';

            break;

         case 'medialib':

             echo 'Media Library';

             break;

         case 'comments':
            
             echo 'Comments';

             break;

         case 'pages':

             echo 'Pages';

             break;

         default:
            
           echo 'Dashboard';

           break;

    }
    
}