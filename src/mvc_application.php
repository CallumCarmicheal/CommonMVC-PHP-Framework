<?php 

require_once ('cmvc/bootstrap.php');

$app = new Bootstrap();
$app->run($_GET['virtual_path']);