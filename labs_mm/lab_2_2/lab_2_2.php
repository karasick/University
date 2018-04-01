<html>
  <head>
    <title>
      Lab 2_2
    </title>
  </head>
  <body>
    <?php

      $S = 201.45; // m^2 - square
      $b_a = 5.285; // m - average aerodynamic wing chord
      $G = 73000; // kg - weight
      $al = array(0 => 18, 1 => 24, 2 => 32); // pers AEwC -  alignment (tsentrovka)
      $Iz = 660000; // kg * m * s^2 - lengthwise moment of inertia

      $n_dv = 3; // amount of engines
      $Y_dv = 0.5; // m - engine thrust arm in vertical area of ​​symmetry

      $V = 97.2; // m per s - speed
      $h = 500; // m - height
      $pr = 0.1190; // (kg * s^2) per m^2 - pressure
      $a_n = 338.36; // m per s - ?

      $g = 9.81; // m per s^2 - gravitational acceleration

      $C_y0 = -0.255; // - aerodynamic characteristic of ?
      $C_y_A = 5.78; // - aerodynamic characteristic of ?
      $C_y_Dv = 0.2865; // - aerodynamic characteristic of ?
      $C_y_M = 0.0; // - aerodynamic characteristic of ?
      $C_xgp = 0.046; // - aerodynamic characteristic of ?
      $C_x_A = 0.286; // - aerodynamic characteristic of ?
      $C_x_M = 0.0; // - aerodynamic characteristic of ?

      $m_z0 = 0.20; // - ?
      $m_z_vWz = -13; // - ?
      $m_z_vA = -3.8; // - ?
      $m_z_A = -1.83; // - ?
      $m_z_Dv = -0.96; // - ?
      $m_z_Cy = -0.3166; // - ?
      $m_z_M = 0; // - ?

      $P1_Dg = 7003; // - ?
      $P1_V = -13.8; // - ?

      $k_wz = 1.0; // 1 per s - coefficient
      $T_wz = 0.7; // s - transmitting function
      $ks = 0.112; // - ?
      $xv = -17.86; // - ?

      $m = $G / $g; // N - Weight

      $C_ygp = (2 * $G) / ($S * $pr * pow($V, 2));
      $M_gp = $V / $a_n;
      $D_ny = $m_z_Cy + (($pr * $S * $b_a) / (2 * $m)) * $m_z_vWz;
      $D_v = $m_z_Cy * (1 + $C_y_M * ($M_gp / (2 * $C_ygp))) - $m_z_M * ($M_gp / (2 * $C_ygp));
      $W_V = ($g / $V) * sqrt((2 * $D_v) / $D_ny);
      $T_V = (2 * M_PI) / $W_V;

      $Dv_ny = -57.3 * $D_ny * ($C_ygp / $m_z_Dv);
      $C_ybal = (2 * $G) / ($S * $pr * pow($V, 2));
      $A_bal = 57.3 * (($C_ybal - $C_y0) / $C_y_A);
      $Dv_bal = -57.3 * (($m_z0 + (($m_z_A * $A_bal) / 57.3) + $C_ybal * (($al[1] / 100) - 0.24)) / $m_z_Dv);

      $C = array(1 => 0);
      $e = array(1 => 0);

     $graph_dat = array_fill(0,3,array());

      for($alignment = 0; $alignment <= 2; $alignment++){

        $mode = $alignment;

        switch($mode){
            case 0:
            $Dv = -2;
            $Dg = 0;
            $Wx = 0;
            break;
            case 1:
            $Dv = -2;
            $Dg = 5;
            $Wx = -5;
            break;
            case 2:
            $Dv = -2;
            $Dg = 0;
            $Wx = 0;
            break;
        }


        $Dal = ($al[$alignment] / 100) - 0.24;
        $Vt = 0;
        $Ny = 0;

        $C[1] = (- ($m_z_vWz / $Iz)) * $S * pow($b_a, 2) * (($pr * $V) / 2);
        $C[2] = (- ($m_z_A / $Iz)) * $S * $b_a * (($pr * pow($V, 2)) / 2);
        $C[3] = (- ($m_z_Dv / $Iz)) * $S * $b_a * (($pr * pow($V, 2)) / 2);
        $C[4] = (($C_y_A + $C_xgp) / $m) * $S * (($pr * $V) / 2);
        $C[5] = (- ($m_z_vA / $Iz)) * $S * pow($b_a, 2) * (($pr * $V) / 2);
        $C[6] = $V / 57.3;
        $C[7] = $g / 57.3;
        $C[8] = (($C_x_A - $C_ygp) / (57.3 * $m)) * $S * (($pr * pow($V, 2)) / 2);
        $C[9] = ( (($C_y_Dv) / $m)) * $S * (($pr * $V) / 2);
        $C[16] = $V / (57.3 * $g);
        $C[17] = (- (($C_y_A * $Dal) / $Iz)) * $S * $b_a * (($pr * pow($V, 2)) / 2);
        $C[18] = (- (($C_y_Dv * $Dal) / $Iz)) * $S * $b_a * (($pr * pow($V, 2)) / 2);
        $C[19] = (-($n_dv * $P1_Dg) / (57.3 * $m));

        $e[1] = ($C_xgp + (($C_x_M * $M_gp) / 2) - (($n_dv * $P1_V) / ($pr * $V * $S))) * $S * (($pr * $V) / $m);
        $e[2] = ($C_ygp + (($C_y_M * $M_gp) / 2)) * $S * ((57.3 * $pr) / $m);
        $e[3] = (-(57.3 / $Iz)) * ((($m_z_M / $a_n) + ((2 * $C_xgp * $Y_dv) / ( $V * $b_a))) * $S * $b_a * (($pr * pow($V, 2)) / 2) - $n_dv * $P1_V * $Y_dv);

        echo "<p>" . var_dump($C) . "</p>";
        echo "<p>" . var_dump($e) . "</p>";

        $t = 0; // s - flight time
        $td = 0; // s - output time
        $tf = 180.01; // s - flight end time
        $dt = 0.01; // 1 per s - integration step
        $dd = 2; // s - output time

        echo "<h3 aling=\"left\"> Alignment value = 0." . $al[$alignment] . "</h3>";

        $X = array (1 => 0);
        $Y = array (1 => 0);

        $X[1] = 0;
        $X[2] = 0;
        $X[3] = 0;
        $X[4] = 0;
        $X[5] = 0;
        $X[6] = 0;

        $Y[1] = 0;
        $Y[2] = 0;
        $Y[3] = 0;
        $Y[4] = 0;
        $Y[5] = 0;
        $Y[6] = 0;

        echo
        "<table width=\"100%\" cellspacing=\"0\" border=\"1\">
        <tr>
          <th>T</th>
          <th>WX</th>
          <th>DV</th>
          <th>DG</th>
          <th>ALF</th>
          <th>TANG</th>
          <th>KSI</th>
          <th>NY</th>
          <th>H</th>
          <th>V</th>
          <th>VK</th>
        </tr>";

        for($t; $t <= $tf; $t+=$dt){

            $X[1] = -$e[1] * $Vt - $C[8] * $Y[5] - $C[7] * $Y[2] - $C[19] * $Dg;
            $X[2] = $Y[3];
            $X[3] = -$C[1] * $Y[3] - ($C[2] + $C[17]) * $Y[5] - $C[5] * $X[5] - $e[3] * $Vt - ($C[3] + $C[18]) * $Dv;
            $X[4] = $C[4] * $Y[5] + $e[2] * $Vt + $C[9] * $Dv;
            $X[5] = $X[2] - $X[4];
            $X[6] = $C[6] * $Y[4];
            $Vt = $Y[1] - $Wx;
            $Ny = $C[16] * $X[4];

            for($i = 1; $i <= 6; $i++){
                $Y[$i] += $X[$i] * $dt;
            }

            for($t; $t >= $td; $td+=$dd){
              array_push($graph_dat[$alignment], ["time"=>$td, "Vt"=>$Vt, "H"=>$Y[6], "Ny"=>$Ny]);
                echo  "<tr>
                <td>" . number_format($td, 1, '.', ' ') . "</td>
                <td>" . number_format($Wx, 4, '.', ' ') . "</td>
                <td>" . number_format($Dv, 4, '.', ' ') . "</td>
                <td>" . number_format($Dg, 4, '.', ' ') . "</td>
                <td>" . number_format($Y[5], 4, '.', ' ') . "</td>
                <td>" . number_format($Y[3], 4, '.', ' ') . "</td>
                <td>" . number_format($Y[4], 4, '.', ' ') . "</td>
                <td>" . number_format($Ny, 4, '.', ' ') . "</td>
                <td>" . number_format($Y[6], 4, '.', ' ') . "</td>
                <td>" . number_format($Vt, 4, '.', ' ') . "</td>
                <td>" . number_format($Y[1], 4, '.', ' ') . "</td>
                </tr>";
            }
        }
        echo "</table><br/>";

        $graph_dat_file = 'data' . $alignment . '.json';
        $handle = fopen($graph_dat_file, 'w') or die ('Cannot open file: ' . $graph_dat_file);
        $graph_content = json_encode($graph_dat[$alignment]);
        fwrite($handle, $graph_content);
      }
      echo
      "<table width=\"100%\" cellspacing=\"0\" border=\"1\">
       <tr>
        <th colspan=\"2\">Dv_bal<br/>vXt = 24</th>
        <th colspan=\"3\">Dv_ny<br/>vXt = 24</th>
        <th colspan=\"4\">T_v</th>
       </tr>
       <tr>
       <th rowspan=\"2\">analyt design</th>
        <th rowspan=\"2\">real obj</th>
        <th rowspan=\"2\">analyt design</th>
        <th rowspan=\"2\">model</th>
        <th rowspan=\"2\">real obj</th>
        <th rowspan=\"2\">analyt design<br/>vXt = 24</th>
        <th colspan=\"3\">model</th>
       </tr>
       <tr>
        <th>vXt = 18</th>
        <th>vXt = 24</th>
        <th>vXt = 32</th>
       </tr>
       <tr>
        <td>$Dv_bal</td><td></td><td>$Dv_ny</td><td></td><td></td><td>$T_V</td><td></td><td></td><td></td>
       </tr>
      </table><br/>";
      echo "<h3>M = " . $V / 338.38 . "</h3>";
      echo "<h2>Vi = " . $V * sqrt(($pr * 10)/(0.1249)) . "</h2> "; //НУЖНОПРЕОБРАЗОВАТЬ
    ?>

