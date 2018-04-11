<?php
require ".\\topics.php";
Page::TopHead("Lab 6_1");

    class RL_CODE {

        public $before_dot;
        public $after_dot;

        public $before_dot_bin;
        public $after_dot_bin;

        public $bd_array = array();
        public $ad_array = array();

        public $before_dot_rl;
        public $after_dot_rl;

        public $ifnegative;
        public $amount;

        public $length_bd_rl;
        public $length_ad_rl;

        function __construct($number) {
            
            $length = strlen($number);
            $preparts = explode("-", $number);

            if($length > strlen($preparts[0])) {
                $this->ifnegative = 1;
                $parts = explode(".", $preparts[1]);
            } else {
                $this->ifnegative = 0;
                $parts = explode(".", $preparts[0]);
            }

            if(count($parts) == 1) {
                $this->before_dot = $parts[0];
                $this->after_dot = "";
            } else {
                $this->before_dot = $parts[0];
                $this->after_dot = $parts[1];
            }
        }

        function MoveToBinary() {

            $this->before_dot_bin = decbin($this->before_dot);

            if($this->after_dot != "") {
                $after_dot_dec = $this->after_dot;
                $length = strlen($after_dot_dec);

                for($i = 1; $i <= intval($_POST['ad']); $i++) {
    
                    $after_dot_dec = intval($after_dot_dec) * 2;
    
                    if(($length < strlen($after_dot_dec)) /*|| (intval($after_dot_dec) == (10 ** $length))*/ ) {
                        $this->after_dot_bin .= "1"; 
                        $after_dot_dec -= (10 ** $length);

                    } elseif((intval($after_dot_dec) != (10 ** $length)) && ($length == strlen($after_dot_dec)) && ($length != 0)) {
                        $this->after_dot_bin .= "0"; 
                    }
                    $length = strlen($after_dot_dec);
                }
            }
        }

        function MoveToArray() {

            $this->length_bd_rl = strlen($this->before_dot_bin);
            $this->length_ad_rl = strlen($this->after_dot_bin);

            for($i = 0; $i < $this->length_bd_rl; $i++) {

                $this->bd_array[$i] = $this->before_dot_bin[$i];
            }

            for($i = 0; $i < $this->length_ad_rl; $i++) {

                $this->ad_array[$i] = $this->after_dot_bin[$i];
            }
        }

        function MoveToRL() {

            $this->before_dot_rl = $this->bd_array;

            for($itt = 0; $itt < $this->length_bd_rl; $itt++) {

                $itt_rl = $this->length_bd_rl - $itt - 1;

                if($this->before_dot_rl[$itt] == "1") {
                    $this->before_dot_rl[$itt] = $itt_rl;
                    $this->amount++;
                } else {
                    $this->before_dot_rl[$itt] = "n";
                }
            }

            if($this->after_dot != "") {
                $this->after_dot_rl = $this->ad_array;

                for($itt = 0; $itt < $this->length_ad_rl; $itt++) {

                    $itt_rl = $itt + 1;

                    if($this->after_dot_rl[$itt] == "1") {
                        $this->after_dot_rl[$itt] = substr_replace($this->after_dot_rl[$itt], $itt_rl, 0);
                        $this->amount++;
                    } else {
                        $this->after_dot_rl[$itt] = "n";
                    }
                }
            }
        }

        function DisplayResults() {

            echo "<h3>Your 1 number: </h3><h4>" . $_POST['n1'] . "</h4>";

            echo "<h3>Your 2 number: </h3><h4>" . $_POST['n2'] . "</h4>";

            echo "<h3>Addition of this numbers in RL view: </h3><h4>";
                echo $this->ifnegative . "." . $this->amount . " ";
                for($itt = 0, $k = 0; $itt < $this->length_bd_rl; $itt++){
                        
                    if((string) $this->before_dot_rl[$itt] != "n") {
                        if($k == 0) {
                            echo $this->before_dot_rl[$itt];
                            $k++;
                        } else {
                            echo  "." . $this->before_dot_rl[$itt];
                        }
                    }
                }
                if($this->after_dot != "") {
                    for($itt = 0, $k = 0; $itt < $this->length_ad_rl; $itt++){
                            
                        if($this->after_dot_rl[$itt] != "n") {
                            echo  ".-";
                            print $this->after_dot_rl[$itt];
                        }
                    }
                }
            echo "</h4><br/>";
        }
    }

    $n = new RL_CODE(strval(round(($_POST['n1'] + $_POST['n2']), 10)));

    $n->MoveToBinary();
    $n->MoveToArray();
    $n->MoveToRL();
    $n->DisplayResults();

Page::Bottom();
?>