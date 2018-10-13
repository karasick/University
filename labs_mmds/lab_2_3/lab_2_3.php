<html>
  <head>
    <title>
      Lab 2_3
    </title>
  </head>
  <body>
    <?php

      $S = 201.45; // m^2 - square
      $l = 37.55; // m - wingspan
      $G = 80000; // kg - weight
      $I_x = 250000; // kg * m * s^2 - cross moment of inertia
      $I_y = 900000; // kg * m * s^2 - roadway moment of inertia

      $V = 97.2; // m per s - speed
      $H = 500; // m - height
      $pr = 0.1190; // (kg * s^2) per m^2 - pressure
      $An = 338.36; // m per s - sound velocity

      $g = 9.81; // m per s^2 - gravitational acceleration
      $m = $G / $g; // N - Weight

      $C_y0 = -0.255;
      $C_y_A = 5.78;
      $C_y_A = 5.78;

      $C_z_B = -0.8136;
      $C_z_Dn = -0.16;

      $m_x_Dn = -0.02;
      $m_x_vWy = -0.151;
      $m_x_vWx = -0.56;
      $m_x_B = -0.1146;
      $m_x_De = -0.07;

      $m_y_Dn = -0.071;
      $m_y_vWy = -0.141;
      $m_y_vWx = -0.026;
      $m_y_B = -0.1518;
      $m_y_De = 0;

      $k_Wx = 1.5;
      $k_Wy = 2.5;
      $T_Wx = 1.6;
      $T_Wy = 2.5;

      $Ga_B = $m_y_B - (($C_z_B * $pr * $S * $l) / (4 * $m)) * $m_y_vWy;
      $W_x_De = 0;
      $Xx = (($m_x_B * $I_y) / ($m_y_B * $I_x)) * (1 / sqrt(1 - pow(($m_x_vWx / $I_x), 2) * $I_y * $S * pow($l, 2) * ($pr / (4 * $m_y_B))));
      
      $C_ybal = (2 * $G) / ($S * $pr * pow($V, 2));
      $A_bal = 57.3 * (($C_ybal - $C_y0) / $C_y_A);

      $a[1] = -($m_y_vWy / $I_y) * $S * pow($l, 2) * (($pr * pow($V, 1)) / 4);
      $a[2] = -($m_y_B / $I_y) * $S * pow($l, 1) * (($pr * pow($V, 2)) / 2);
      $a[3] = -($m_y_Dn / $I_y) * $S * pow($l, 1) * (($pr * pow($V, 2)) / 2);
      $a[4] = -($C_z_B / $m) * $S * (($pr * pow($V, 1)) / 2);
      $a[5] = -($m_x_Dn / $I_x) * $S * pow($l, 1) * (($pr * pow($V, 2)) / 2);
      $a[6] = -($m_x_vWy / $I_x) * $S * pow($l, 2) * (($pr * pow($V, 1)) / 4);
      $a[7] = -($C_z_Dn / $m) * $S * (($pr * pow($V, 1)) / 2);

      $b[1] = -($m_x_vWx / $I_x) * $S * pow($l, 2) * (($pr * pow($V, 1)) / 4);
      $b[2] = -($m_x_B / $I_x) * $S * pow($l, 1) * (($pr * pow($V, 2)) / 2);
      $b[3] = -($m_x_De / $I_x) * $S * pow($l, 1) * (($pr * pow($V, 2)) / 2);
      $b[4] = ($g / $V) * cos($A_bal);
      $b[5] = -($m_y_De / $I_y) * $S * pow($l, 1) * (($pr * pow($V, 2)) / 2);
      $b[6] = -($m_y_vWx / $I_y) * $S * pow($l, 2) * (($pr * pow($V, 1)) / 4);
      $b[7] = sin($A_bal);

      $c[6] = $V / 57.3;

      echo "a: ";
      var_dump($a);
      echo "<br/>b: ";
      var_dump($b);
      echo "<br/>c: ";
      var_dump($c);
      echo "<br/>";

      $graph_data = array_fill(0,3,array());

      for($damper = 0; $damper <= 2; $damper++){

        $t = 0; // s - flight time
        $td = 0; // s - output time
        $tf = 30.001; // s - flight end time
        $dt = 0.01; // 1 per s - integration step
        $dd = 0.1; // s - output time

        echo "<h3 aling=\"left\"> Dempfer value = $damper.</h3>";

        $X = array (1 => 0);
        $Y = array (1 => 0);

        $X[1] = 0; // acceleration Y[1]
        $X[2] = 0; // acceleration Y[2]
        $X[3] = 0; // acceleration Y[3]
        $X[4] = 0; // acceleration Y[4]
        $X[5] = 0; // acceleration Y[5]
        $X[6] = 0; // acceleration Y[6]
        $X[7] = 0; // acceleration Y[7]
        $X[8] = 0; // acceleration Y[8]

        $Y[1] = 0; // 
        $Y[2] = 0; // 
        $Y[3] = 0; // 
        $Y[4] = 0; // 
        $Y[5] = 0; // 
        $Y[6] = 0; // 
        $Y[7] = 0; // 
        $Y[8] = 0; // 

        $De = 0;
        $Dn = 0;
        $Ded = 0;
        $Dnd = 0;
        //$X_s = 1;
        //$X_n = 1;
        //$k_se = 1;
        //$k_sp = 1;

        echo
        "<table width=\"100%\" cellspacing=\"0\" border=\"1\">
        <tr>
          <th>T</th>
          <th>De</th>
          <th>Dn</th>
          <th>Wx</th>
          <th>Wy</th>
          <th>PSI</th>
          <th>GAMMA</th>
          <th>BETTA</th>
          <th>Z</th>
        </tr>";

        for($t; $t <= $tf; $t += $dt){

          if($td < 0.5) {
            $Dnn = 0;
          } elseif($td < 1.5) {
            $Dnn = 10;
          } else $Dnn = 0;

          $Des = 0;

          $De = $Des + $Ded;
          $Dn = $Dnn + $Dnd;

            $X[1] = $Y[2];
            $X[2] = -$a[1] * $Y[2] - $b[6] * $Y[4] - $a[2] * $Y[5] - $a[3] * $Dn - $b[5] * $De;
            $X[3] = $Y[4];
            $X[4] = -$a[6] * $Y[2] - $b[1] * $Y[4] - $b[2] * $Y[5] - $a[5] * $Dn - $b[3] * $De;
            $X[5] = $Y[2] + $b[7] * $Y[4] + $b[4] * $Y[3] - $a[4] * $Y[5] - $a[7] * $Dn;
            $X[6] = -$c[6] * ($Y[1] - $Y[5]);

            for($i = 1; $i <= 6; $i++){
                $Y[$i] += $X[$i] * $dt;
            }

            $mode = $damper;
            switch($mode){
                case 0:
                $Ded = 0;
                $Dnd = 0;
                break;
                  case 1:
                  $Ded = $k_Wx * $Y[4];
                  $Dnd = $k_Wy * $Y[2];
                  break;
                    case 2:
                    $X[7] = $Ded;
                    $X[8] = $Dnd;
                    $Y[7] += $X[7] * $dt;
                    $Y[8] += $X[8] * $dt;
                    $Ded = ($k_Wx * $Y[4]) - ($Y[7]/$T_Wx);
                    $Dnd = ($k_Wy * $Y[2]) - ($Y[8]/$T_Wy);
                    break;
            }

            //$Des = $k_se * $X_s;
            //$Dnn = $k_sp * $X_n;

            for($t; $t >= $td; $td += $dd){
              array_push($graph_data[$damper], ["time"=>$td, "Wx"=>$Y[4], "Wy"=>$Y[2], "psi"=>$Y[1], "gamma"=>$Y[3], "betta"=>$Y[5]]);
                echo  "<tr>
                <td>" . number_format($td, 2, '.', ' ') . "</td>
                <td>" . number_format($De, 4, '.', ' ') . "</td>
                <td>" . number_format($Dn, 4, '.', ' ') . "</td>
                <td>" . number_format($Y[4], 4, '.', ' ') . "</td>
                <td>" . number_format($Y[2], 4, '.', ' ') . "</td>
                <td>" . number_format($Y[1], 4, '.', ' ') . "</td>
                <td>" . number_format($Y[3], 4, '.', ' ') . "</td>
                <td>" . number_format($Y[5], 4, '.', ' ') . "</td>
                <td>" . number_format($Y[6], 4, '.', ' ') . "</td>
                </tr>";
            }
        }
        echo "</table><br/>";

        $graph_data_file = 'data' . $damper . '.json';
        $handle = fopen($graph_data_file, 'w') or die ('Cannot open file: ' . $graph_data_file);
        $graph_content = json_encode($graph_data[$damper]);
        fwrite($handle, $graph_content);
      }
      echo
      "<table width=\"100%\" cellspacing=\"0\" border=\"1\">
       <tr>
        <th rowspan=\"2\" width=\"15%\">Damper control acts</th>
        <th colspan=\"1\">t_pp</th>
        <th colspan=\"2\">T_B</th>
        <th colspan=\"3\">X</th>
        <th colspan=\"2\">W_x_De</th>
       </tr>
       <tr>
       <th colspan=\"1\">model</th>
        <th colspan=\"1\">model</th>
        <th colspan=\"1\">real obj</th>
        <th colspan=\"1\">analyt design</th>
        <th colspan=\"1\">model</th>
        <th colspan=\"1\">real obj</th>
        <th colspan=\"1\">model</th>
        <th colspan=\"1\">real obj</th>
       </tr>
       <tr>
        <td>1</td><td>45.5</td><td>8</td><td>7-8</td><td>$Xx</td><td></td><td>1.0</td><td>0.023</td><td>0.7163</td>
       </tr>
       <tr>
        <td>2</td><td>8.5</td><td>7</td><td>7-8</td><td>$Xx</td><td></td><td>1.0</td><td>0.2</td><td>0.7163</td>
       </tr>
       <tr>
        <td>3</td><td>9</td><td>7.5</td><td>7-8</td><td>$Xx</td><td></td><td>1.0</td><td>0.4</td><td>0.7163</td>
       </tr>
      </table><br/>";
      echo "<h3>M = " . $V / $An . "</h3>";
      echo "<h2>Vi = " . $V * sqrt(($pr * 10)/(0.1249)) . "</h2> ";
    ?>

