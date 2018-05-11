<?php
/**
 * Install database table
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
user_fullname VARCHAR(120) NOT NULL DEFAULT '',
user_url VARCHAR(100) NOT NULL DEFAULT '#',
user_registered DATE NOT NULL,
user_activation_key varchar(255) NOT NULL DEFAULT '',
user_reset_key varchar(255) DEFAULT '',
user_reset_complete VARCHAR(3) DEFAULT 'No',
user_status enum('0','1') NOT NULL DEFAULT '0',
user_session VARCHAR(255) NOT NULL,
PRIMARY KEY(ID)
)Engine=InnoDB DEFAULT CHARSET=utf8";
        
$tablePost = "CREATE TABLE IF NOT EXISTS posts (
ID bigint(20) unsigned NOT NULL auto_increment,
post_image varchar(512) DEFAULT NULL,
post_author bigint(20) unsigned NOT NULL DEFAULT 0,
date_created date NOT NULL,
date_modified date NOT NULL,
post_title varchar(255) NOT NULL,
post_slug varchar(255) NOT NULL,
post_content longtext NOT NULL,
post_summary tinytext DEFAULT '',
post_keyword text DEFAULT '',
post_status varchar(20) NOT NULL DEFAULT 'publish',
post_type varchar(120) NOT NULL DEFAULT 'blog',
comment_status varchar(20) NOT NULL DEFAULT 'open',
PRIMARY KEY (ID)
)Engine=InnoDB DEFAULT CHARSET=utf8";
    
$tableTopic = "CREATE TABLE IF NOT EXISTS topics(
ID bigint(20) unsigned NOT NULL auto_increment,
topic_title varchar(255) NOT NULL,
topic_slug varchar(255) NOT NULL,
topic_status enum('Y','N') NOT NULL DEFAULT 'Y',
PRIMARY KEY (ID)
)Engine=InnoDB DEFAULT CHARSET=utf8";
    
$tablePostTopic = "CREATE TABLE IF NOT EXISTS post_topic(
ID BIGINT(20) unsigned NOT NULL auto_increment,
post_id bigint(20) unsigned DEFAULT NULL,
topic_id bigint(20) unsigned DEFAULT NULL,
PRIMARY KEY(ID)
)Engine=InnoDB DEFAULT CHARSET=utf8";
    
$tableComment = "CREATE TABLE IF NOT EXISTS comments(
ID BIGINT(20) unsigned NOT NULL auto_increment,
comment_post_id BIGINT(20) unsigned NOT NULL,
comment_author_name VARCHAR(60) NOT NULL,
comment_author_url VARCHAR(200) NOT NULL,
comment_author_ip VARCHAR(100) NOT NULL,
comment_content text NOT NULL,
comment_status enum('0','1') NOT NULL DEFAULT '0',
comment_date DATE NOT NULL,
user_id BIGINT(20) unsigned NOT NULL DEFAULT 0,
PRIMARY KEY(ID)
)Engine=InnoDB DEFAULT CHARSET=utf8";
    
$tableMenu = "CREATE TABLE IF NOT EXISTS menu(
ID BIGINT(20) unsigned NOT NULL auto_increment,
menu_label VARCHAR(200) NOT NULL,
menu_link VARCHAR(255) NOT NULL DEFAULT '#',
menu_sort INT(5) DEFAULT NULL,
menu_status enum('Y','N') NOT NULL DEFAULT 'Y',
PRIMARY KEY(ID)
)Engine=InnoDB DEFAULT CHARSET=utf8";
    
$tableMenuChild = "CREATE TABLE IF NOT EXISTS menu_child(
ID BIGINT(20) unsigned NOT NULL auto_increment,
menu_child_label VARCHAR(200) NOT NULL,
menu_child_link VARCHAR(255) NOT NULL DEFAULT '#',
menu_id BIGINT(20) unsigned NOT NULL,
menu_sub_child BIGINT(20) unsigned NOT NULL,
menu_child_sort INT(5) DEFAULT NULL,
menu_child_status enum('Y','N') NOT NULL DEFAULT 'Y',
PRIMARY KEY(ID)
)Engine=InnoDB DEFAULT CHARSET=utf8";
    
$tablePlugin = "CREATE TABLE IF NOT EXISTS plugin(
ID BIGINT(20) unsigned NOT NULL auto_increment,
plugin_name VARCHAR(100) NOT NULL,
plugin_link VARCHAR(100) NOT NULL,
plugin_desc tinytext,
plugin_status enum('Y','N') NOT NULL DEFAULT 'N',
plugin_level VARCHAR(20) NOT NULL,
plugin_sort INT(5) DEFAULT NULL,
PRIMARY KEY(ID)
)Engine=InnoDB DEFAULT CHARSET=utf8";
    
$tableSetting = "CREATE TABLE IF NOT EXISTS settings(
ID SMALLINT(5) unsigned NOT NULL,
app_key VARCHAR(255) DEFAULT NULL,
site_name VARCHAR(100) NOT NULL,
meta_description text DEFAULT NULL,
meta_keywords tinytext DEFAULT NULL,
logo VARCHAR(255) NOT NULL DEFAULT '',
facebook_url VARCHAR(200) NOT NULL DEFAULT '#',
twitter_url VARCHAR(200) NOT NULL DEFAULT '#',
instagram_url VARCHAR(200) NOT NULL DEFAULT '#',
PRIMARY KEY(ID)
)Engine=InnoDB DEFAULT CHARSET=utf8";
    
$tableTheme = "CREATE TABLE IF NOT EXISTS themes(
ID BIGINT(20) unsigned NOT NULL auto_increment,
theme_title VARCHAR(100) NOT NULL,
theme_desc tinytext,
theme_designer VARCHAR(90) NOT NULL,
theme_directory VARCHAR(100) NOT NULL,
theme_status enum('Y','N') NOT NULL DEFAULT 'N',
PRIMARY KEY(ID)
)Engine=InnoDB DEFAULT CHARSET=utf8";

$saveAdmin = "INSERT INTO users (user_login, user_email, user_pass, user_level,
user_registered, user_activation_key, user_status, user_session) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$saveAppKey = "INSERT INTO settings (app_key) VALUES(?)";

$date_registered = date("Ymd");
$user_activation_key = md5( mt_rand( 10000, 99999 ) . time() . $value . 'c#haRl891');
$user_session = md5($user_email);
$shield_pass = password_hash($user_pass, PASSWORD_DEFAULT);
$user_level = 'Administrator';
$user_status = '1';

if ($link instanceof mysqli) $newTableUser = $link -> query($tableUser);
$createAdmin = $link ->prepare($saveAdmin);
$createAdmin -> bind_param("ssssssss", $user_login, $user_email, 
    $shield_pass, $user_level, $date_registered, 
    $user_activation_key, $user_status, $user_session);
$createAdmin -> execute();

if ($link -> insert_id && $createAdmin -> affected_rows > 0) {
    
    // create other database tables
    $newTablePost = $link -> query($tablePost);
    $newTableTopic = $link -> query($tableTopic);
    $newTablePostTopic = $link -> query($tablePostTopic);
    $newTableComment = $link -> query($tableComment);
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
 * Write configuration file
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

$lenght = 13;

$url = $protocol.'://'.$server_host.dirname(dirname($_SERVER['PHP_SELF'])).'/';

$link = mysqli_connect($host, $user, $password, $database);

if (isset($_SESSION['install']) && $_SESSION['install'] == true) {
   
   $getAppKey = "SELECT ID, app_key FROM settings WHERE app_key = '$key' LIMIT 1";
   $retrieve_app_info = mysqli_query($link, $getAppKey);
   $row = mysqli_fetch_assoc($retrieve_app_info);
   
   if (function_exists("random_bytes")) {
       $bytes = random_bytes(ceil($lenght / 2));
   } elseif (function_exists("openssl_random_pseudo_bytes")) {
       $bytes = openssl_random_pseudo_bytes(ceil($lenght / 2));
   } else {
       throw new Exception("no cryptographically secure random function available");
   }
   
   $app_key = generate_license(substr(bin2hex($bytes), 0, $lenght));

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
                   'url' => '".addslashes($url)."',
                   'email' => '".addslashes($email)."',
                   'key' => '".addslashes($app_key)."'
                   ]

            ];";
     
    if (isset($_SESSION['token'])) file_put_contents(__DIR__ . '/../../config.php', $fileconfig);
      
 }

}

/**
 * Remove bad characters
 * 
 * @param string $str_words
 * @param boolean $escape
 * @param string $level
 * @return string
 */
