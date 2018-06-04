<html>
  <head>
    <title>
      Lab 2_4_3
    </title>
  </head>
  <body>
    <?php

      $S = 201.45; // m^2 - square
      $b_a = 5.285; // m - average aerodynamic wing chord
      $l = 37.55; // m - wingspan
      $G = 73000; // kg - weight
      $al = 0.24; // pers AEwC -  alignment (tsentrovka)
      $I_x = 250000; // kg * m * s^2 - cross moment of inertia
      $I_y = 900000; // kg * m * s^2 - roadway moment of inertia
      $I_z = 660000; // kg * m * s^2 -  moment of inertia

      $V = 97.2; // m per s - speed
      $H = 500; // m - height
      $pr = 0.1190; // (kg * s^2) per m^2 - pressure
      $An = 338.36; // m per s - sound velocity

      $g = 9.81; // m per s^2 - gravitational acceleration
      $m = $G / $g; // N - Weight

      $C_x = 0.046;

      $C_y0 = -0.255;
      $C_y_A = 5.78;
      $C_y_Dv = 0.2865;

      $m_z0 = 0.2;
      $m_z_vWz = -13;
      $m_z_vA = -3.8;
      $m_z_A = -1.83;
      $m_z_Dv = -0.96;

      $k_n = 0.1;
      $k_dn = 0.4;
      $k_Wz = 3.0;
  
      $C_ybal = (2 * $G) / ($S * $pr * pow($V, 2));
      $A_bal = 57.3 * (($C_ybal - $C_y0) / $C_y_A);
      $Dv_bal = -57.3 * (($m_z0 + (($m_z_A * $A_bal) / 57.3) + $C_ybal * (($al) - 0.24)) / $m_z_Dv);

      $Ly = 300;
      $Ry = $V / $Ly;
      $Sigma_Wy = array(0.5, 1.0, 3.0);

      $o[1] = -($m_z_vWz / $I_z) * $S * pow($b_a, 2) * (($pr * pow($V, 1) / 2));
      $o[2] = -($m_z_A / $I_z) * $S * pow($b_a, 1) * (($pr * pow($V, 2) / 2));
      $o[3] = -($m_z_Dv / $I_z) * $S * pow($b_a, 1) * (($pr * pow($V, 2) / 2));
      $o[4] = (($C_y_A + $C_x) / $m) * $S * (($pr * pow($V, 1) / 2));
      $o[5] = -($m_z_vA / $I_z) * $S * pow($b_a, 2) * (($pr * pow($V, 1) / 2));
      $o[6] = $V / 57.3;
      $o[9] = ($C_y_Dv / $m) * $S * (($pr * pow($V, 1) / 2));
      $o[16] = $V / (57.3 * $g);

      echo "c: ";
      var_dump($o);
      echo "<br/>";

      $graph_data = array_fill(0,3,array());

      for($turbulence_intensity = 0; $turbulence_intensity <= 2; $turbulence_intensity++){

        $t = 0; // s - flight time
        $td = 0; // s - output time
        $tf = 300.01; // s - flight end time
        $dt = 0.01; // 1 per s - integration step
        $dd = 5; // s - output time

        echo "<h3 aling=\"left\"> Turbulence intensity = " . $Sigma_Wy[$turbulence_intensity] . "</h3>";

        $X = array_fill(1,7,0);
        $Y = array_fill(1,7,0);

        $A_v = 0;
        $DA = 0;
        $F_Sigma = 0;
        $DH = 0;

        $h_ri = 0;
        $Ht = 0;

        $Dv = $Dv_bal;
        $H_set = 0;

        echo
        "<table width=\"100%\" cellspacing=\"0\" border=\"1\">
        <tr>
          <th>T</th>
          <th>Wy</th>
          <th>Dv</th>
          <th>ALPHA</th>
          <th>TANG</th>
          <th>H</th>
          <th>Ny</th>
        </tr>";

        for($t; $t <= $tf; $t += $dt){

            $h_ri = rand(0, getrandmax()) / getrandmax();
            $Ht = 0;
            for($j = 0; $j < 12; $j++) {
                $Ht += $h_ri;
            }
            $Ht -= 6;
            $Ht /= 6;

            $X[1] = $Y[2];
            $X[2] = -$o[1] * $Y[2] - $o[2] * $A_v - $o[5] * $X[4] - $o[3] * $Dv;
            $X[3] = $o[4] * $A_v + $o[9] * $Dv;
            $X[4] = $X[1] - $X[3];
            $A_v = $Y[4] + $DA;
            $DA = $Y[6] / $o[6];
            $X[5] = $o[6] * $Y[3];
            $Ny = $o[16] * $X[3];
            $X[6] = $Y[7] + 1.76 * sqrt($Ry) * $Sigma_Wy[$turbulence_intensity] * ($Ht / sqrt($dt));
            $X[7] = -2 * $Ry * $Y[7] - pow($Ry, 2) * $Y[6] - 2.46 * $Ry * sqrt($Ry) * $Sigma_Wy[$turbulence_intensity] * ($Ht / sqrt($dt));

            for($i = 1; $i <= 7; $i++){
                $Y[$i] += $X[$i] * $dt;
            }

            for($t; $t >= $td; $td += $dd){
              array_push($graph_data[$turbulence_intensity], ["time"=>$td, "alpha"=>$Y[4], "tang"=>$Y[1], "n_y"=>$Ny]);
                echo  "<tr>
                <td>" . number_format($td, 2, '.', ' ') . "</td>
                <td>" . number_format($Y[6], 4, '.', ' ') . "</td>
                <td>" . number_format($Dv, 4, '.', ' ') . "</td>
                <td>" . number_format($Y[4], 4, '.', ' ') . "</td>
                <td>" . number_format($Y[1], 4, '.', ' ') . "</td>
                <td>" . number_format($Y[5], 4, '.', ' ') . "</td>
                <td>" . number_format($Ny, 4, '.', ' ') . "</td>
                </tr>";
            }
        }
        echo "</table><br/>";

        $graph_data_file = 'data' . $turbulence_intensity . '.json';
        $handle = fopen($graph_data_file, 'w') or die ('Cannot open file: ' . $graph_data_file);
        $graph_content = json_encode($graph_data[$turbulence_intensity]);
        fwrite($handle, $graph_content);
      }
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
                . $json_a[$i]['alpha'] . ", "
                . $json_a1[$i]['alpha'] . ", "
                . $json_a2[$i]['alpha']
                . "],";
              }
            ?>
          ]);

          var options = {
            'title' : 'Alpha',
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
                . $json_a[$i]['tang'] . ", "
                . $json_a1[$i]['tang'] . ", "
                . $json_a2[$i]['tang']
                . "],";
              }
            ?>
          ]);

          var options = {
            'title' : 'Tang',
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
                . $json_a[$i]['n_y'] . ", "
                . $json_a1[$i]['n_y'] . ", " //colors: ['red', 'green', 'orange']
                . $json_a2[$i]['n_y']
                . "],";
              }
            ?>
          ]);

          var options = {
            'title' : 'Ny',
            'width': 1000,
            'height': 500,
            curveType: 'function',
            colors: ['red', 'green', 'orange']
          };

          var chart = new google.visualization.LineChart(document.getElementById('chart_div_c'));

          chart.draw(data, options);
        }
        google.charts.setOnLoadCallback(chart_div_c);

      </script>
   </body>
</html>