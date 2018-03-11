<?php
require ".\helpers.php";
Page::TopHead("Lab 4");

    class RL_CODE {

        public $before_dot;
        public $after_dot;

        public $before_dot_bin;
        public $after_dot_bin;

        public $before_dot_rl;
        public $after_dot_rl;

        public $ifnegative;
        public $amount;

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

            if(count($parts) == 1){
                $this->before_dot = $parts[0];
                $this->after_dot = "";
            } else{

                $this->before_dot = $parts[0];
                $this->after_dot = $parts[1];
            }
        }

        function MoveToBinary() {

            $this->before_dot_bin = decbin($this->before_dot);

            if($this->after_dot != ""){
                
                $after_dot_dec = $this->after_dot;
                $length = strlen($after_dot_dec);

                for($i = 1; $i <= intval($_POST['ad']); $i++){
    
                    $after_dot_dec = intval($after_dot_dec) * 2;
    
                    if(($length < strlen($after_dot_dec)) /*|| (intval($after_dot_dec) == (10 ** $length))*/ ){
    
                        $this->after_dot_bin .= '1'; 
                        $after_dot_dec -= (10 ** $length);

                    } elseif((intval($after_dot_dec) != (10 ** $length)) && ($length == strlen($after_dot_dec)) && ($length != 0)) {
    
                        $this->after_dot_bin .= '0'; 
                    }
                    $length = strlen($after_dot_dec);
                }
            }
        }

        function MoveToRL() {

            $this->before_dot_rl = $this->before_dot_bin;

            $length_bd_rl = intval(strlen($this->before_dot_rl));

            for($itt = 0; $itt < strlen($this->before_dot_rl); $itt++){

                $itt_rl = $length_bd_rl - $itt - 1;

                if($this->before_dot_rl[$itt] == '1'){

                    $this->before_dot_rl[$itt] = $itt_rl;
                    $this->amount++;
                } else {

                    $this->before_dot_rl[$itt] = "n";
                }
            }

            if($this->after_dot != ""){

                $this->after_dot_rl = $this->after_dot_bin;

                $length_ad_rl = intval(strlen($this->after_dot_rl));

                for($itt = 0; $itt < strlen($this->after_dot_rl); $itt++){

                    $itt_rl = $itt + 1;

                    if($this->after_dot_rl[$itt] == '1'){

                        $this->after_dot_rl[$itt] = $itt_rl;
                        $this->amount++;
                    } else {

                        $this->after_dot_rl[$itt] = 'n';
                    }
                }
            }
        }

        function DisplayResults() {

            echo "<h3>Your number: </h3><h4>";
                if($this->ifnegative == 1){
                    echo "-";
                }
                if($this->after_dot == "") {

                    echo $this->before_dot . "<br/>";
                } else {

                    echo $this->before_dot . "." . $this->after_dot . "</h4>";
                }

            echo "<h3>Your number in binary system: </h3><h4>" . $this->ifnegative . " ";
                echo $this->before_dot_bin;
                if($this->after_dot != ""){

                    echo "." . $this->after_dot_bin;
                }
                echo "</h4>";

            echo "<h3>Your number in RL view: </h3><h4>";
                echo $this->ifnegative . "." . $this->amount . " ";
                for($itt = 0, $k = 0; $itt < strlen($this->before_dot_rl); $itt++){
                        
                    if($this->before_dot_rl[$itt] != "n"){

                        if($k == 0){

                            echo  $this->before_dot_rl[$itt];
                            $k++;
                        } else {
            
                            echo  "." . $this->before_dot_rl[$itt];
                        }
                    }
                }
                if($this->after_dot != "") {

                    for($itt = 0, $k = 0; $itt < strlen($this->after_dot_rl); $itt++){
                            
                        if($this->after_dot_rl[$itt] != "n"){

                            echo  "." . -$this->after_dot_rl[$itt];
                        }
                    }
                }
            echo "</h4>";
        }
    }

    $n = new RL_CODE($_POST['n']);

    $n->MoveToBinary();
    $n->MoveToRL();
    $n->DisplayResults();

    Page::Bottom();
?>