<html>
  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['line']});
      google.charts.setOnLoadCallback(draw_chart_a);
      google.charts.setOnLoadCallback(draw_chart_b);
      google.charts.setOnLoadCallback(draw_chart_c);

      function draw_chart_a() {

        var data = new google.visualization.DataTable();
        data.addColumn('number', 'flight time');
        data.addColumn('number', 'alignment = 18');
        data.addColumn('number', 'alignment = 24');
        data.addColumn('number', 'alignment = 32');

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
            . $json_a[$i]['Vt'] . ", "
            . $json_a1[$i]['Vt'] . ", "
            . $json_a2[$i]['Vt']
            . "],";
          }
        ?>
        ]);

        var options = {
          chart: {
            title: 'True Velocity',
            subtitle: 'for 3 alignment modes'
          },
          width: 1000,
          height: 350,
          colors: ['red', 'green', 'cyan']
        };

        var chart = new google.charts.Line(document.getElementById('chart_div_a'));

          chart.draw(data, google.charts.Line.convertOptions(options));
        }

        function draw_chart_b() {

          var data = new google.visualization.DataTable();
          data.addColumn('number', 'flight time');
          data.addColumn('number', 'alignment = 18');
          data.addColumn('number', 'alignment = 24');
          data.addColumn('number', 'alignment = 32');

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
              . $json_a[$i]['H'] . ", "
              . $json_a1[$i]['H'] . ", "
              . $json_a2[$i]['H']
              . "],";
            }
          ?>
          ]);

          var options = {
            chart: {
              title: 'Flight level',
              subtitle: 'for 3 alignment modes'
            },
            width: 1000,
            height: 350,
            colors: ['red', 'green', 'cyan']
          };

          var chart = new google.charts.Line(document.getElementById('chart_div_b'));

          chart.draw(data, google.charts.Line.convertOptions(options));
        }

        function draw_chart_c() {

          var data = new google.visualization.DataTable();
          data.addColumn('number', 'flight time');
          data.addColumn('number', 'alignment = 18');
          data.addColumn('number', 'alignment = 24');
          data.addColumn('number', 'alignment = 32');

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
              . $json_a[$i]['Ny'] . ", "
              . $json_a1[$i]['Ny'] . ", "
              . $json_a2[$i]['Ny']
              . "],";
            }
          ?>
          ]);

          var options = {
            chart: {
              title: 'Vertical overloading',
              subtitle: 'for 3 alignment modes'
            },
            width: 1000,
            height: 350,
            colors: ['red', 'green', 'cyan']
          };

          var chart = new google.charts.Line(document.getElementById('chart_div_c'));

          chart.draw(data, google.charts.Line.convertOptions(options));
        }

    </script>
  </head>
  <body>
    <div id="chart_div_a"></div>
    <div id="chart_div_b"></div>
    <div id="chart_div_c"></div>
  </body>
</html>