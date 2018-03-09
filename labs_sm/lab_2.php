<?php
require "C:\\xampp\htdocs\php\helpers.php";
require "binary.php";
Page::TopHead("Lab 2");

$xi;
$xinext;
$ximax = 6;
$Amax = 4;
$A = array(1 => 0);
$A_end = array(1 => 0);
$X = array(1 => 0);
$y = array(1 => 0);

$A = array_fill(1, $Amax, array_fill(1, 2, array_fill(1, 3, 0)));
$x = array(1 => $_POST['x1'], $_POST['x2'], $_POST['x3'], $_POST['x4'], $_POST['x5'], $_POST['x6']);

for($xi = 1; $xi <= $ximax; $xi++){
    echo "x$xi: <B>" . $x[$xi] . "</B><br />";
}
echo "<br />";



for($k = 1, $j = 1, $i = 1, $xi = 1, $xinext = 2; $i <= 3; $i++){
    $A[$i][$j][$k] = Binary::Disjunction($x[$xi], $x[$xinext]);
    echo "A[$i][$j][$k]: <B>" . $A[$i][$j][$k] . "</B><br />";
    $A[$i][$j+1][$k] = Binary::Conjunction($x[$xi], $x[$xinext]);
    echo "A[$i][$j+1][$k]: <B>" . $A[$i][$j+1][$k] . "</B><br />";
    $xi=$xi+2;
    $xinext=$xinext+2;
}
echo "<br />";

for($k = 2; $k <= 3; $k++){
    for($i = 1, $j = 1; $i <= 3; $i++){
        if($i == 1) {
            $A[$i][$j][$k] = Binary::Disjunction($A[$i][$j][$k-1], $A[$i+1][$j][$k-1]);
            echo "A[$i][$j][$k]: <B>" . $A[$i][$j][$k] . "</B><br />";
            $A[$i][$j+1][$k] = Binary::Conjunction($A[$i][$j][$k-1], $A[$i+1][$j][$k-1]);
            echo "A[$i][$j+1][$k]: <B>" . $A[$i][$j+1][$k] . "</B><br />";
        }
        if($i == 2) {
            $A[$i][$j][$k] = Binary::Disjunction($A[$i-1][$j+1][$k-1], $A[$i+1][$j][$k-1]);
            echo "A[$i][$j][$k]: <B>" . $A[$i][$j][$k] . "</B><br />";
            $A[$i][$j+1][$k] = Binary::Conjunction($A[$i-1][$j+1][$k-1], $A[$i+1][$j][$k-1]);
            echo "A[$i][$j+1][$k]: <B>" . $A[$i][$j+1][$k] . "</B><br />";
        }
        if($i == 3) {
            $A[$i][$j][$k] = Binary::Disjunction($A[$i-1][$j+1][$k-1], $A[$i][$j+1][$k-1]);
            echo "A[$i][$j][$k]: <B>" . $A[$i][$j][$k] . "</B><br />";
            $A[$i][$j+1][$k] = Binary::Conjunction($A[$i-1][$j+1][$k-1], $A[$i][$j+1][$k-1]);
            echo "A[$i][$j+1][$k]: <B>" . $A[$i][$j+1][$k] . "</B><br />";
        }
    }
}

for($i = 4, $j = 1, $k = 1; $k <= 3; $k++){
    if($k == 1){
        $A[$i][$j][$k] = Binary::Disjunction($A[$i-3][$j+1][$Amax-1], $A[$i-2][$j][$Amax-1]);
        echo "A[$i][$j][$k]: <B>" . $A[$i][$j][$k] . "</B><br />";
        $A[$i][$j+1][$k] = Binary::Conjunction($A[$i-3][$j+1][$Amax-1], $A[$i-2][$j][$Amax-1]);
        echo "A[$i][$j+1][$k]: <B>" . $A[$i][$j+1][$k] . "</B><br />";
    }
    elseif($k == 2){
        $A[$i][$j][$k] = Binary::Disjunction($A[$i-2][$j+1][$Amax-1], $A[$i-1][$j][$Amax-1]);
        echo "A[$i][$j][$k]: <B>" . $A[$i][$j][$k] . "</B><br />";
        $A[$i][$j+1][$k] = Binary::Conjunction($A[$i-2][$j+1][$Amax-1], $A[$i-1][$j][$Amax-1]);
        echo "A[$i][$j+1][$k]: <B>" . $A[$i][$j+1][$k] . "</B><br />";
    }
    elseif($k == 3){
        $A[$i][$j][$k] = Binary::Disjunction($A[$i][$j+1][$k-2], $A[$i][$j][$k-1]);
        echo "A[$i][$j][$k]: <B>" . $A[$i][$j][$k] . "</B><br />";
        $A[$i][$j+1][$k] = Binary::Conjunction($A[$i][$j+1][$k-2], $A[$i][$j][$k-1]);
        echo "A[$i][$j+1][$k]: <B>" . $A[$i][$j+1][$k] . "</B><br />";
    }
}
echo "<br />";

$A_end = array(1 => $A[1][1][3], $A[4][1][1], $A[4][1][3], $A[4][2][3], $A[4][2][2], $A[3][2][3]);

for($it = 1; $it <= $ximax; $it++){
    echo "A_$it: <B>" . $A_end[$it] . "</B><br />";
}
echo "<br />";

for($it = 1; $it <= $ximax; $it++){
    if($it == $ximax){
        $X[$it] = Binary::XOR(0, $A_end[$it]);
    }
    else $X[$it] = Binary::XOR($A_end[$it], $A_end[$it+1]);
}

for($it = 1; $it <= $ximax; $it++){
    echo "X$it: <B>" . $X[$it] . "</B><br />";
}
echo "<br />";

//y1
$y[1] = Binary::Disjunction($X[1], Binary::Disjunction($X[3], $X[5]));
//Y2
$y[2] = Binary::Disjunction($X[2], Binary::Disjunction($X[3], $X[6]));
//y3
$y[3] = Binary::Disjunction($X[4], Binary::Disjunction($X[5], $X[6]));

for($it = 1; $it <= $ximax/2; $it++){
    echo "y$it: <B>" . $y[$it] . "</B><br />";
}

Page::Bottom();
?>
