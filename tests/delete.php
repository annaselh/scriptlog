<?php
require(__DIR__ . '/../library/main.php');

if (isset($_GET['id'])) {
    
    $id = $_GET['id'];
    $sanitize = new Sanitize();
    $users = new User();
    $hapus = $users -> deleteUser($id, $sanitize);
    header("Location: ../tests/index.php");
    
}