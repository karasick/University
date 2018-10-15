<html>
  <head>
    <title>
      CGW
    </title>
  </head>
  <body>
    <?php
      
      // airplane characteristics
      $S = 201.45; // m^2 - square
      $l = 37.55; // m - wingspan
      $b_a = 5.285; // m - average aerodynamic wing chord
      $al = 0.24; // pers AEwC - alignment
      $G = 73000; // kg - weight
      $I_x = 170000; // kg * m * s^2 - cross moment of inertia
      $I_y = 800000; // kg * m * s^2 - roadway moment of inertia
      $I_z = 660000; // kg * m * s^2 - lengthwise moment of inertia

      // hs flight mode 1
      $V0 = 97.2; // m per s - speed // Vhf - speed of  horizontal flight
      $H0 = 500; // m - height
      $pr = 0.119; // (kg * s^2) per m^2 - pressure
      $An = 338.36; // m per s - sound velocity
      $g = 9.81; // m per s^2 - gravitational acceleration
      $m = $G / $g; // N - Weight

      $P_1_Dg = 7003;
      $P_1_V = -13.8;

      $C_x_Alpha = 0.286;
      $C_x_M = 0;
      $C_xhf = 0.043;

      $C_y0 = -0.255;
      $C_y_Alpha = 5.78;
      $C_y_Dv = 0.2865;
      $C_y_M = 0;
      $C_yhf = 0.6446;

      $C_z_B = -0.8136;
      $C_z_Dn = -0.16;

      $m_x_Dn = -0.02;
      $m_x_vWy = -0.151;
      $m_x_vWx = -0.56;
      $m_x_B = -0.1146;
      $m_x_De = -0.07;

      $m_y_vWy = -0.141;
      $m_y_B = -0.1518;
      $m_y_Dn = -0.071;
      $m_y_De = 0;
      $m_y_vWx = 0.026; # error. it must be equal -0.026 #

      $m_z0 = 0.2;
      $m_z_vWz = -13;
      $m_z_vDAlpha = -3.8;
      $m_z_vAlpha = -1.38;
      $m_z_Dv = -0.96;
      $m_z_M = 0;
      
      // for task 2
      $k_Wx = 1.5; // s
      $k_Wy = 2.5; // s
      $T_Wx = 1.6; // s
      $T_Wy = 2.5; // s
      $k_Roll = 2.0;
      # F_De = +- 12 dergees // Aileron
      # F_Dn = +- 10 dergees // Direction wheel

      // for task 2.2
      $Z0 = 100; // m
      $k_z = 0.02; // degree / m
      $k_Dz = 0.6; // degree / (m / s)
      $T_z = 0.1; // s - .............
      # F_Roll = +-20 dergees
      # F_z = +-2000 m
      # F_Dz = +-300 m / s

      // for calculations
      $Ga_B = $m_y_B - (($C_z_B * $pr * $S * $l) / (4 * $m)) * $m_y_vWy;
      $W_x_De = 0;
      $Xx = (($m_x_B * $I_y) / ($m_y_B * $I_x)) * (1 / sqrt(1 - pow(($m_x_vWx / $I_x), 2) * $I_y * $S * pow($l, 2) * ($pr / (4 * $m_y_B))));
      $C_ybal = (2 * $G) / ($S * $pr * pow($V0, 2));
      $A_bal = 57.3 * (($C_ybal - $C_y0) / $C_y_Alpha);

      // coefficients for linear math. model of side plane profile
      $a[1] = -(($m_y_vWy * $pr * pow($V0, 1)) / (4 * $I_y)) * $S * pow($l, 2);
      $a[2] = -(($m_y_B * $pr * pow($V0, 2)) / (2 * $I_y)) * $S * pow($l, 1);
      $a[3] = -(($m_y_Dn * $pr * pow($V0, 2)) / (2 * $I_y)) * $S * pow($l, 1);
      $a[4] = -(($C_z_B * $pr * pow($V0, 1)) / (2 * $m)) * $S;
      $a[5] = -(($m_x_Dn * $pr * pow($V0, 2)) / (2 * $I_x)) * $S * pow($l, 1);
      $a[6] = -(($m_x_vWy * $pr * pow($V0, 1)) / (4 * $I_x)) * $S * pow($l, 2);
      $a[7] = -(($C_z_Dn * $pr * pow($V0, 1)) / (2 * $m)) * $S;
      $b[1] = -(($m_x_vWx * $pr * pow($V0, 1)) / (4 * $I_x)) * $S * pow($l, 2);
      $b[2] = -(($m_x_B * $pr * pow($V0, 2)) / (2 * $I_x)) * $S * pow($l, 1);
      $b[3] = -(($m_x_De * $pr * pow($V0, 2)) / (2 * $I_x)) * $S * pow($l, 1);
      $b[4] = ($g / $V0) * cos($A_bal); // * cos($A_hf)
      $b[5] = -(($m_y_De * $pr * pow($V0, 2)) / (2 * $I_y)) * $S * pow($l, 1);
      $b[6] = -(($m_y_vWx * $pr * pow($V0, 1)) / (4 * $I_y)) * $S * pow($l, 2);
      $b[7] = sin($A_bal); // sin($A_hf)
      $c[6] = $V0 / 57.3;
      echo "<b>a</b>: ";
      var_dump($a);
      echo "<br/><b>b</b>: ";
      var_dump($b);
      echo "<br/><b>c</b>: ";
      var_dump($c);
      echo "<br/>";

      $graph_data = array_fill(0,3,array());

      $damper = 1;

        $t = 0; // s - flight time
        $td = 0; // s - output time
        $tf = 110.001; // s - flight ending time
        $dt = 0.01; // 1 per s - integration step
        $dd = 0.1; // s - output step

        echo "<h3 aling=\"left\"> Dempfer value = $damper.</h3>";

        $X = array_fill(1, 10, 0);
        $Y = array_fill(1, 10, 0);

        $Y[6] = $Z0;
        $De = 0;
        $Dn = 0;
        $Ded = 0;
        $Dnd = 0;

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
          <th>DZ</th>
          <th>Roll_set</th>
        </tr>";

        for($t; $t <= $tf; $t += $dt){

          /*
          if($td < 0.5) {
            $Dnn = 0;
          } elseif($td < 1.5) {
            $Dnn = 10;
          } else $Dnn = 0;

          $Des = 0;
          $De = $Des + $Ded;
          $Dn = $Dnn + $Dnd;
          */

          if($Dnd < -10) {
            $Dnd = -10;
          } elseif($Dnd > 10) {
            $Dnn = 10;
          } 

          if($Ded < -12) {
            $Ded = -12;
          } elseif($Ded > 12) {
            $Ded = 12;
          }

          $De = $Ded;
          $Dn = $Dnd;

            $X[1] = $Y[2];
            $X[2] = -$a[1] * $Y[2] - $b[6] * $Y[4] - $a[2] * $Y[5] - $a[3] * $Dn - $b[5] * $De;
            $X[3] = $Y[4];
            $X[4] =  -$b[1] * $Y[4] - $a[6] * $Y[2] - $b[2] * $Y[5] - $a[5] * $Dn - $b[3] * $De;
            $X[5] = $Y[2] + $b[4] * $Y[3] + $b[7] * $Y[4] - $a[4] * $Y[5] - $a[7] * $Dn;
            $X[6] = -$c[6] * ($Y[1] - $Y[5]);

            for($i = 1; $i <= 6; $i++){
                $Y[$i] += $X[$i] * $dt;
            }

            $mode = $damper;
            switch($mode){
              case 0: {
                $Ded = 0;
                $Dnd = 0;
              break;
              }
              case 1: {
                $X[7] = $Ded;
                $X[8] = $Dnd;
                $Y[7] += $X[7] * $dt;
                $Y[8] += $X[8] * $dt;

                if($Y[6] < -2000) {
                  $Z = -2000;
                } elseif($Y[6] > 2000) {
                  $Z = 2000;
                } else {
                  $Z = $Y[6];
                }

                if($X[6] < -300) {
                  $DZ = -300;
                } elseif($X[6] > 300) {
                  $DZ = 300;
                } else {
                  $DZ = $X[6];
                }

                $Roll_set = $k_z * $Z + $k_Dz * $DZ;
                if($Roll_set < -300) {
                  $Roll_set = -300;
                } elseif($Roll_set > 300) {
                  $Roll_set = 300;
                } else {
                  $Roll_set = $Roll_set;
                }
                $X[9] = ($Roll_set - $Y[9]) / $T_z;
                $Y[9] += $X[9] * $dt;

                $DRoll = $Y[3] - $Y[9];
                $Der = $k_Roll * $DRoll;
                $Ded = (($k_Wx * $Y[4]) - ($Y[7]/$T_Wx)) + $Der;
                $Dnd = ($k_Wy * $Y[2]) - ($Y[8]/$T_Wy);
              break;
              }
            }

            for($t; $t >= $td; $td += $dd){
              array_push($graph_data[$damper], ["time"=>$td, "Wx"=>$Y[4], "Wy"=>$Y[2], "psi"=>$Y[1], "gamma"=>$Y[3], "betta"=>$Y[5]]);
                echo  "<tr>
                <td>" . number_format($td, 1, '.', ' ') . "</td>
                <td>" . number_format($De, 4, '.', ' ') . "</td>
                <td>" . number_format($Dn, 4, '.', ' ') . "</td>
                <td>" . number_format($Y[4], 4, '.', ' ') . "</td>
                <td>" . number_format($Y[2], 4, '.', ' ') . "</td>
                <td>" . number_format($Y[1], 4, '.', ' ') . "</td>
                <td>" . number_format($Y[3], 4, '.', ' ') . "</td>
                <td>" . number_format($Y[5], 4, '.', ' ') . "</td>
                <td>" . number_format($Y[6], 4, '.', ' ') . "</td>
                <td>" . number_format($X[6], 4, '.', ' ') . "</td>
                <td>" . number_format($Roll_set, 4, '.', ' ') . "</td>
                </tr>";
            }
        }
        echo "</table><br/>";

        $graph_data_file = 'data' . 0 . '.json';
        $handle = fopen($graph_data_file, 'w') or die ('Cannot open file: ' . $graph_data_file);
        $graph_content = json_encode($graph_data[$damper]);
        fwrite($handle, $graph_content);
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
      </table><br/>";
      $Vi = $V0 * 3.6 * sqrt(($pr)/(0.1249)); // Vhf
      $M = $Vi / $An;
      echo "<h2>Vi = " . $Vi . "</h2>";
      echo "<h3>M = " . $M . "</h3>";
    ?>

