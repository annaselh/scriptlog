<?php
#######################################################################
#   Setup.php File
#   This is file to setup installation and write config.php file
#   @Package    SCRIPTLOG
#   @author     M.Noermoehammad
#   @license    MIT
#   @version    1.0
#   @since      Since Release 1.0
#######################################################################

function current_url()
{
   $scheme = (!empty($_SERVER['HTTPS']) and $_SERVER['HTTPS'] !== "off") ? "https" : "http" ;
   
   $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'] ;
   
   return $scheme."://".$host.dirname($_SERVER['PHP_SELF']) . "/";

}

/**
 * function makeConnection
 * 
 * @param string $host
 * @param string $username
 * @param string $passwd
 */
function make_connection($host, $username, $passwd, $dbname)
{

  $connect = new mysqli($host, $username, $passwd, $dbname);

  return $connect;

}

/**
 * function closeConnection
 * closing database connection
 * 
 * @param string $link
 * 
 */
function close_connection($link)
{
  $link -> close();
}

/**
 * function table_exists
 * checking whether table exists or not
 * 
 * @param string $link
 * @param string $table
 * @param numeric $counter
 * 
 */
function table_exists($link, $table, $counter = 0)
{
    if ($link instanceof mysqli) 
    
    $counter++;

    $check = $link->query( "SHOW TABLES LIKE '".$table."'");
    
    if($check !== false) {

        if( $check->num_rows > 0 ) {

            return true;

        } else {

            return false;

        }

    } else {

        return false;
        
    }

}

/**
 * function check_dbtable
 * 
 * @param string $link
 * @param string $table
 * 
 */
function check_dbtable($link, $table)
{
  $install = false;

  if(!table_exists($link, $table)) {
      
    $install = true;

  } else {

    $install = false;

  }

  return $install;

}

/**
 * Install Database Table Function
 * 
 * @param string $link
 * @param string $user_login
 * @param string $user_pass
 * @param string $user_email
 * @param string $key
 * 
 */
function install_database_table($link, $protocol, $server_host, $user_login, $user_pass, $user_email, $key)
{

require __DIR__ . '/dbtable.php';

// Users  
$date_registered = date("Y-m-d H:i:s");
$user_session    = md5(microtime());
$shield_pass     = password_hash(base64_encode(hash('sha384', $user_pass, true)), PASSWORD_DEFAULT);
$user_level      = 'administrator';

// Theme 
$theme_title     = "Bootstrap Blog";
$theme_desc      = "Ondrej Svestka create this beautiful responsive blog theme and we are really love to use it as our default theme";
$theme_designer  = "Ondrej Svestka";
$theme_directory = "themes/bootstrapious";
$theme_status    = "Y";

// setting App Key
$setting_name_key = "app_key";

// Setting App URL
$setting_name_url  = "app_url";
$setting_value_url = $protocol.'://'.$server_host.dirname(dirname($_SERVER['PHP_SELF'])).DIRECTORY_SEPARATOR;

#create users table
if ($link instanceof mysqli)  

$createUser = $link -> query($tableUser);

#save administrator
$createAdmin = $link ->prepare($saveAdmin);
$createAdmin -> bind_param("ssssss", $user_login, 
$user_email, $shield_pass, $user_level, $date_registered, $user_session);
$createAdmin -> execute();

if ($link -> insert_id && $createAdmin -> affected_rows > 0) {
    
    // create other database tables
    $createUserToken = $link -> query($tableUserToken);
    $createPost = $link -> query($tablePost);
    $createTopic = $link -> query($tableTopic);
    $createPostTopic = $link -> query($tablePostTopic);
    $createComment = $link -> query($tableComment);
    $createReply = $link -> query($tableReply);
    $createMenu = $link -> query($tableMenu);
    $createMenuChild = $link -> query($tableMenuChild);
    $createMedia = $link -> query($tableMedia);
    $createMediaMeta = $link -> query($tableMediaMeta);
    $createPlugin = $link -> query($tablePlugin);
    $createSetting = $link -> query($tableSetting);
    $createTheme = $link -> query($tableTheme);
    
    // insert configuration - setting app key
    $recordAppKey = $link -> prepare($saveAppKey);
    $recordAppKey -> bind_param('ss', $setting_name_key, $key);
    $recordAppKey -> execute();

    // insert configuration - setting app url
    $recordAppURL = $link -> prepare($saveAppURL);
    $recordAppURL -> bind_param('ss', $setting_name_url, $setting_value_url);
    $recordAppURL -> execute();
    
    // insert default theme
    $recordTheme = $link -> prepare($saveTheme);
    $recordTheme -> bind_param('sssss', $theme_title, $theme_desc, $theme_designer, $theme_directory, $theme_status);
    $recordTheme -> execute();

    if ($recordAppKey -> affected_rows > 0) $link -> close();
 
}

}

