<?php
class Page{

    function TopHead($head){
        echo "<html><head><link rel=\"icon\"
        type = \"image/png\"
        href = \"./code.png\" />
        <title>" . $head . "</title></head><body>";
    }
    public function Top(){
        echo "<html><body>";
    }
    public function Bottom(){
        echo "</body></html>";
    }
}
?>