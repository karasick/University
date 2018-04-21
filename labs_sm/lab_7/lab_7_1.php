<?php
require ".\\topics.php";
Page::TopHead("Lab 7_1");

    class RL_STRUCT {

        public $a_sign;
        public $a_amount;
        public $rl_a = array();

        public $b_sign;
        public $b_amount;
        public $rl_b = array();

        public $rl_general = array();
        public $rl_multiplicated = array();
        public $rl_sorted = array();
        public $rl_combined = array();

        public $rl_biggest_amount;
        public $rl_biggest_number = "A and B";
        public $general_sign = 0;
        public $general_amount = 0;

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

        function SortRL() {

            $this->rl_sorted = $this->rl_multiplicated;
            rsort($this->rl_sorted);
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

            echo "<h3>Multiplicated RLnumber:</h3> <h4>";
            echo $this->general_sign . "." . $this->general_amount . " " . $this->rl_multiplicated[0];
            for($i = 1; $i < count($this->rl_multiplicated); $i++) {

                    echo "." . $this->rl_multiplicated[$i];
            }
            echo "</h4>";


            echo "<h3>Sorted RLnumber:</h3> <h4>";
            echo $this->general_sign . "." . $this->general_amount . " " . $this->rl_sorted[0];
            for($i = 1; $i < count($this->rl_sorted); $i++) {

                    echo "." . $this->rl_sorted[$i];
            }
            echo "</h4>";

            echo "<h3>Сombined RLnumber:</h3> <h4>";
            echo $this->general_sign . "." . $this->combined_amount . " " . $this->rl_combined[0];
            for($i = 1; $i < count($this->rl_combined); $i++) {

                    echo "." . $this->rl_combined[$i];
            }
            echo "</h4>";
        }
    }

    $rl = new RL_STRUCT();

    $rl->CreationRL();
    $rl->ComparisonRLs();
    $rl->MultiplicationRL();
    $rl->SortRL();
    $rl->CombineSimilars();
    $rl->DisplayResults();

Page::Bottom();
?>