/**
 * Write Config File Function
 * 
 * @param string $host
 * @param string $user
 * @param string $password
 * @param string $database
 * @param string $email
 * @param string $key
 * @throws Exception
 * 
 */
function write_config_file($protocol, $server_name, $host, $user, $password, $database, $email, $key)
{

$link = mysqli_connect($host, $user, $password, $database);

if (isset($_SESSION['install']) && $_SESSION['install'] == true) {
   
   $getAppKey = "SELECT ID, setting_name, setting_value FROM settings 
                 WHERE setting_value = '$key'";
   
   $row = mysqli_fetch_assoc(mysqli_query($link, $getAppKey));

   $app_key = generate_license(substr($row['setting_value'], 0, 5));

   $updateAppKey = "UPDATE settings SET setting_value = '$app_key'
                    WHERE setting_name = 'app_key' 
                    AND ID = {$row['ID']} LIMIT 1";

    mysqli_query($link, $updateAppKey);
    mysqli_close($link);
    
    $configFile = '<?php  
    
    return ['."
                    
            'db' => [

                  'host' => '".addslashes($host)."',
                  'user' => '".addslashes($user)."',
                  'pass' => '".addslashes($password)."',
                  'name' => '".addslashes($database)."'
                  
                ],
        
            'app' => [

                   'url'   => '".addslashes($protocol.'://'.$server_name.dirname(dirname($_SERVER['PHP_SELF'])).DIRECTORY_SEPARATOR)."',
                   'email' => '".addslashes($email)."',
                   'key'   => '".addslashes($app_key)."'
                   
                ]

        ];";
     
    if (isset($_SESSION['token'])) {

        file_put_contents(__DIR__ . '/../../config.php', $configFile);
        
    }
      
 }

}

/**
 * Remove Bad Characters
 * 
 * @link https://stackoverflow.com/questions/14114411/remove-all-special-characters-from-a-string#14114419
 * @link https://stackoverflow.com/questions/19167432/strip-bad-characters-from-an-html-php-contact-form
 * @param string $str_words
 * @param boolean $escape
 * @param string $level
 * @return string
 */
function remove_bad_characters($str_words, $escape = false, $level = 'high')
{
    $found = false;
    $str_words = htmlentities(strip_tags($str_words));
    if($level == 'low') {
        
        $bad_string = array('drop', '--', 'insert', 'xp_', '%20union%20', '/*', '*/union/*', '+union+', 'load_file', 'outfile', 'document.cookie', 'onmouse', '<script', '<iframe', '<applet', '<meta', '<style', '<form', '<body', '<link', '_GLOBALS', '_REQUEST', '_GET', '_POST', 'include_path', 'prefix', 'ftp://', 'smb://', 'onmouseover=', 'onmouseout=');
    
    } elseif($level == 'medium') {
        
        $bad_string = array('select', 'drop', '--', 'insert', 'xp_', '%20union%20', '/*', '*/union/*', '+union+', 'load_file', 'outfile', 'document.cookie', 'onmouse', '<script', '<iframe', '<applet', '<meta', '<style', '<form', '<body', '<link', '_GLOBALS', '_REQUEST', '_GET', '_POST', 'include_path', 'prefix', 'ftp://', 'smb://', 'onmouseover=', 'onmouseout=');
    
    } else {
        
        $bad_string = array('select', 'drop', '--', 'insert', 'xp_', '%20union%20', '/*', '*/union/*', '+union+', 'load_file', 'outfile', 'document.cookie', 'onmouse', '<script', '<iframe', '<applet', '<meta', '<style', '<form', '<img', '<body', '<link', '_GLOBALS', '_REQUEST', '_GET', '_POST', 'include_path', 'prefix', 'http://', 'https://', 'ftp://', 'smb://', 'onmouseover=', 'onmouseout=');
    
    }
    
    for($i = 0; $i < count($bad_string); $i++) {
        
        $str_words = str_replace($bad_string[$i], '', $str_words);
    
    }
    
    if($escape) {
        
       $str_words = mysqli_real_escape_string($str_words);
    
    }
    
    return $str_words;
    
}

