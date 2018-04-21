<?php
require ".\\topics.php";
Page::TopHead("" . $_POST['task'] . "");

    class RL_STRUCT {

        public $a_sign;
        public $a_amount;
        public $rl_a = array();
        public $b_sign;
        public $b_amount;
        public $rl_b = array();

        public $rl_general = array();
        public $rl_sorted = array();
        public $rl_combined = array();
        public $rl_subtracted = array();
        public $rl_subtract = array();
        public $rl_multiplicated = array();
        public $rl_divided = array();
        public $rl_numerator = array();
        public $rl_denominator = array();

        public $rl_biggest_amount;
        public $biggest_rl = "A and B";
        public $general_sign = 0;
        public $general_amount = 0;
        public $combined_amount = 0;
        public $subtracted_amount = 0;
        public $divided_amount = 0;

        function CreationRL() {

            $this->a_sign = $_POST['as'];
            $this->a_amount = $_POST['aa'];

            $this->b_sign = $_POST['bs'];
            $this->b_amount = $_POST['ba'];

            for($i = 0; $i < $this->a_amount; $i++) {

                if($_POST['a' . $i] != NULL) {
                    $this->rl_a[$i] = $_POST['a' . $i];
                } else {
                    $this->rl_a[$i] = NULL;
                }
            }

            for($i = 0; $i < $this->b_amount; $i++){

                if($_POST['b' . $i] != NULL) {
                    $this->rl_b[$i] = $_POST['b' . $i];
                } else {
                    $this->rl_b[$i] = NULL;
                }
            }
        }

        function CombinationRLs() {

            $this->rl_general = $this->rl_a;

            for($i = 0; $i < count($this->rl_b); $i++) {

                $this->rl_general[count($this->rl_a) + $i] = $this->rl_b[$i];
            }

            if((intval($this->a_sign) == 1) || (intval($this->b_sign) == 1)) {
                $this->general_sign = 1;
            }
            $this->general_amount = intval($this->a_amount) + intval($this->b_amount);
        }

        function SortRLs() {

            if(strval($_POST['task']) == "Multiplication") {
                $this->rl_sorted = $this->rl_multiplicated;
            } else {
                $this->rl_sorted = $this->rl_general;
            }
            sort($this->rl_sorted);

            for($i = 0; $i < count($this->rl_sorted); $i++) {

                if($this->rl_sorted[$i] == NULL) {
                    unset($this->rl_sorted[$i]);
                }
            }

            $this->rl_sorted = array_reverse($this->rl_sorted);

            for($i = 0; $i < count($this->rl_sorted); $i++) {

                $this->rl_sorted[$i] = intval($this->rl_sorted[$i]);
            }
        }

        function CombineSimilars() {

            $this->rl_combined = $this->rl_sorted;

            for($i = 1; $i <  count($this->rl_combined); $i++) {

                if($this->rl_combined[$i] == $this->rl_combined[$i-1]) {
                    array_splice($this->rl_combined, $i-1, 2, $this->rl_combined[$i] + 1);
                    $i = 1;
                }
            }
            for($i = 0; $i <  count($this->rl_combined) - 1; $i++) {

                if($this->rl_combined[$i] == $this->rl_combined[$i+1]) {
                    array_splice($this->rl_combined, $i, 2, $this->rl_combined[$i] + 1);
                    $i = 0;
                }
            }
            $this->combined_amount = count($this->rl_combined);
        }

        function ComparisonRLs() {

            if($this->a_amount >= $this->b_amount) {
                $this->rl_biggest_amount = $this->a_amount;
            } else {
                $this->rl_biggest_amount = $this->b_amount;
            }

            for($i = 0; $i < $this->rl_biggest_amount; $i++) {
                if(intval($this->rl_a[$i]) > intval($this->rl_b[$i])) {
                    $this->rl_biggest_number = "A";
                    break;
                } else if(intval($this->rl_a[$i]) < intval($this->rl_b[$i])) {
                    $this->rl_biggest_number = "B";
                    break;
                }
            }
        }

        function Subtraction() {

            if($this->rl_biggest_number == "A and B") {
                $this->rl_subtracted = 0;
                $this->subtracted_amount = 0;
                return;
            } elseif($this->rl_biggest_number == "A") {
                $this->rl_subtract = $this->rl_b;
                $this->rl_subtracted = $this->rl_a;
            } elseif($this->rl_biggest_number == "B") {
                $this->rl_subtract = $this->rl_a;
                $this->rl_subtracted = $this->rl_b;
            }
            
            for($i = $this->a_amount - 1; $i >= 0; $i--) {
                for($j = $this->b_amount - 1; $j >= 0; $j--) {

                    if($this->rl_subtract == NULL) {
                        break 2;
                    }

                    if(intval($this->rl_subtracted[$i]) == intval($this->rl_subtract[$j])) {
                        array_splice($this->rl_subtracted, $i, 1);
                        array_splice($this->rl_subtract, $j, 1);
                        $i--;
                    } elseif(intval($this->rl_subtracted[$i]) > intval($this->rl_subtract[$j])) {
                        $repls = array_fill(0,2,$this->rl_subtracted[$i] - 1);
                        array_splice($this->rl_subtracted, $i, 1, $repls);
                        $j++;
                        $i++;
                    } elseif(intval($this->rl_subtracted[$i]) < intval($this->rl_subtract[$j])) {
                        $i--;
                        $j++;
                    }
                }
            }
            $this->subtracted_amount = count($this->rl_subtracted);
        }

        function MultiplicationRL() {

            if($this->a_sign >= $this->b_sign) {
                $this->general_sign = $this->a_sign;
            } else {
                $this->general_sign = $this->b_sign;
            }

            for($i = 0; $i < $this->a_amount; $i++) {
                for($j = 0; $j < $this->b_amount; $j++, $this->general_amount++) {

                    $this->rl_multiplicated[$this->general_amount] = intval($this->rl_a[$i]) + intval($this->rl_b[$j]);
                }
            }
        }

        function DivisionRL() {

            if($this->a_sign >= $this->b_sign) {
                $this->general_sign = $this->a_sign;
            } else {
                $this->general_sign = $this->b_sign;
            }

            $this->rl_numerator = $this->rl_a;
            $this->rl_denominator = $this->rl_b;
            $rl_intermediate_denominator = $this->rl_denominator;
            $coefficient = 0;
            $precision = 0;

            for($itt = 0; $itt < 10; $itt++) {

                if($this->rl_numerator == NULL) {
                    break;
                } elseif($this->rl_numerator == $rl_intermediate_denominator) {
                    array_push($this->rl_divided, 0);
                    break;
                } elseif($this->rl_numerator[0] < 0) {
                    $precision++;
                }

                if($precision == 10) {
                    break;
                }

                code1: {
                    $divider = intval($this->rl_numerator[0]) - (intval($this->rl_denominator[0]) + $coefficient);
                    for($i = 0; $i < count($rl_intermediate_denominator); $i++) {

                        $rl_intermediate_denominator[$i] += $divider;
                    }

                    if(count($this->rl_numerator) >=  count($rl_intermediate_denominator)) {
                        $this->rl_biggest_amount = count($this->rl_numerator);
                    } else {
                        $this->rl_biggest_amount =  count($rl_intermediate_denominator);
                    }

                    for($i = 0; $i < count($this->rl_numerator); $i++) {
                        for($j = $i; $j < count($rl_intermediate_denominator); $j++) {
                        
                            if(intval($this->rl_numerator[$i]) == intval($rl_intermediate_denominator[$j])) {
                                $this->rl_biggest_number = "D";
                                if(($i == (count($this->rl_numerator) - 1)) && ($j == (count($rl_intermediate_denominator) - 1))) {
                                    $this->rl_biggest_number = "N and D";
                                }
                                break;
                            } elseif(intval($this->rl_numerator[$i]) > intval($rl_intermediate_denominator[$j])) {
                                $this->rl_biggest_number = "N";
                                break 2;
                            } elseif(intval($this->rl_numerator[$i]) < intval($rl_intermediate_denominator[$j])) {
                                $this->rl_biggest_number = "D";
                                break 2;
                            }
                        }
                    }

                    if($this->rl_biggest_number == "N") {
                        array_push($this->rl_divided, $divider);
                        $this->rl_subtracted = $this->rl_numerator;
                        $this->rl_subtract = $rl_intermediate_denominator;
                        $coefficient = 0;
                    } elseif($this->rl_biggest_number == "D") {
                        $coefficient++;
                        $rl_intermediate_denominator = $this->rl_denominator;
                        goto code1;
                    } elseif($this->rl_biggest_number == "N and D") {
                        array_push($this->rl_divided, $divider);
                        $coefficient = 0;
                        $rl_intermediate_denominator = $this->rl_denominator;
                        continue;
                    }
                }

                for($i = count($this->rl_subtracted) - 1; $i >= 0; $i--) {
                    for($j = count($this->rl_subtract) - 1; $j >= 0; $j--) {

                        if($this->rl_subtract == NULL) {
                            break 2;
                        }
    
                        if(intval($this->rl_subtracted[$i]) == intval($this->rl_subtract[$j])) {
                            array_splice($this->rl_subtracted, $i, 1);
                            array_splice($this->rl_subtract, $j, 1);
                            $i--;
                        } elseif(intval($this->rl_subtracted[$i]) > intval($this->rl_subtract[$j])) {
                            $repls = array_fill(0,2,$this->rl_subtracted[$i] - 1);
                            array_splice($this->rl_subtracted, $i, 1, $repls);
                            $j++;
                            $i++;
                        } elseif(intval($this->rl_subtracted[$i]) < intval($this->rl_subtract[$j])) {
                            $i--;
                            $j++;
                        }
                    }
                }

                $this->rl_numerator = $this->rl_subtracted;
                $rl_intermediate_denominator = $this->rl_denominator;
            }
            $this->divided_amount = count($this->rl_divided);
        }

        function DisplayRLs() {

            echo "<h3>RL code A:</h3> <h4>";
            echo $this->a_sign . "." . $this->a_amount . " " . $this->rl_a[0];
            for($i = 1; $i < count($this->rl_a); $i++) {

                if($this->rl_a[$i] != NULL) {
                    echo "." . $this->rl_a[$i];
                }
            }
            echo "</h4>";

            echo "<h3>RL code B:</h3> <h4>";
            echo $this->b_sign . "." . $this->b_amount . " " . $this->rl_b[0];
            for($i = 1; $i < count($this->rl_b); $i++) {

                if($this->rl_b[$i] != NULL) {
                    echo "." . $this->rl_b[$i];
                }
            }
            echo "</h4>";
        }

        function DisplayAdditionResults() {

            echo "<h3>General RL code:</h3> <h4>";
            echo $this->general_sign . "." . $this->general_amount . " " . $this->rl_general[0];
            for($i = 1; $i < count($this->rl_general); $i++) {

                if($this->rl_general[$i] != NULL) {
                    echo "." . $this->rl_general[$i];
                }
            }
            echo "</h4>";

            echo "<h3>Sorted RL code:</h3> <h4>";
            echo $this->general_sign . "." . $this->general_amount . " " . $this->rl_sorted[0];
            for($i = 1; $i < count($this->rl_sorted); $i++) {

                    echo "." . $this->rl_sorted[$i];
            }
            echo "</h4>";

            echo "<h3>Сombined RL code:</h3> <h4>";
            echo $this->general_sign . "." . $this->combined_amount . " " . $this->rl_combined[0];
            for($i = 1; $i < count($this->rl_combined); $i++) {

                    echo "." . $this->rl_combined[$i];
            }
            echo "</h4>";
        }

        function DisplaySubtractionResults() {

            echo "<h3>The biggest RL code is: </h3>" . "<h4>";
            printf($this->rl_biggest_number) ;
            echo "</h4>";

            echo "<h3>Subtructed RL code:</h3> <h4>";
            echo $this->general_sign . "." . $this->subtracted_amount . " " . $this->rl_subtracted[0];
            for($i = 1; $i < $this->subtracted_amount; $i++) {

                if((string)$this->rl_subtracted[$i] != NULL) {
                    echo "." . $this->rl_subtracted[$i];
                }
            }
            echo "</h4>";
        }

        function DisplayMultiplicationResults() {

            echo "<h3>Multiplicated RL code:</h3> <h4>";
            echo $this->general_sign . "." . $this->general_amount . " " . $this->rl_multiplicated[0];
            for($i = 1; $i < count($this->rl_multiplicated); $i++) {

                    echo "." . $this->rl_multiplicated[$i];
            }
            echo "</h4>";


            echo "<h3>Sorted RL code:</h3> <h4>";
            echo $this->general_sign . "." . $this->general_amount . " " . $this->rl_sorted[0];
            for($i = 1; $i < count($this->rl_sorted); $i++) {

                    echo "." . $this->rl_sorted[$i];
            }
            echo "</h4>";

            echo "<h3>Сombined RL code:</h3> <h4>";
            echo $this->general_sign . "." . $this->combined_amount . " " . $this->rl_combined[0];
            for($i = 1; $i < count($this->rl_combined); $i++) {

                    echo "." . $this->rl_combined[$i];
            }
            echo "</h4>";
        }

        function DisplayDivisionResults() {

            echo "<h3>Divided RL code:</h3> <h4>";
            echo $this->general_sign . "." . $this->divided_amount . " " . $this->rl_divided[0];
            for($i = 1; $i < count($this->rl_divided); $i++) {

                    echo "." . $this->rl_divided[$i];
            }
            echo "</h4>";
        }
    }

    if ($_POST) {
        $rl = new RL_STRUCT();
        $rl->CreationRL();

        if(strval($_POST['task']) == "Addition") {
            $rl->CombinationRLs();
            $rl->SortRLs();
            $rl->CombineSimilars();
                $rl->DisplayRLs();
                $rl->DisplayAdditionResults();
        } elseif(strval($_POST['task']) == "Subtraction") {
            $rl->ComparisonRLs();
            $rl->Subtraction();
                $rl->DisplayRLs();
                $rl->DisplaySubtractionResults();
        } elseif(strval($_POST['task']) == "Multiplication") {
            $rl->MultiplicationRL();
            $rl->SortRLs();
            $rl->CombineSimilars();
                $rl->DisplayRLs();
                $rl->DisplayMultiplicationResults();
        } elseif(strval($_POST['task']) == "Division") {
            $rl->DivisionRL();
                $rl->DisplayRLs();
                $rl->DisplayDivisionResults();
        } else {
            echo "<h2>You choose no tasks! Go back.</h2>";
        }
    } else {
        echo "<h2>GET BACK TO WORK!</h2>";
    }

Page::Bottom();
?>
