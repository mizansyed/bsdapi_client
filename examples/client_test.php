<?php
require '../constitutents.php';

$c = new Constituent();
$output = $c->email_register('mizan.r.syed@gmail.com');

print_r($output);

?>