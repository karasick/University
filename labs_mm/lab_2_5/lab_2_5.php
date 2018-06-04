<html>
  <head>
    <title>
      Lab 2_5
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

      //$k_n = 0.1;
      //$k_dn = 0.4;
      //$k_Wx = 1.5;
      //$k_Wy = 2.5;
      $k_Wz = 1.0;
      $k_st0 = 0.112;
      $k_p = 20;
      //$T_Wx = 1.6;
      //$T_Wy = 2.5;

      $C_ybal = (2 * $G) / ($S * $pr * pow($V, 2));
      $A_bal = 57.3 * (($C_ybal - $C_y0) / $C_y_A);
      $Dv_bal = -57.3 * (($m_z0 + (($m_z_A * $A_bal) / 57.3) + $C_ybal * (($al) - 0.24)) / $m_z_Dv);
      $X_st_bal = $Dv_bal / $k_st0;

      $O_ny = ($m_z_A / $C_y_A) + (($pr * $S * $b_a) / (2 * $m)) * $m_z_vWz;
      $Dv_ny = -57.3 * $O_ny * ($C_ybal / $m_z_Dv);

      $F1_min = -29 - $Dv_bal;
      $F1_max = 16 - $Dv_bal;
      $F2_min = -250 - $X_st_bal;
      $F2_max = 156 - $X_st_bal;

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

      echo "C_ybal: ";
      var_dump($C_ybal);
      echo "<br/>";

      echo "A_bal: ";
      var_dump($A_bal);
      echo "<br/>";

      echo "Dv_bal: ";
      var_dump($Dv_bal);
      echo "<br/>";

      echo "X_st_bal: ";
      var_dump($X_st_bal);
      echo "<br/>";

      echo "Dv_ny: ";
      var_dump($Dv_ny);
      echo "<br/>";

      $graph_data = array_fill(0,3,array());

      for($turbulence_intensity = 0; $turbulence_intensity < 1; $turbulence_intensity++){

        $t = 0; // s - flight time
        $td = 0; // s - output time
        $tf = 20.01; // s - flight end time
        $dt = 0.01; // 1 per s - integration step
        $dd = 0.5; // s - output time

        //echo "<h3 aling=\"left\"> Turbulence intensity = " . $Sigma_Wy[$turbulence_intensity] . "</h3>";

        //$X = array (1 => 0);
        //$Y = array (1 => 0);

        $X = array_fill(1,10,0);
        $Y = array_fill(1,10,0);

        $Dv = 0;
        $Dvd = 0;
        $Dvs = 0;
        $H_set = 0;
        $Pitch_set = 5;
        $tau_l = 0.15; // латентное время реакции
        $T1 = 1.1;
        $T2 = 1.0;
        $T3 = 0.15;
        $k_x = 8;
        $Y[8] = -17.86;
        $tau_time = $tau_l;
        $dPitch = 0;
        $X[6] = $dPitch;
        $U1 = 0;
        $U2 = 0;

        echo
        "<table width=\"100%\" cellspacing=\"0\" border=\"1\">
        <tr>
          <th>T</th>
          <th>dX_st</th>
          <th>Dv</th>
          <th>TANG</th>
          <th>H</th>
          <th>Ny</th>
        </tr>";

        for($t; $t <= $tf; $t += $dt){

          ///SHU
            //$Y[8] =  -17.86;
            $Dvd = $k_Wz * $Y[2];
            $Dvs = $k_st0 * $Y[8];
            $Dv = $Dvs + $Dvd;
            //$Dv = $k_st0 * (1 - $k_x) * $dX_st + $k_Wz * $Y[2]; 
            if($Dv < $F1_min) {
              $Dv = $F1_min;
            } elseif($Dv > $F1_max) {
              $Dv = $F1_max;
            }
            
            $X[1] = $Y[2]; // wz kut nahilu trajectorii
            $X[2] = -$o[1] * $Y[2] - $o[2] * $Y[4] - $o[5] * $X[4] - $o[3] * $Dv; // tangaj
            $X[3] = $o[4] * $Y[4] + $o[9] * $Dv; // kut
            $X[4] = $X[1] - $X[3]; // kut ataki
            $X[5] = $o[6] * $Y[3]; // visotaa
            $Ny = $o[16] * $X[3];

            $dPitch = $Y[1] - $Pitch_set;

            ///Pilot

            //$X[6] = ($dPitch - $Y[6]) / (0.5 * $tau_l); // Блок чистого запізнення
            if($tau_time < $t) {                          //
              //$X[6] = $dPitch;                          //
              $U1 = $dPitch;                              //
              $tau_time += $tau_l;                        // W 1
            } else {                                      //
              $U1 = $dPitch;                              //
            }                                             //

            //$X[7] = ($X[6] * $k_p * $T1 + $Y[6] * $k_p - $Y[7]) / $T2;  // Корегувальний блок
            //$X[7] = ($k_p * $T1 * $Y[6] - $Y[7]) / $T2;                 //
            //$X[8] = ($k_p * $Y[6] - $Y[8]) / $T2;                       // W 2
            //$X[9] = $X[7] + $X[8];                                      //
            $X[6] = ($k_p * $T1 * $U1 - $Y[7]) / $T2;
            $X[7] = ($k_p * $U1 - $Y[8]) / $T2;
            $U2 = $X[6] + $Y[7];

            $X[8] = ($U2 - $Y[8]) / $T3; // W 3 Нейро-м'язовий блок

            $dX_st = $X_st_bal - $Y[8]; // відхилення від балансованого значення
            if($dX_st < $F2_min) {
              $dX_st = $F2_min;
            } elseif($dX_st > $F2_max) {
              $dX_st = $F2_max;
            }
            //$k_x = ($X_st_bal - 20) / 120;

            for($i = 1; $i <= 10; $i++){
                $Y[$i] += $X[$i] * $dt;
            }

            for($t; $t >= $td; $td += $dd){
              array_push($graph_data[$turbulence_intensity], ["time"=>$td, "alpha"=>$Y[4], "tang"=>$Y[1], "n_y"=>$Ny]);
                echo  "<tr>
                <td>" . number_format($td, 2, '.', ' ') . "</td>
                <td>" . number_format($dX_st, 4, '.', ' ') . "</td>
                <td>" . number_format($Dv, 4, '.', ' ') . "</td>
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

        unset($X); unset($Y);
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
          data.addColumn('number', '1');
          data.addRows([
            <?php
              $a = file_get_contents("./data0.json");
              $json_a = json_decode($a, true);

              for ($i = 0; $i <= (count($json_a)-1); $i++) {
                echo "["
                . $json_a[$i]['time'] . ",  "
                . $json_a[$i]['alpha']
                . "],";
              }
            ?>
          ]);

          var options = {
            'title' : 'Alpha',
            'width': 1000,
            'height': 500,
            curveType: 'function',
            colors: ['red']
          };

          var chart = new google.visualization.LineChart(document.getElementById('chart_div_a'));

          chart.draw(data, options);
        }
        google.charts.setOnLoadCallback(chart_div_a);

        function chart_div_b() {

          var data = new google.visualization.DataTable();
          data.addColumn('number', 'flight time');
          data.addColumn('number', '1');
          data.addRows([
            <?php
              $a = file_get_contents("./data0.json");
              $json_a = json_decode($a, true);

              for ($i = 0; $i <= (count($json_a)-1); $i++) {
                echo "["
                . $json_a[$i]['time'] . ",  "
                . $json_a[$i]['tang']
                . "],";
              }
            ?>
          ]);

          var options = {
            'title' : 'Tang',
            'width': 1000,
            'height': 500,
            curveType: 'function',
            colors: ['green']
          };

          var chart = new google.visualization.LineChart(document.getElementById('chart_div_b'));

          chart.draw(data, options);
        }
        google.charts.setOnLoadCallback(chart_div_b);

        function chart_div_c() {

          var data = new google.visualization.DataTable();
          data.addColumn('number', 'flight time');
          data.addColumn('number', '1 damper');
          data.addRows([
            <?php
              $a = file_get_contents("./data0.json");
              $json_a = json_decode($a, true);

              for ($i = 0; $i <= (count($json_a)-1); $i++) {
                echo "["
                . $json_a[$i]['time'] . ",  "
                . $json_a[$i]['n_y']
                . "],";
              }
            ?>
          ]);

          var options = {
            'title' : 'Ny',
            'width': 1000,
            'height': 500,
            curveType: 'function',
            colors: ['orange']
          };

          var chart = new google.visualization.LineChart(document.getElementById('chart_div_c'));

          chart.draw(data, options);
        }
        google.charts.setOnLoadCallback(chart_div_c);

      </script>
   </body>
</html>