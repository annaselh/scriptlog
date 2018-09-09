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

/**
 * Install Database Table Function
 * 
 * @param string $link
 * @param string $user_login
 * @param string $user_pass
 * @param string $user_email
 * @param string $key
 */
function install_database_table($link, $user_login, $user_pass, $user_email, $key)
{

$tableUser = "CREATE TABLE IF NOT EXISTS users(
ID BIGINT(20) unsigned NOT NULL auto_increment,
user_login VARCHAR(60) NOT NULL,
user_email VARCHAR(100) NOT NULL,
user_pass VARCHAR(255) NOT NULL,
user_level VARCHAR(20) NOT NULL,
user_fullname VARCHAR(120) DEFAULT NULL,
user_url VARCHAR(100) DEFAULT '#',
user_registered datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
user_activation_key varchar(255) NOT NULL DEFAULT '',
user_reset_key varchar(255) DEFAULT NULL,
user_reset_complete VARCHAR(3) DEFAULT 'No',
user_session VARCHAR(255) NOT NULL,
PRIMARY KEY(ID)
)Engine=InnoDB DEFAULT CHARSET=utf8mb4";
        
$tablePost = "CREATE TABLE IF NOT EXISTS posts (
ID bigint(20) unsigned NOT NULL auto_increment,
post_image varchar(512) DEFAULT NULL,
post_author bigint(20) unsigned NOT NULL DEFAULT 0,
post_date datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
post_modified datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
post_title varchar(255) NOT NULL,
post_slug varchar(255) NOT NULL,
post_content longtext NOT NULL,
post_summary tinytext DEFAULT '',
post_keyword text DEFAULT '',
post_status varchar(20) NOT NULL DEFAULT 'publish',
post_type varchar(120) NOT NULL DEFAULT 'blog',
comment_status varchar(20) NOT NULL DEFAULT 'open',
PRIMARY KEY (ID)
)Engine=InnoDB DEFAULT CHARSET=utf8mb4";
    
$tableTopic = "CREATE TABLE IF NOT EXISTS topics(
ID bigint(20) unsigned NOT NULL auto_increment,
topic_title varchar(255) NOT NULL,
topic_slug varchar(255) NOT NULL,
topic_status enum('Y','N') NOT NULL DEFAULT 'Y',
PRIMARY KEY (ID)
)Engine=InnoDB DEFAULT CHARSET=utf8mb4";
    
$tablePostTopic = "CREATE TABLE IF NOT EXISTS post_topic(
ID BIGINT(20) unsigned NOT NULL auto_increment,
post_id bigint(20) unsigned DEFAULT NULL,
topic_id bigint(20) unsigned DEFAULT NULL,
PRIMARY KEY(ID)
)Engine=InnoDB DEFAULT CHARSET=utf8mb4";
    
$tableComment = "CREATE TABLE IF NOT EXISTS comments(
ID BIGINT(20) unsigned NOT NULL auto_increment,
comment_post_id BIGINT(20) unsigned NOT NULL,
comment_author_name VARCHAR(60) NOT NULL,
comment_author_ip VARCHAR(100) NOT NULL,
comment_content text NOT NULL,
comment_status VARCHAR(20) NOT NULL DEFAULT 'approved',
comment_date datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
PRIMARY KEY(ID)
)Engine=InnoDB DEFAULT CHARSET=utf8mb4";

$tableReply = "CREATE TABLE IF NOT EXISTS comment_reply(
ID BIGINT(20) unsigned NOT NULL auto_increment,
comment_id BIGINT(20)unsigned NOT NULL,
user_id BIGINT(20) unsigned NOT NULL,
reply_content text NOT NULL,
reply_status enum('0','1') NOT NULL DEFAULT '1',
reply_date datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
PRIMARY KEY(ID)
)Engine=InnoDB DEFAULT CHARSET=utf8mb4";
    
