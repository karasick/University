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
      $m_y_vWx = -0.026;   # error. it must be equal: -0.026 # # #

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
      $k_Roll = 2.0; // 
      # F_De = +- 12 dergees // Aileron
      # F_Dn = +- 10 dergees // Direction wheel

      // for task 2.2
      $Z0 = 100; // m
      $k_z = 0.02; // degree / m
      // $k_z = 0.004; // degree / m // 2.8.2
      // $k_z = 0.1; // degree / m  // 2.8.2
      $k_Dz = 0.6; // degree / (m / s)
      // $k_Dz = 0; // degree / (m / s) // 2.8.3
      $T_z = 1; // s - .............
      # F_Roll = +-20 dergees
      # F_z = +-2000 m
      # F_Dz = +-300 m / s

      // for calculations
      $Ga_B = $m_y_B - (($C_z_B * $pr * $S * $l) / (4 * $m)) * $m_y_vWy;
      $W_x_De = -0.73;
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

      // $mode = "free flight kappa"; // 
      // $mode = "free flight De"; // 
      $mode = "regulation"; // 
      
      // $method = "eiler"; // 
      $method = "runge-kutta"; // 

      // $signal = "zero"; // 
      $signal = "normal"; // 

      $t = 0; // s - flight time
      $td = 0; // s - output time
      $tf = 90.1; // s - flight ending time
      $dt = 0.01; // 1 per s - integration step
      $dd = 0.1; // s - output step

      echo "<h3 aling=\"left\"> Mode value = <u>$mode</u>. Method value = <u>$method</u>. Signal value = <u>$signal</u>. Integration step value = <u>$dt</u> </h3>";

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

        $De = $Y[7];
        $Dn = $Y[8];

        switch($mode) {
          case "free flight kappa" : { // 2.5
            if($td < 1) {
              $Dn = 0;
            } elseif($td < 2) {
              $Dn = 10;
            } else $Dn = 0;
            $De = 0;
          break;
          }
          case "free flight De" : { // 2.5
            $Dn = 0;
            $De = -1;
          break;
          }
        }

        $X[1] = $Y[2];
        $X[2] = -$a[1] * $Y[2] - $b[6] * $Y[4] - $a[2] * $Y[5] - $a[3] * $Dn - $b[5] * $De;
        $X[3] = $Y[4];
        $X[4] = -$b[1] * $Y[4] - $a[6] * $Y[2] - $b[2] * $Y[5] - $a[5] * $Dn - $b[3] * $De;
        $X[5] = $Y[2] + $b[4] * $Y[3] + $b[7] * $Y[4] - $a[4] * $Y[5] - $a[7] * $Dn;
        $X[6] = -$c[6] * ($Y[1] - $Y[5]);

        if($Y[6] < -2000) {
          $Y[6] = -2000;
        } elseif($Y[6] > 2000) {
          $Y[6] = 2000;
        }
        if($X[6] < -300) {
          $X[6] = -300;
        } elseif($X[6] > 300) {
          $X[6] = 300;
        }
        $Roll_c = $k_z * $Y[6] + $k_Dz * $X[6];
        if($Roll_c < -300) {
          $Roll_c = -300;
        } elseif($Roll_c > 300) {
          $Roll_c = 300;
        }
        $X[9] = $Roll_c - ($Y[9] / $T_z); // Roll_set == $Y[9]   # error. it must be: $X[9] = $Roll_c - ($Y[9] / $T_z) # # #
        $DRoll = $Y[3] - $Y[9];
        $Der = $k_Roll * $DRoll;
        $X[7] = ($k_Wx * $X[4]) + (($Der - $Y[7]) / $T_Wx);
        switch($mode) {
          case "free flight" : { // 2.5
            $X[8] = 0;
          break;
          }
          case "regulation" : {
            $X[8] = ($k_Wy * $X[2]) - ($Y[8] / $T_Wy);
          break;
          }
        }

        switch($method) {
          case "eiler" : {
            for($i = 1; $i <= 9; $i++){
              $Y[$i] += $X[$i] * $dt;
            }
            break;
          }
          case "runge-kutta" : {
            for($i = 1; $i <= 9; $i++){
              $K1[$i] = $X[$i];
              $X_K2[$i] = $X[$i] + $dt;
              $Y_K2[$i] = $Y[$i] + $dt * $K1[$i];
            }
            $X_K2[1] = $Y_K2[2];
            $X_K2[2] = -$a[1] * $Y_K2[2] - $b[6] * $Y_K2[4] - $a[2] * $Y_K2[5] - $a[3] * $Dn - $b[5] * $De;
            $X_K2[3] = $Y_K2[4];
            $X_K2[4] = -$b[1] * $Y_K2[4] - $a[6] * $Y_K2[2] - $b[2] * $Y_K2[5] - $a[5] * $Dn - $b[3] * $De;
            $X_K2[5] = $Y_K2[2] + $b[4] * $Y_K2[3] + $b[7] * $Y_K2[4] - $a[4] * $Y_K2[5] - $a[7] * $Dn;
            $X_K2[6] = -$c[6] * ($Y_K2[1] - $Y_K2[5]);

            if($Y_K2[6] < -2000) {
              $Y_K2[6] = -2000;
            } elseif($Y_K2[6] > 2000) {
              $Y_K2[6] = 2000;
            }
            if($X_K2[6] < -300) {
              $X_K2[6] = -300;
            } elseif($X_K2[6] > 300) {
              $X_K2[6] = 300;
            }
            $Roll_c = $k_z * $Y_K2[6] + $k_Dz * $X_K2[6];
            if($Roll_c < -300) {
              $Roll_c = -300;
            } elseif($Roll_c > 300) {
              $Roll_c = 300;
            }
            $X_K2[9] = $Roll_c - ($Y_K2[9] / $T_z); // Roll_set == $Y[9]   # error. it must be: $X[9] = $Roll_c - ($Y[9] / $T_z) # # #
            $DRoll = $Y_K2[3] - $Y_K2[9];
            $Der = $k_Roll * $DRoll;
            $X_K2[7] = ($k_Wx * $X_K2[4]) + (($Der - $Y_K2[7]) / $T_Wx);
            switch($mode){
              case "free flight": {
                $X_K2[8] = 0;
              break;
              }
              case "regulation": {
                $X_K2[8] = ($k_Wy * $X_K2[2]) - ($Y_K2[8] / $T_Wy);
              break;
              }
            }
            for($i = 1; $i <= 9; $i++){
              $K2[$i] = $X_K2[$i];
              $Y[$i] += ($dt / 2) * ($K1[$i] + $K2[$i]);
            }
            break;
          }
        }
        for($t; $t >= $td; $td += $dd){
          array_push($graph_data[0], ["time"=>$td, "Wx"=>$Y[4], "Wy"=>$Y[2], "psi"=>$Y[1], "gamma"=>$Y[3], "betta"=>$Y[5], "Z" => $Y[6]]);
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
            <td>" . number_format($Y[9], 4, '.', ' ') . "</td>
            </tr>";
        }
      }
      echo "</table><br/>";

      $graph_data_file = 'data' . 0 . '.json';
      $handle = fopen($graph_data_file, 'w') or die ('Cannot open file: ' . $graph_data_file);
      $graph_content = json_encode($graph_data[0]);
      fwrite($handle, $graph_content);

      echo
      "<table width=\"75%\" cellspacing=\"0\" border=\"1\">
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
        <td>default</td><td>15</td><td>7</td><td>7-8</td><td>" . number_format($Xx, 2, '.', ' ') . "</td><td></td><td>1.0</td><td>$W_x_De</td><td>-0.7163</td>
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
      <div id = "chart_div_Z" style = "width: 1400px; height: 700px">
      </div>
      <script language = "JavaScript">

        function chart_div_Wx() {
          var data = new google.visualization.DataTable();
          data.addColumn('number', 'flight time');
          data.addColumn('number', 'default damper');
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
          data.addColumn('number', 'default damper');
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
          data.addColumn('number', 'default damper');
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
          data.addColumn('number', 'default damper');
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
          data.addColumn('number', 'default damper');
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

        function chart_div_Z() {
          var data = new google.visualization.DataTable();
          data.addColumn('number', 'flight time');
          data.addColumn('number', 'default damper');
          data.addRows([
            <?php
              $data_0 = file_get_contents("./data0.json");
              $json_data_0 = json_decode($data_0, true);
              for ($i = 0; $i <= (count($json_data_0)-1); $i++) {
                echo "["
                . $json_data_0[$i]['time'] . ",  "
                . $json_data_0[$i]['Z']
                . "],";
              }
            ?>
          ]);
          var options = {
            'title' : 'Z',
            'width': 1400,
            'height': 700,
            curveType: 'function',
            colors: ['blue']
          };
          var chart = new google.visualization.LineChart(document.getElementById('chart_div_Z'));
          chart.draw(data, options);
        }
        google.charts.setOnLoadCallback(chart_div_Z);

      </script>
   </body>
</html>