/** 
 * Escape HTML Function
 * 
 * @param string $html
 * @return string
 * 
 */
function escapeHTML($html)
{
  return htmlspecialchars($html, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
}

/**
 * Generate License Function
 * to create serial generation of license key with php
 * 
 * @link https://stackoverflow.com/questions/3687878/serial-generation-with-php
 * @param string $suffix
 * @return string
 * 
 */
function generate_license($suffix = null) 
{
    
  // Default tokens contain no "ambiguous" characters: 1,i,0,o
  if(isset($suffix)){
           
     $num_segments = 3;
     $segment_chars = 6;
     
  } else {
      
     $num_segments = 4;
     $segment_chars = 5;
    
  }
  
  $tokens = 'ABCDEFGHJKLMNPQRSTUVWXYZ1234567890';
  $license_string = '';
    
  // Build Default License String
  for ($i = 0; $i < $num_segments; $i++) {
        
    $segment = '';
    for ($j = 0; $j < $segment_chars; $j++) {
        $segment .= $tokens[rand(0, strlen($tokens)-1)];
    }
        
    $license_string .= $segment;
    if ($i < ($num_segments - 1)) {
        $license_string .= '-';
    }
        
  }
    
   // If provided, convert Suffix
    if(isset($suffix)){
        
        if(is_numeric($suffix)) {   // Userid provided
            
            $license_string .= '-'.strtoupper(base_convert($suffix,10,36));
        
        } else {
            
            $long = sprintf("%u\n", ip2long($suffix), true);

            if($suffix === long2ip($long) ) {
                
                $license_string .= '-'.strtoupper(base_convert($long,10,36));
            
            } else {
                
                $license_string .= '-'.strtoupper(str_ireplace(' ','-',$suffix));
                
            }
            
        } 
        
    }
    
    return $license_string;
    
}

/**
 * Convert Memory Used Function
 * Format size memory usage onto b, kb, mb, gb, tb and pb
 * 
 * @param number $size
 * @return mixed
 * 
 */
function convert_memory_used($size)
{
  $unit=array('b','kb','mb','gb','tb','pb');
  return round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
}

/**
 * Purge Intallation Function
 * Clean up installation procedure
 * 
 */
function purge_installation()
{
   
 $length = 16;
 
 if (is_readable(__DIR__ . '/../../config.php')) {
     
    if (function_exists("random_bytes")) {
             
        $bytes = random_bytes(ceil($length / 2));
        
    } elseif (function_exists("openssl_random_pseudo_bytes")) {
        
        $bytes = openssl_random_pseudo_bytes(ceil($length / 2));
        
    } else {
        
        trigger_error("no cryptographically secure random function available", E_USER_NOTICE);
        
    }

    $disabled = APP_PATH . substr(bin2hex($bytes), 0, $length).'.log';

    if (is_writable(APP_PATH)) {

        if(rename(__DIR__ . '/../index.php', $disabled)) {

            $clean_installation = '<?php ';
     
            file_put_contents(__DIR__ . '/../index.php', $clean_installation);

            unset($_SESSION['token']);
     
            $_SESSION = array();
     
            session_destroy();
            
        }
        
    } 

  }
    
}