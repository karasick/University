<?php
require ".\\topics.php";
Page::TopHead("Lab 6_2");

    class RL_STRUCT {

        public $a_sign;
        public $a_amount;
        public $rl_a = array();

        public $b_sign;
        public $b_amount;
        public $rl_b = array();

        public $rl_general = array();
        public $rl_subtracted = array();
        public $rl_subtract = array();

        public $rl_biggest_amount;
        public $rl_biggest_number = "A and B";
        public $general_sign = 0;
        public $subtracted_amount = 0;

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

            echo "<h3>The biggest RLnumber is: </h3>" . "<h4>";
            printf($this->rl_biggest_number) ;
            echo "</h4>";

            echo "<h3>Subtructed RLnumber:</h3> <h4>";
            echo $this->general_sign . "." . $this->subtracted_amount . " " . $this->rl_subtracted[0];
            for($i = 1; $i < $this->subtracted_amount; $i++) {

                if((string)$this->rl_subtracted[$i] != NULL) {
                    echo "." . $this->rl_subtracted[$i];
                }
            }
            echo "</h4>";
        }
    }

    $rl = new RL_STRUCT();

    $rl->CreationRL();
    $rl->ComparisonRLs();
    $rl->Subtraction();
    $rl->DisplayResults();

Page::Bottom();
?>