<html>
   <head>
      <script type = "text/javascript" src = "https://www.gstatic.com/charts/loader.js"></script>
      <script type = "text/javascript">
         google.charts.load('current', {packages: ['corechart','line']});  
      </script>
   </head>
   
   <body>
      <div id = "chart_div_Wx" style = "width: 1400px; height: 700px">
      </div>
      <div id = "chart_div_Wy" style = "width: 1400px; height: 700px">
      </div>
      <div id = "chart_div_PSI" style = "width: 1400px; height: 700px">
      </div>
      <div id = "chart_div_GAMMA" style = "width: 1400px; height: 700px">
      </div>
      <div id = "chart_div_BETTA" style = "width: 1400px; height: 700px">
      </div>
      <script language = "JavaScript">

        function chart_div_Wx() {
          var data = new google.visualization.DataTable();
          data.addColumn('number', 'flight time');
          data.addColumn('number', '1 damper');
          data.addRows([
            <?php
              $data_0 = file_get_contents("./data0.json");
              $json_data_0 = json_decode($data_0, true);
              for ($i = 0; $i <= (count($json_data_0)-1); $i++) {
                echo "["
                . $json_data_0[$i]['time'] . ",  "
                . $json_data_0[$i]['Wx']
                . "],";
              }
            ?>
          ]);
          var options = {
            'title' : 'Wx',
            'width': 1400,
            'height': 700,
            curveType: 'function',
            colors: ['blue']
          };
          var chart = new google.visualization.LineChart(document.getElementById('chart_div_Wx'));
          chart.draw(data, options);
        }
        google.charts.setOnLoadCallback(chart_div_Wx);

        function chart_div_Wy() {
          var data = new google.visualization.DataTable();
          data.addColumn('number', 'flight time');
          data.addColumn('number', '1 damper');
          data.addRows([
            <?php
              $data_0 = file_get_contents("./data0.json");
              $json_data_0 = json_decode($data_0, true);
              for ($i = 0; $i <= (count($json_data_0)-1); $i++) {
                echo "["
                . $json_data_0[$i]['time'] . ",  "
                . $json_data_0[$i]['Wy']
                . "],";
              }
            ?>
          ]);
          var options = {
            'title' : 'Wy',
            'width': 1400,
            'height': 700,
            curveType: 'function',
            colors: ['blue']
          };
          var chart = new google.visualization.LineChart(document.getElementById('chart_div_Wy'));
          chart.draw(data, options);
        }
        google.charts.setOnLoadCallback(chart_div_Wy);

        function chart_div_PSI() {
          var data = new google.visualization.DataTable();
          data.addColumn('number', 'flight time');
          data.addColumn('number', '1 damper');
          data.addRows([
            <?php
              $data_0 = file_get_contents("./data0.json");
              $json_data_0 = json_decode($data_0, true);
              for ($i = 0; $i <= (count($json_data_0)-1); $i++) {
                echo "["
                . $json_data_0[$i]['time'] . ",  "
                . $json_data_0[$i]['psi']
                . "],";
              }
            ?>
          ]);
          var options = {
            'title' : 'PSI',
            'width': 1400,
            'height': 700,
            curveType: 'function',
            colors: ['blue']
          };
          var chart = new google.visualization.LineChart(document.getElementById('chart_div_PSI'));
          chart.draw(data, options);
        }
        google.charts.setOnLoadCallback(chart_div_PSI);

        function chart_div_GAMMA() {
          var data = new google.visualization.DataTable();
          data.addColumn('number', 'flight time');
          data.addColumn('number', '1 damper');
          data.addRows([
            <?php
              $data_0 = file_get_contents("./data0.json");
              $json_data_0 = json_decode($data_0, true);
              for ($i = 0; $i <= (count($json_data_0)-1); $i++) {
                echo "["
                . $json_data_0[$i]['time'] . ",  "
                . $json_data_0[$i]['gamma']
                . "],";
              }
            ?>
          ]);
          var options = {
            'title' : 'GAMMA',
            'width': 1400,
            'height': 700,
            curveType: 'function',
            colors: ['blue']
          };
          var chart = new google.visualization.LineChart(document.getElementById('chart_div_GAMMA'));
          chart.draw(data, options);
        }
        google.charts.setOnLoadCallback(chart_div_GAMMA);

        function chart_div_BETTA() {
          var data = new google.visualization.DataTable();
          data.addColumn('number', 'flight time');
          data.addColumn('number', '1 damper');
          data.addRows([
            <?php
              $data_0 = file_get_contents("./data0.json");
              $json_data_0 = json_decode($data_0, true);
              for ($i = 0; $i <= (count($json_data_0)-1); $i++) {
                echo "["
                . $json_data_0[$i]['time'] . ",  "
                . $json_data_0[$i]['betta']
                . "],";
              }
            ?>
          ]);
          var options = {
            'title' : 'BETTA',
            'width': 1400,
            'height': 700,
            curveType: 'function',
            colors: ['blue']
          };
          var chart = new google.visualization.LineChart(document.getElementById('chart_div_BETTA'));
          chart.draw(data, options);
        }
        google.charts.setOnLoadCallback(chart_div_BETTA);

      </script>
   </body>
</html>