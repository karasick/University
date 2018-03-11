<?php
require "C:\\xampp\htdocs\php\GitHub\University\helpers.php";
require "binary.php";
Page::TopHead("Lab 1");

echo "x1: <B>" . $_POST['x1'] . "</B><br />";
echo "x2: <B>" . $_POST['x2'] . "</B><br />";
echo "x3: <B>" . $_POST['x3'] . "</B><br />";
echo "x4: <B>" . $_POST['x4'] . "</B><br />";
echo "x5: <B>" . $_POST['x5'] . "</B><br />";
echo "x6: <B>" . $_POST['x6'] . "</B><br />";
echo "</br>";

//A1
$A2 = Binary::Disjunction($_POST['x1'], $_POST['x2']);
echo "A2: <B>" . $A2 . "</B><br />";
$A2_2 = Binary::Conjunction($_POST['x1'], $_POST['x2']);
echo "A2_2: <B>" . $A2_2 . "</B><br />";
//A2
$A3 = Binary::Disjunction($A2, $_POST['x3']);
echo "A3: <B>" . $A3 . "</B><br />";
$A2 = Binary::Conjunction($A2, $_POST['x3']);
echo "A2: <B>" . $A2 . "</B><br />";
//A2_2
$A3_2 = Binary::Disjunction($A2_2, $A2);
echo "A3_2: <B>" . $A3_2 . "</B><br />";
$A3_3 = Binary::Conjunction($A2_2, $A2);
echo "A3_3: <B>" . $A3_3 . "</B><br />";

//A3
$A4 = Binary::Disjunction($A3, $_POST['x4']);
echo "A4: <B>" . $A4 . "</B><br />";
$A3 = Binary::Conjunction($A3, $_POST['x4']);
echo "A3: <B>" . $A3 . "</B><br />";
//A3_2
$A4_2 = Binary::Disjunction($A3_2, $A3);
echo "A4_2: <B>" . $A4_2 . "</B><br />";
$A3_2 = Binary::Conjunction($A3_2, $A3);
echo "A3_2: <B>" . $A3_2 . "</B><br />";
//A3_3
$A4_3 = Binary::Disjunction($A3_3, $A3_2);
echo "A4_3: <B>" . $A4_3 . "</B><br />";
$A4_4 = Binary::Conjunction($A3_3, $A3_2);
echo "A4_4: <B>" . $A4_4 . "</B><br />";

//A4
$A5 = Binary::Disjunction($A4, $_POST['x5']);
echo "A5: <B>" . $A5 . "</B><br />";
$A4 = Binary::Conjunction($A4, $_POST['x5']);
echo "A4: <B>" . $A4 . "</B><br />";
//A4_2
$A5_2 = Binary::Disjunction($A4_2, $A4);
echo "A5_2: <B>" . $A5_2 . "</B><br />";
$A4_2 = Binary::Conjunction($A4_2, $A4);
echo "A4_2: <B>" . $A4_2 . "</B><br />";
//A4_3
$A5_3 = Binary::Disjunction($A4_3, $A4_2);
echo "A5_3: <B>" . $A5_3 . "</B><br />";
$A4_3 = Binary::Conjunction($A4_3, $A4_2);
echo "A4_3: <B>" . $A4_3 . "</B><br />";
//A4_4
$A5_4 = Binary::Disjunction($A4_4, $A4_3);
echo "A5_4: <B>" . $A5_4 . "</B><br />";
$A5_5 = Binary::Conjunction($A4_4, $A4_3);
echo "A5_5: <B>" . $A5_5 . "</B><br />";

//A5
$A_1 = Binary::Disjunction($A5, $_POST['x6']);
echo "A_1: <B>" . $A_1 . "</B><br />";
$A5 = Binary::Conjunction($A5, $_POST['x6']);
echo "A5: <B>" . $A5 . "</B><br />";
//A5_2
$A_2 = Binary::Disjunction($A5_2, $A5);
echo "A_2: <B>" . $A_2 . "</B><br />";
$A5_2 = Binary::Conjunction($A5_2, $A5);
echo "A5_2: <B>" . $A5_2 . "</B><br />";
//A5_3
$A_3 = Binary::Disjunction($A5_3, $A5_2);
echo "A_3: <B>" . $A_3 . "</B><br />";
$A5_3 = Binary::Conjunction($A5_3, $A5_2);
echo "A5_3: <B>" . $A5_3 . "</B><br />";
//A5_4
$A_4 = Binary::Disjunction($A5_4, $A5_3);
echo "A_4: <B>" . $A_4 . "</B><br />";
$A5_4 = Binary::Conjunction($A5_4, $A5_3);
echo "A5_4: <B>" . $A5_4 . "</B><br />";
//A5_5
$A_5 = Binary::Disjunction($A5_5, $A5_4);
echo "A_5: <B>" . $A_5 . "</B><br />";
$A_6 = Binary::Conjunction($A5_5, $A5_4);
echo "A_6: <B>" . $A_6 . "</B><br />";
echo "</br>";

echo "A_1: <B>" . $A_1 . "</B><br />";
echo "A_2: <B>" . $A_2 . "</B><br />";
echo "A_3: <B>" . $A_3 . "</B><br />";
echo "A_4: <B>" . $A_4 . "</B><br />";
echo "A_5: <B>" . $A_5 . "</B><br />";
echo "A_6: <B>" . $A_6 . "</B><br />";
echo "</br>";

//X
$X = Binary::XOR($A_1, $A_2);
//X2
$X2 = Binary::XOR($A_2, $A_3);
//X3
$X3 = Binary::XOR($A_3, $A_4);
//X4
$X4 = Binary::XOR($A_4, $A_5);
//X5
$X5 = Binary::XOR($A_5, $A_6);
//X6
$X6 = Binary::XOR($A_6, 0);

echo "X1: <B>" . $X . "</B><br />";
echo "X2: <B>" . $X2 . "</B><br />";
echo "X3: <B>" . $X3 . "</B><br />";
echo "X4: <B>" . $X4 . "</B><br />";
echo "X5: <B>" . $X5 . "</B><br />";
echo "X6: <B>" . $X6 . "</B><br />";
echo "</br>";

//y1
$y1 = Binary::Disjunction($X, Binary::Disjunction($X3, $X5));
//y2
$y2 = Binary::Disjunction($X2, Binary::Disjunction($X3, $X6));
//y3
$y3 = Binary::Disjunction($X4, Binary::Disjunction($X5, $X6));

echo "y1: <B>" . $y1 . "</B><br />";
echo "y2: <B>" . $y2 . "</B><br />";
echo "y3: <B>" . $y3 . "</B><br />";

Page::Bottom();
?>
