<?php
$time_start = microtime(true);

$errors = [];

$installation_path = __DIR__ . '/../install/';
$current_path = __DIR__ . '/';
$ParentPath = dirname($current_path) . '/';

echo 'Installation Path: '. $installation_path . '<br>';

echo 'Current Path: '. $current_path .'<br>';

echo 'Parent Path: ' . $ParentPath . '<br>';

echo '<pre>';
/*
$filesaved = '<?php  return ['."
    'db' => [
        
        'host' => 'localhost',
        'name' => 'setupdb',
        'user' => 'root',
        'pass' => 'kartatopia'
    ],
    
    'app' => [
        
        'url' => 'application_url',
        'email' => 'application_email',
        'key' => 'application_key'
    ]
    
];";
*/

// inser new records
if (file_exists(__DIR__ . '/../config.php')) {
    include(__DIR__ . '/../library/main.php');
}


$users = new User();

echo '<h1>User</h1><br><br>';
echo '<pre>';
// list of users
?>
<a href="insert.php?id=0">create User</a><br>
<?php 
echo '<h2>List of Users</h2>';

$results = $users -> getUsers(0, 20);
foreach ($results as $r) :
    
?>
<html>
<body>
<table>

<tr>
<td>Username</td>
<td>Email</td>
<td>Level</td>
<td>Edit</td>
<td>Delete</td>
<tr>

<tr>
<td><?= $r -> user_login; ?></td>
<td><?= $r -> user_email; ?></td>
<td><?= $r -> user_level; ?></td>
<td><a href="update.php?id=<?= (int)$r -> ID; ?>">Edit</a></td>
<td><a href="delete.php?id=<?= (int)$r -> ID; ?>">Delete</a></td>
</tr>

</table>
</body>
</html>
<?php 
endforeach;

$action = isset($_GET['action']);

switch ($action) {
  
  case 'insert':
     
    include 'insert.php';

  
    break;
  
  case 'update';
    
     include 'update.php';
     
    break;
    
  case 'delete':
      
      include 'delete.php';
      
  default:;
  
}

echo '</pre>';
$time_end = microtime(true);
$time = $time_end - $time_start;
echo '<br><br>';
echo 'Memory Consumption is '. round(memory_get_usage()/1048576,2).''.' MB<br>'; 
echo  $time . ' seconds';
echo '</pre>';