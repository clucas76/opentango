<?php

$data = $_POST['data'];
$name = $_POST['name'];

$data = substr($data,strpos($data,",")+1);
$data = base64_decode($data);
$file = "/tmp/qos/" . $name . ".png";
file_put_contents($file, $data);
echo "Success! (" . $file . ")";

?>
