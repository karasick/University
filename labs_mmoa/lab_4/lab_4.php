<?php
include 'html/main_page.html';
ini_set('error_reporting', E_ALL);

$variant = 25;
$X = array(-24, -22, -18, -14, -10, -6, -4, -2, 0, 2, 4, 6, 10, 14, 18);
$Y = array(
    array(55, 50, 45, 40, 35, 30, 25, 20, 15, 20, 25, 35, 40, 45, 50), 
    array(50, 45, 40, 35, 30, 25, 20, 15, 10, 15, 20, 30, 35, 40, 45), 
    array(45, 40, 35, 30, 25, 20, 15, 10, 5, 10, 15, 25, 30, 35, 40), 
    array(35, 30, 25, 20, 15, 10, 5, 3, 1, 3, 5, 10, 15, 20, 25, 30), 
    array(40, 35, 30, 25, 20, 15, 10, 5, 2, 2.5, 5, 10, 15, 20, 25), 
    array(45, 40, 35, 30, 25, 20, 15, 10, 5, 10, 15, 20, 25, 30, 35), 
    array(50, 45, 40, 35, 30, 25, 20, 15, 10, 15, 20, 25, 30, 35, 40), 
    array(60, 50, 45, 40, 35, 30, 25, 20, 15, 20, 25, 30, 35, 40, 50)
);

foreach ($X as $x_value) {
    echo $x_value . " ";
}

?>

