<?php

function rename_file($filename)
{
  return preg_replace('/\s+/', '_', $filename);
}