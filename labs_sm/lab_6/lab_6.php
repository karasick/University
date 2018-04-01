<?php
require ".\\topics.php";
Page::TopHead("Lab 6");

    class RL_STRUCT {

        public $a_sign;
        public $a_amount;
        public $rl_a = array();
        public $rl_a_s;

        public $b_sign;
        public $b_amount;
        public $rl_b = array();
        public $rl_b_s;

        public $rl_general = array();
        public $rl_sorted = array();
        public $rl_combined = array();

        public $rl_biggest_amount;
        public $rl_biggest_number ="A and B";
        public $general_sign = 0;
        public $general_amount;
        public $combined_amount;

        function CreationRL() {

            $this->a_sign = $_POST['as'];
            $this->a_amount = $_POST['aa'];

            $this->b_sign = $_POST['bs'];
            $this->b_amount = $_POST['ba'];

            if($this->a_amount >= $this->b_amount) {
                $this->rl_biggest_amount = $this->a_amount;
            } else {
                $this->rl_biggest_amount = $this->b_amount;
            }

            for($i = 0; $i < 6; $i++) {

                if($_POST['a' . $i] != NULL) {
                    $this->rl_a[$i] = $_POST['a' . $i];
                } else {
                    $this->rl_a[$i] = NULL;
                }  
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

            $this->rl_sorted = $this->rl_general;
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

            for($i = 1; $i < count($this->rl_combined); $i++) {

                if($this->rl_combined[$i] == $this->rl_combined[$i-1]) {
                    array_splice($this->rl_combined, $i-1, 2, $this->rl_combined[$i] + 1);
                    $i = 1;
                }
            }
            for($i = 0; $i < count($this->rl_combined) - 1; $i++) {

                if($this->rl_combined[$i] == $this->rl_combined[$i+1]) {
                    array_splice($this->rl_combined, $i, 2, $this->rl_combined[$i] + 1);
                    $i = 0;
                }
            }
            $this->combined_amount = count($this->rl_combined);
        }

        function ComparisonRLs() {

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

        function SubtractionRL() {

            $this->rl_a_s = $this->rl_a;
            $this->rl_b_s = $this->rl_b;

            
        }

        function DisplayResults() {

            echo "<h3>RLnumber A:</h3> <h4>";
            echo $this->a_sign . "." . $this->a_amount . " " . $this->rl_a[0];
            for($i = 1; $i < count($this->rl_a); $i++) {

                if($this->rl_a[$i] != NULL) {
                    echo "." . $this->rl_a[$i];
                }
            }
            echo "</h4>";

            echo "<h3>RLnumber B:</h3> <h4>";
            echo $this->b_sign . "." . $this->b_amount . " " . $this->rl_b[0];
            for($i = 1; $i < count($this->rl_b); $i++) {

                if($this->rl_b[$i] != NULL) {
                    echo "." . $this->rl_b[$i];
                }
            }
            echo "</h4>";

            echo "<h3>+ RLnumber:</h3> <h4>";
            echo $this->general_sign . "." . count($this->rl_combined) . " " . $this->rl_combined[0];
            for($i = 1; $i < count($this->rl_combined); $i++) {

                    echo "." . $this->rl_combined[$i];
            }
            echo "</h4>";
        }
    }

    $rl = new RL_STRUCT();

    $rl->CreationRL();
    $rl->CombinationRLs();
    $rl->CombineSimilars();
    $rl->ComparisonRLs();
    $rl->SubtractionRL();

    $rl->DisplayResults();

Page::Bottom();
?>