function remove_bad_characters($str_words, $escape = false, $level = 'high')
{
    $found = false;
    $str_words = htmlentities(strip_tags($str_words));
    if($level == 'low'){
        $bad_string = array('drop', '--', 'insert', 'xp_', '%20union%20', '/*', '*/union/*', '+union+', 'load_file', 'outfile', 'document.cookie', 'onmouse', '<script', '<iframe', '<applet', '<meta', '<style', '<form', '<body', '<link', '_GLOBALS', '_REQUEST', '_GET', '_POST', 'include_path', 'prefix', 'ftp://', 'smb://', 'onmouseover=', 'onmouseout=');
    }else if($level == 'medium'){
        $bad_string = array('select', 'drop', '--', 'insert', 'xp_', '%20union%20', '/*', '*/union/*', '+union+', 'load_file', 'outfile', 'document.cookie', 'onmouse', '<script', '<iframe', '<applet', '<meta', '<style', '<form', '<body', '<link', '_GLOBALS', '_REQUEST', '_GET', '_POST', 'include_path', 'prefix', 'ftp://', 'smb://', 'onmouseover=', 'onmouseout=');
    }else{
        $bad_string = array('select', 'drop', '--', 'insert', 'xp_', '%20union%20', '/*', '*/union/*', '+union+', 'load_file', 'outfile', 'document.cookie', 'onmouse', '<script', '<iframe', '<applet', '<meta', '<style', '<form', '<img', '<body', '<link', '_GLOBALS', '_REQUEST', '_GET', '_POST', 'include_path', 'prefix', 'http://', 'https://', 'ftp://', 'smb://', 'onmouseover=', 'onmouseout=');
    }
    for($i = 0; $i < count($bad_string); $i++){
        $str_words = str_replace($bad_string[$i], '', $str_words);
    }
    
    if($escape){
        $str_words = mysqli_real_escape_string($str_words);
    }
    
    return $str_words;
}

