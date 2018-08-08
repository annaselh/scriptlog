<?php

require_once(__DIR__ . '/../library/main-reserve.php');

$classes_dir = dirname(__FILE__). '/classes/';

echo $classes_dir;

$users = new User();
$data = $users -> getUsers();
?>

<html>
<head>
<title></title>
</head>
<body>
<table>
<thead>
<tr>
   <th>Username</th>
</tr>
</thead>
<tbody>
<?php 
foreach ($data as $key => $value) :
?>
<tr>
  <td><?= $value->user_login; ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</body>
</html>

