<?php
require "C:\\xampp\htdocs\php\helpers.php";
Page::TopHead("Lab 3");

class analyzed_data {

  public $data;
  public $rang;

  function __construct($filePath) {
    $this->data = file_get_contents($filePath);
  }

  function getContents () {
    return $this->data; 
  }

  function  extractRang () {

    $rows = explode("\n", $this->data);
    foreach ($rows as &$row) {
      $row = explode(" ", substr($row, 5, -6));
    }

    $c = (count($rows) - 1);
    $this->rang = [];
    for ($i = 0; $i <= $c; $i++) {

      if (((count($rows[$i])-1) == 1) && (intval(substr($rows[$i][0], 0, -3)) == 1)) {
        
        $this->rang[(intval(substr($rows[$i][1], 1)))] = 2;
      }

      else if (((count($rows[$i])-1) == 1) && (intval(substr($rows[$i][0], 0, -3)) == 5)) {

        $this->rang[(intval(substr($rows[$i][1], 1)))] = $this->rang[(intval(substr($rows[$i][0], 1)))] + 1;
      }

      else if ((count($rows[$i])-1) != 1) {

        for($it = 0; $it < intval(count($rows[$i])-1); $it++){

          $ar_rangs[$it] = intval($this->rang[(intval(substr($rows[$i][$it], 1)))]);
        }

        $this->rang[(intval(substr($rows[$i][count($rows[$i])-1], 1)))] = max($ar_rangs) + 1;
        unset($ar_rangs);
      }
    }

    return $this->rang;
  }
  function displayRang(): void {

    echo "<table style = \"width: 1000px;
            border:1px #000;
            background: #828282;
            border-style: solid;
            margin-left: auto;
            margin-right: auto;
            text-align: center;
            vertical-align: middle\">

            <colgroup span = \"2\"></colgroup>
            <tr>
              <th style = \"width: 1000px;
              height: 50px;
              border:1px #000;
              background: #fff;
              border-style: solid;
              text-align: center;
              vertical-align: middle\"> Number of operation </th>

              <th style = \"width: 1000px;
              height: 50px;
              border:1px #000;
              background: #fff;
              border-style: solid;
              text-align: center;
              vertical-align: middle\"> Rang of operation </th>
            </tr>";

    foreach ($this->rang as $key => $value) {

      echo "<tr>
              <td style = \"width: 1000px;
                height: 40px;
                border:1px #000;
                background: #fff;
                border-style: solid;
                text-align: center;
                vertical-align: middle\"> $key </td>

                <td style = \"width: 1000px;
                height: 40px;
                border:1px #000;
                background: #fff;
                border-style: solid;
                text-align: center;
                vertical-align: middle\"> $value </td>
            </tr>";
    }

    echo "</table>";
  }
}

$test = new analyzed_data("./lab_3_data.txt");
$test->extractRang();
$test->displayRang();

Page::Bottom();
?>