// delete directory https://secure.php.net/manual/es/function.rmdir.php
function deleterDir($dirPath)
{
 
    if (! is_dir($dirPath)) {
        throw new InvalidArgumentException("$dirPath must be a directory");
    }
    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
        $dirPath .= '/';
    }
    $files = glob($dirPath . '*', GLOB_MARK);
    foreach ($files as $file) {
        if (is_dir($file)) {
            self::deleteDir($file);
        } else {
            unlink($file);
        }
    }
    
    rmdir($dirPath);
 
}

// escape html 
function escapeHTML($html)
{
 return htmlspecialchars($html, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
}

/**
 * generate license
 * to generate app key
 * 
 * @link stackoverflow.com/questions/3687878/serial-generation-with-php
 * @param string $suffix
 * @return string
 */
function generate_license($suffix = null) {
    // Default tokens contain no "ambiguous" characters: 1,i,0,o
    if(isset($suffix)){
        // Fewer segments if appending suffix
        $num_segments = 3;
        $segment_chars = 6;
    }else{
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
        }else{
            $long = sprintf("%u\n", ip2long($suffix),true);
            if($suffix === long2ip($long) ) {
                $license_string .= '-'.strtoupper(base_convert($long,10,36));
            }else{
                $license_string .= '-'.strtoupper(str_ireplace(' ','-',$suffix));
            }
        } 
    }
    return $license_string;
}

/**
 * Purge installation
 * Cleaning all installation process
 * 
 */
function purge_installation()
{
   
 if (is_readable(__DIR__ . '/../../config.php')) {
     
     if (is_file(__DIR__ . '/../index.php')) {
     
         $data_app = [
             'app_title'    => APP_TITLE,
             'app_codename' => APP_CODENAME,
             'app_version'  => APP_VERSION
         ];
         
         $disabled = $_SERVER['DOCUMENT_ROOT'].'/'.substr(bin2hex(openssl_random_pseudo_bytes(32)), 0, 13).'-'.date("Ymd").'.log';
         
         rename(__DIR__ . '/../index.php', $disabled);
         
         $clean_installation = '<?php ';
         
         file_put_contents(__DIR__ . '/../index.php', $clean_installation);
         
     }
     
    $_SESSION = array();
        
    session_destroy();
        
    setcookie('PHPSESSID', '', time()-3600, '/', '', 0, 0);
    
 }
    
}