<html>
   <head>
      <script type = "text/javascript" src = "https://www.gstatic.com/charts/loader.js"></script>
      <script type = "text/javascript">
         google.charts.load('current', {packages: ['corechart','line']});  
      </script>
   </head>
   
   <body>
      <div id = "chart_div_a" style = "width: 1000px; height: 500px">
      </div>
      <div id = "chart_div_b" style = "width: 1000px; height: 500px">
      </div>
      <div id = "chart_div_c" style = "width: 1000px; height: 500px">
      </div>
      <div id = "chart_div_d" style = "width: 1000px; height: 500px">
      </div>
      <div id = "chart_div_e" style = "width: 1000px; height: 500px">
      </div>
      <script language = "JavaScript">

        function chart_div_a() {

          var data = new google.visualization.DataTable();
          data.addColumn('number', 'flight time');
          data.addColumn('number', '1 damper');
          data.addColumn('number', '2 damper');
          data.addColumn('number', '3 damper');
          data.addRows([
            <?php
              $a = file_get_contents("./data0.json");
              $a1 = file_get_contents("./data1.json");
              $a2 = file_get_contents("./data2.json");
              $json_a = json_decode($a, true);
              $json_a1 = json_decode($a1, true);
              $json_a2 = json_decode($a2, true);

              for ($i = 0; $i <= (count($json_a)-1); $i++) {
                echo "["
                . $json_a[$i]['time'] . ",  "
                . $json_a[$i]['Wx'] . ", "
                . $json_a1[$i]['Wx'] . ", "
                . $json_a2[$i]['Wx']
                . "],";
              }
            ?>
          ]);

          var options = {
            'title' : 'Wx',
            'width': 1000,
            'height': 500,
            curveType: 'function',
            colors: ['red', 'green', 'orange']
          };

          var chart = new google.visualization.LineChart(document.getElementById('chart_div_a'));

          chart.draw(data, options);
        }
        google.charts.setOnLoadCallback(chart_div_a);

        function chart_div_b() {

          var data = new google.visualization.DataTable();
          data.addColumn('number', 'flight time');
          data.addColumn('number', '1 damper');
          data.addColumn('number', '2 damper');
          data.addColumn('number', '3 damper');
          data.addRows([
            <?php
              $a = file_get_contents("./data0.json");
              $a1 = file_get_contents("./data1.json");
              $a2 = file_get_contents("./data2.json");
              $json_a = json_decode($a, true);
              $json_a1 = json_decode($a1, true);
              $json_a2 = json_decode($a2, true);

              for ($i = 0; $i <= (count($json_a)-1); $i++) {
                echo "["
                . $json_a[$i]['time'] . ",  "
                . $json_a[$i]['Wy'] . ", "
                . $json_a1[$i]['Wy'] . ", "
                . $json_a2[$i]['Wy']
                . "],";
              }
            ?>
          ]);

          var options = {
            'title' : 'Wy',
            'width': 1000,
            'height': 500,
            curveType: 'function',
            colors: ['red', 'green', 'orange']
          };

          var chart = new google.visualization.LineChart(document.getElementById('chart_div_b'));

          chart.draw(data, options);
        }
        google.charts.setOnLoadCallback(chart_div_b);

        function chart_div_c() {

          var data = new google.visualization.DataTable();
          data.addColumn('number', 'flight time');
          data.addColumn('number', '1 damper');
          data.addColumn('number', '2 damper');
          data.addColumn('number', '3 damper');
          data.addRows([
            <?php
              $a = file_get_contents("./data0.json");
              $a1 = file_get_contents("./data1.json");
              $a2 = file_get_contents("./data2.json");
              $json_a = json_decode($a, true);
              $json_a1 = json_decode($a1, true);
              $json_a2 = json_decode($a2, true);

              for ($i = 0; $i <= (count($json_a)-1); $i++) {
                echo "["
                . $json_a[$i]['time'] . ",  "
                . $json_a[$i]['psi'] . ", "
                . $json_a1[$i]['psi'] . ", "
                . $json_a2[$i]['psi']
                . "],";
              }
            ?>
          ]);

          var options = {
            'title' : 'PSI',
            'width': 1000,
            'height': 500,
            curveType: 'function',
            colors: ['red', 'green', 'orange']
          };

          var chart = new google.visualization.LineChart(document.getElementById('chart_div_c'));

          chart.draw(data, options);
        }
        google.charts.setOnLoadCallback(chart_div_c);

        function chart_div_d() {

          var data = new google.visualization.DataTable();
          data.addColumn('number', 'flight time');
          data.addColumn('number', '1 damper');
          data.addColumn('number', '2 damper');
          data.addColumn('number', '3 damper');
          data.addRows([
            <?php
              $a = file_get_contents("./data0.json");
              $a1 = file_get_contents("./data1.json");
              $a2 = file_get_contents("./data2.json");
              $json_a = json_decode($a, true);
              $json_a1 = json_decode($a1, true);
              $json_a2 = json_decode($a2, true);

              for ($i = 0; $i <= (count($json_a)-1); $i++) {
                echo "["
                . $json_a[$i]['time'] . ",  "
                . $json_a[$i]['gamma'] . ", "
                . $json_a1[$i]['gamma'] . ", "
                . $json_a2[$i]['gamma']
                . "],";
              }
            ?>
          ]);

          var options = {
            'title' : 'GAMMA',
            'width': 1000,
            'height': 500,
            curveType: 'function',
            colors: ['red', 'green', 'orange']
          };

          var chart = new google.visualization.LineChart(document.getElementById('chart_div_d'));

          chart.draw(data, options);
        }
        google.charts.setOnLoadCallback(chart_div_d);

        function chart_div_e() {

          var data = new google.visualization.DataTable();
          data.addColumn('number', 'flight time');
          data.addColumn('number', '1 damper');
          data.addColumn('number', '2 damper');
          data.addColumn('number', '3 damper');
          data.addRows([
            <?php
              $a = file_get_contents("./data0.json");
              $a1 = file_get_contents("./data1.json");
              $a2 = file_get_contents("./data2.json");
              $json_a = json_decode($a, true);
              $json_a1 = json_decode($a1, true);
              $json_a2 = json_decode($a2, true);

              for ($i = 0; $i <= (count($json_a)-1); $i++) {
                echo "["
                . $json_a[$i]['time'] . ",  "
                . $json_a[$i]['betta'] . ", "
                . $json_a1[$i]['betta'] . ", "
                . $json_a2[$i]['betta']
                . "],";
              }
            ?>
          ]);

          var options = {
            'title' : 'BETTA',
            'width': 1000,
            'height': 500,
            curveType: 'function',
            colors: ['red', 'green', 'orange']
          };

          var chart = new google.visualization.LineChart(document.getElementById('chart_div_e'));

          chart.draw(data, options);
        }
        google.charts.setOnLoadCallback(chart_div_e);

      </script>
   </body>
</html>