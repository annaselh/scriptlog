<?php

$rules = array(
           
    '/'        => "/",
    'category' => "/category/(?'category'[\w\-]+)",
    'page'     => "/page/(?'page'[^/]+)",
    'post'     => "/post/(?'id'\d+)/(?'post'[\w\-]+)",
    'posts'    => "/posts/([^/]*)",
    'search'   => "(?'search'[\w\-]+)" 
  
);