$tableMenu = "CREATE TABLE IF NOT EXISTS menu(
ID BIGINT(20) unsigned NOT NULL auto_increment,
menu_label VARCHAR(200) NOT NULL,
menu_link VARCHAR(255) NOT NULL DEFAULT '#',
menu_sort INT(5) DEFAULT NULL,
menu_status enum('Y','N') NOT NULL DEFAULT 'Y',
PRIMARY KEY(ID)
)Engine=InnoDB DEFAULT CHARSET=utf8mb4";
    
$tableMenuChild = "CREATE TABLE IF NOT EXISTS menu_child(
ID BIGINT(20) unsigned NOT NULL auto_increment,
menu_child_label VARCHAR(200) NOT NULL,
menu_child_link VARCHAR(255) NOT NULL DEFAULT '#',
menu_id BIGINT(20) unsigned NOT NULL,
menu_sub_child BIGINT(20) unsigned NOT NULL,
menu_child_sort INT(5) DEFAULT NULL,
menu_child_status enum('Y','N') NOT NULL DEFAULT 'Y',
PRIMARY KEY(ID)
)Engine=InnoDB DEFAULT CHARSET=utf8mb4";
    
$tablePlugin = "CREATE TABLE IF NOT EXISTS plugin(
ID BIGINT(20) unsigned NOT NULL auto_increment,
plugin_name VARCHAR(100) NOT NULL,
plugin_link VARCHAR(100) NOT NULL DEFAULT '#',
plugin_desc tinytext,
plugin_status enum('Y','N') NOT NULL DEFAULT 'N',
plugin_level VARCHAR(20) NOT NULL,
plugin_sort INT(5) DEFAULT NULL,
PRIMARY KEY(ID)
)Engine=InnoDB DEFAULT CHARSET=utf8mb4";
    
$tableSetting = "CREATE TABLE IF NOT EXISTS settings(
ID SMALLINT(5) unsigned NOT NULL,
app_key VARCHAR(255) DEFAULT NULL,
app_url VARCHAR(255) NOT NULL DEFAULT '#',
site_name VARCHAR(100) NOT NULL,
meta_description text DEFAULT NULL,
meta_keywords tinytext DEFAULT NULL,
logo VARCHAR(255) NOT NULL DEFAULT '',
email_address VARCHAR(150) DEFAULT NULL,
facebook VARCHAR(200) NOT NULL DEFAULT '#',
twitter VARCHAR(200) NOT NULL DEFAULT '#',
instagram VARCHAR(200) NOT NULL DEFAULT '#',
PRIMARY KEY(ID)
)Engine=InnoDB DEFAULT CHARSET=utf8mb4";
    
$tableTheme = "CREATE TABLE IF NOT EXISTS themes(
ID BIGINT(20) unsigned NOT NULL auto_increment,
theme_title VARCHAR(100) NOT NULL,
theme_desc tinytext,
theme_designer VARCHAR(90) NOT NULL,
theme_directory VARCHAR(100) NOT NULL,
theme_status enum('Y','N') NOT NULL DEFAULT 'N',
PRIMARY KEY(ID)
)Engine=InnoDB DEFAULT CHARSET=utf8mb4";

$saveAdmin = "INSERT INTO users (user_login, user_email, user_pass, user_level,
user_registered, user_session) 
VALUES (?, ?, ?, ?, ?, ?)";

$saveAppKey = "INSERT INTO settings (app_key) VALUES(?)";

$date_registered = date("Ymd");
$user_session = md5($user_email);
$shield_pass = password_hash(base64_encode(hash('sha384', $password, true)), PASSWORD_DEFAULT);;
$user_level = 'administrator';

#create users table
if ($link instanceof mysqli) $newTableUser = $link -> query($tableUser);
#save administrator
$createAdmin = $link ->prepare($saveAdmin);
$createAdmin -> bind_param("ssssss", $user_login, $user_email, 
    $shield_pass, $user_level, $date_registered, $user_session);
$createAdmin -> execute();

