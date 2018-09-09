<?php

require_once(__DIR__ . '/../library/main-reserve.php');
/*
$classes_dir = dirname(__FILE__). '/classes/';

echo $classes_dir;

$users = new User();
$data = $users -> getUsers();
*/

?>

<html>
<head>
<title></title>
</head>
<body>
<table>
<thead>
<tr>
   <th>URL :</th>
</tr>
</thead>
<tbody>
<?php 
//foreach ($data as $key => $value) :
?>
<tr>
  <td>
  <?php 
    $field = array('load' => 'banners');
    
    $url = APP_PROTOCOL . '://' . APP_HOSTNAME . dirname($_SERVER['PHP_SELF']) . '/index.php?'.http_build_query($field);

    echo $url;
  ?>
  </td>
</tr>
<?php //endforeach; ?>
</tbody>
</table>
</body>
</html>

