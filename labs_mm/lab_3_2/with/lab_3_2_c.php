<html>
  <head>
    <title>
      Lab 3_1_c
    </title>
  </head>
  <body>
    <?php

      $S = 201.45; // m^2 - square
      $b_a = 5.285; // m - average aerodynamic wing chord
      //$l = 37.55; // m - wingspan
      $G[1] = 73000; // kg - weight before cargo dropping
      $G[2] = 68000; // kg - weight after cargo dropping
      $al[1] = 0.24; // pers AEwC - alignment before cargo dropping
      $al[3] = 0.30; // pers AEwC - alignment in the moment of cargo dropping
      $al[2] = 0.24; // pers AEwC - alignment after cargo dropping
      //$I_x = 250000; // kg * m * s^2 - cross moment of inertia
      //$I_y = 900000; // kg * m * s^2 - roadway moment of inertia
      $I_z[1] = 660000; // kg * m * s^2 - lengthwise moment of inertia before cargo dropping
      $I_z[2] = 650000; // kg * m * s^2 - lengthwise moment of inertia after cargo dropping

      $V = 97.2; // m per s - speed
      $H_0 = 600; // m - height
      $H_set = 610; // m - height
      $pr = 0.119; // (kg * s^2) per m^2 - pressure
      $An = 338.36; // m per s - sound velocity
      $g = 9.81; // m per s^2 - gravitational acceleration

      $C_x = 0.13;

      $C_y0 = -0.255;
      $C_y_A = 5.78;
      $C_y_Dv = 0.2865;

      /*$C_z_B = -0.8136;
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
      $m_y_De = 0;*/

      $m_z0 = 0.2;
      $m_z_vWz = -13;
      $m_z_vA = -3.8;
      $m_z_A = -1.83;
      $m_z_Dv = -0.96;

      $k_H = 0.1;
      $k_DH = 0.5;
      $k_Wz = 1.0;
      $k_pitch = 1.0;
      $k_integral = 0.002;
      $T_1 = 20;
      $T_2 = 20;
      $Pitch_0 = 0;
      $Alpha_cargo = 0.3; // m per s^2
      $L_cabin = 10; // m

      $graph_data = array_fill(0, 6, array());

      for($control_regulation = 1; $control_regulation <= 5; $control_regulation++) {

        $t = 0; // s - flight time
        $td = 0; // s - output time
        $tf = 90.01; // s - flight end time
        $dt = 0.01; // 1 per s - integration step
        $dd = 1; // s - output time

        $X = array_fill(1, 11, 0);
        $Y = array_fill(1, 11, 0);
        $cargo_position = 1;
        $integrate_count = 10;

        $Y[5] = $H_0;
        $Dv = 0;
        $Dvd = 0;
        $Sigma = 0;
        $DH = 0;
        $DPitch = 0;
        $A_full = 0;
        $Dv_full = 0;
        $DA_bal = 0;
        $DDv_bal = 0;
        $Dal = 0;
        $al_bal = 24;

        $m[1] = $G[1] / $g; // N - Weight
        $m[2] = $G[2] / $g;
        $C_ybal[1] = (2 * $G[1]) / ($S * $pr * pow($V, 2));
        $C_ybal[2] = (2 * $G[2]) / ($S * $pr * pow($V, 2));
        $A_bal[1] = 57.3 * (($C_ybal[1] - $C_y0) / $C_y_A);
        $A_bal[2] = 57.3 * (($C_ybal[2] - $C_y0) / $C_y_A);
        $Dv_bal[1] = -57.3 * (($m_z0 + (($m_z_A * $A_bal[1]) / 57.3) + $C_ybal[1] * (($al[1]) - 0.24)) / $m_z_Dv);
        $Dv_bal[2] = -57.3 * (($m_z0 + (($m_z_A * $A_bal[2]) / 57.3) + $C_ybal[2] * (($al[2]) - 0.24)) / $m_z_Dv);
        $C_y = $C_ybal[1] + ($C_y_A * (0 / 57.3)) + ($C_y_Dv * ($Dv / 57.3));
        $k_al = ($al[3] - $al[2]) / $L_cabin;

        $c[1] = -($m_z_vWz / $I_z[$cargo_position]) * $S * pow($b_a, 2) * (($pr * pow($V, 1) / 2));
        $c[2] = -($m_z_A / $I_z[$cargo_position]) * $S * pow($b_a, 1) * (($pr * pow($V, 2) / 2));
        $c[3] = -($m_z_Dv / $I_z[$cargo_position]) * $S * pow($b_a, 1) * (($pr * pow($V, 2) / 2));
        $c[4] = (($C_y_A + $C_x) / $m[$cargo_position]) * $S * (($pr * pow($V, 1) / 2));
        $c[5] = -($m_z_vA / $I_z[$cargo_position]) * $S * pow($b_a, 2) * (($pr * pow($V, 1) / 2));
        $c[6] = $V / 57.3;
        $c[9] = ($C_y_Dv / $m[$cargo_position]) * $S * (($pr * pow($V, 1) / 2));
        $c[16] = $V / (57.3 * $g);
        $c[20] = 57.3 * $C_y * $S * $b_a * (($pr * pow($V, 2)) / (2 * $I_z[$cargo_position]));     
        echo "c$cargo_position: ";
        var_dump($c);
        echo "<br/>";

        if($control_regulation == 5) {
          echo "<h2>Control regulation rule " . 6 . "</h2>";
        } else {
          echo "<h2>Control regulation rule $control_regulation</h2>";
        }
        echo
          "<table width=\"100%\" cellspacing=\"0\" border=\"1\">
          <tr>
            <th>T</th>
            <th>Dv</th>
            <th>TANG</th>
            <th>ALPHA</th>
            <th>H</th>
            <th>Ny</th>
            <th>Al_bal</th>
          </tr>";

        for($t; $t <= $tf; $t += $dt){

          $Dvd = $k_Wz * $Y[2];
          switch($control_regulation) {
            case 1: {
              $Sigma = $k_H * $DH; // 1
              break;
            }
            case 2: {
             // $DH -= 8.9;
              $Sigma = $k_H * $DH + $k_DH * $X[5]; // 2
              break;
            }
            case 3: {
              //$DH -= 14;
              $Sigma = $k_H * $DH + $k_pitch * $DPitch; // 3
              break;
            }
            case 4: {
              //$DH -= 8.9;
              $Sigma = $k_H * $DH + $X[7]; // 4
              break;
            }
            case 5: {
              $Sigma = $X[8] + ($Y[8] / $T_2); // 6
              break;
            }
          }
        
          $Dv = $Sigma + $Dvd;
          //$Dv = -5;
          $DH = $Y[5] - $H_set;
          $DPitch = $Y[1] - $Pitch_0;
          $c[1] = -($m_z_vWz / $I_z[$cargo_position]) * $S * pow($b_a, 2) * (($pr * pow($V, 1) / 2));
          $c[2] = -($m_z_A / $I_z[$cargo_position]) * $S * pow($b_a, 1) * (($pr * pow($V, 2) / 2));
          $c[3] = -($m_z_Dv / $I_z[$cargo_position]) * $S * pow($b_a, 1) * (($pr * pow($V, 2) / 2));
          $c[4] = (($C_y_A + $C_x) / $m[$cargo_position]) * $S * (($pr * pow($V, 1) / 2));
          $c[5] = -($m_z_vA / $I_z[$cargo_position]) * $S * pow($b_a, 2) * (($pr * pow($V, 1) / 2));
          $c[6] = $V / 57.3;
          $c[9] = ($C_y_Dv / $m[$cargo_position]) * $S * (($pr * pow($V, 1) / 2));
          $c[16] = $V / (57.3 * $g);
          $C_y = $C_ybal[$cargo_position] + ($C_y_A * ($Y[4] / 57.3)) + ($C_y_Dv * ($Dv / 57.3));
          $c[20] = 57.3 * $C_y * $S * $b_a * (($pr * pow($V, 2)) / (2 * $I_z[$cargo_position]));
          
          $X[1] = $Y[2];
          $X[2] = -($c[1] * $Y[2]) - ($c[2] * $A_full) - ($c[5] * $X[4]) - ($c[3] * $Dv_full) + ($c[20] * $Dal); // tangaj
          $X[3] = ($c[4] * $A_full) + ($c[9] * $Dv_full);
          $X[4] = $X[1] - $X[3];
          $A_full = $Y[4] + $DA_bal;
          $Dv_full = $Dv + $DDv_bal;
          $X[5] = $c[6] * $Y[3];
          $Ny = $c[16] * $X[3];
          $X[6] = 0;
          $X[7] = $k_pitch * $DPitch - ($Y[7] / $T_1);
          $X[8] = $k_H * $DH + $k_DH * $X[5];

          if($t >= 2 ) {
            if($cargo_position == 1 && $Y[9] <= $L_cabin) {
              $X[9] = $Y[10];
              $X[10] = $Alpha_cargo;
              $DA_bal = $A_bal[1] - $A_bal[2];
              $DDv_bal = $Dv_bal[1] - $Dv_bal[2];
              $Dal = $k_al * $Y[9];
              $al_bal = 30;
            } elseif($cargo_position == 1 && $Y[9] > $L_cabin) {
              $cargo_position = 2;
              $integrate_count = 8;
              //$DA_bal = 0;
              //$DDv_bal = 0;
              $Dal = 0;
              $al_bal = 24;
            }
          }

          for($i = 1; $i <= $integrate_count; $i++){
            $Y[$i] += $X[$i] * $dt;
          }

          for($t; $t >= $td; $td += $dd){
            array_push($graph_data[$control_regulation], ["time" => $td, "H" => $Y[5]]);
              echo  "<tr>
              <td>" . number_format($td, 2, '.', ' ') . "</td>
              <td>" . number_format($Dv, 4, '.', ' ') . "</td>
              <td>" . number_format($Y[1], 4, '.', ' ') . "</td>
              <td>" . number_format($Y[4], 4, '.', ' ') . "</td>
              <td>" . number_format($Y[5], 4, '.', ' ') . "</td>
              <td>" . number_format($Ny, 4, '.', ' ') . "</td>
              <td>" . number_format($al_bal, 4, '.', ' ') ./* "</td>
              <td>" . number_format($Y[9], 4, '.', ' ') .*/ "</td>
              </tr>";
          }
        }
        echo "</table><br/>";   
            
        $graph_data_file = 'data' . $control_regulation . '.json';
        $handle = fopen($graph_data_file, 'w') or die ('Cannot open file: ' . $graph_data_file);
        $graph_content = json_encode($graph_data[$control_regulation]);
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
      <div id = "chart_div_d" style = "width: 1000px; height: 500px">
      </div>
      <script language = "JavaScript">

        function chart_div_a() {

          var data = new google.visualization.DataTable();
          data.addColumn('number', 'flight time');
          data.addColumn('number', '1 rule');
          data.addColumn('number', '2 rule');
          data.addColumn('number', '3 rule');
          data.addColumn('number', '4 rule');
          data.addColumn('number', '6 rule');
          data.addRows([
            <?php
              $a = file_get_contents("./data1.json");
              $a1 = file_get_contents("./data2.json");
              $a2 = file_get_contents("./data3.json");
              $a3 = file_get_contents("./data4.json");
              $a4 = file_get_contents("./data5.json");
              $json_a = json_decode($a, true);
              $json_a1 = json_decode($a1, true);
              $json_a2 = json_decode($a2, true);
              $json_a3 = json_decode($a3, true);
              $json_a4 = json_decode($a4, true);

              for ($i = 0; $i <= (count($json_a)-1); $i++) {
                echo "["
                . $json_a[$i]['time'] . ",  "
                . $json_a[$i]['H'] . ", "
                . $json_a1[$i]['H'] . ", "
                . $json_a2[$i]['H'] . ", "
                . $json_a3[$i]['H'] . ", "
                . $json_a4[$i]['H']
                . "],";
              }
            ?>
          ]);

          var options = {
            'title' : 'H',
            'width': 1400,
            'height': 700,
            curveType: 'function',
            colors: ['purple', 'orange', 'green', 'blue', 'red']
          };

          var chart = new google.visualization.LineChart(document.getElementById('chart_div_a'));

          chart.draw(data, options);
        }
        google.charts.setOnLoadCallback(chart_div_a);

      </script>
   </body>
</html>
    
        