if ($link -> insert_id && $createAdmin -> affected_rows > 0) {
    
    // create other database tables
    $newTablePost = $link -> query($tablePost);
    $newTableTopic = $link -> query($tableTopic);
    $newTablePostTopic = $link -> query($tablePostTopic);
    $newTableComment = $link -> query($tableComment);
    $newTableReply = $link -> query($tableReply);
    $newTableMenu = $link -> query($tableMenu);
    $newTableMenuChild = $link -> query($tableMenuChild);
    $newTablePlugin = $link -> query($tablePlugin);
    $newTableSetting = $link -> query($tableSetting);
    $newTableTheme = $link -> query($tableTheme);
    
    // insert app key
    $recordAppKey = $link -> prepare($saveAppKey);
    $recordAppKey -> bind_param('s', $key);
    $recordAppKey -> execute();
    
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
 */
function write_config_file($host, $user, $password, $database, $email, $key)
{

global $protocol, $server_host;

$length = 13;

$url = $protocol.'://'.$server_host.dirname(dirname($_SERVER['PHP_SELF'])).'/';

$link = mysqli_connect($host, $user, $password, $database);

if (isset($_SESSION['install']) && $_SESSION['install'] == true) {
   
   $getAppKey = "SELECT ID, app_key FROM settings WHERE app_key = '$key' LIMIT 1";
   $retrieve_app_info = mysqli_query($link, $getAppKey);
   $row = mysqli_fetch_assoc($retrieve_app_info);
   
   if (function_exists("random_bytes")) {
       
       $bytes = random_bytes(ceil($length / 2));
       
   } elseif (function_exists("openssl_random_pseudo_bytes")) {
       
       $bytes = openssl_random_pseudo_bytes(ceil($length / 2));
       
   } else {
       
      trigger_error("no cryptographically secure random function available", E_USER_NOTICE);
       
   }
   
   $app_key = generate_license(substr(bin2hex($bytes), 0, $length));

   $updateAppKey = "UPDATE settings SET app_key = '$app_key'
                    WHERE ID = {$row['ID']} LIMIT 1";
    mysqli_query($link, $updateAppKey);
    mysqli_close($link);
    
    $fileconfig = '<?php  return ['."
                    
            'db' => [
                  'host' => '".addslashes($host)."',
                  'user' => '".addslashes($user)."',
                  'pass' => '".addslashes($password)."',
                  'name' => '".addslashes($database)."'
                  ],
        
            'app' => [
                   'url'   => '".addslashes($url)."',
                   'email' => '".addslashes($email)."',
                   'key'   => '".addslashes($app_key)."'
                   ]

            ];";
     
    if (isset($_SESSION['token'])) file_put_contents(__DIR__ . '/../../config.php', $fileconfig);
      
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
  
  $tokens = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
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
            
            $long = sprintf("%u\n", ip2long($suffix),true);
            
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
 * Purge Intallation Function
 * Clean up installation procedure
 * 
 */
function purge_installation()
{
   
 $length = 32;
 
 if (is_readable(__DIR__ . '/../../config.php')) {
     
     if (is_file(__DIR__ . '/../index.php')) {
    
         
         if (function_exists("random_bytes")) {
             
             $bytes = random_bytes(ceil($length / 2));
             
         } elseif (function_exists("openssl_random_pseudo_bytes")) {
             
             $bytes = openssl_random_pseudo_bytes(ceil($length / 2));
             
         } else {
             
             trigger_error("no cryptographically secure random function available", E_USER_NOTICE);
             
         }
         
        $disabled = $_SERVER['DOCUMENT_ROOT'].'/'.substr(bin2hex($bytes), 0, $length).'-'.date("Ymd").'.log';
         
        rename(__DIR__ . '/../index.php', $disabled);
         
        $clean_installation = '<?php ';
         
        file_put_contents(__DIR__ . '/../index.php', $clean_installation);
         
     }
     
    $_SESSION = array();
        
    session_destroy();
        
    setcookie('PHPSESSID', '', time()-3600, '/', '', 0, 0);
    
 }
    
}