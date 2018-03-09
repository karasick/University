<?php
class Binary{

    function Disjunction($a, $b){
        return ($a or $b) ? 1 : 0;
    }
    public function Conjunction($a, $b){
        return ($a and $b) ? 1 : 0;
    }
    public function XOR($a, $b){
        return ($a xor $b) ? 1 : 0;
    }
}
?>
