<html>
  <head>
    <title>
      Lab 2_1
    </title>
  </head>

  <body>
    <?php

      $S = 201.45; // m^2 - square
      $b_a = 5.285; // m - average aerodynamic wing chord
      $G = 73000; // kg - weight
      $al = 24; // pers AEwC -  alignment (tsentrovka)
      $Iz = 660000; // kg * m * s^2 - lengthwise moment of inertia

      $v0 = 250.0; // m per s - speed
      $h = 11300; // m - height
      $pr = 0.0372; // (kg * s^2) per m^2 - pressure
      $An = 295.06; // m per s - ?

      $g = 9.81; // m per s^2 - gravitational acceleration

      $C_y0 = -0.320; // - aerodynamic characteristic of ?
      $C_A_y = 6.30; // - aerodynamic characteristic of ?
      $C_Dv_y = 0.2635; // - aerodynamic characteristic of ?
      $C_x = 0.031; // - aerodynamic characteristic of ?
      $M_z0 = 0.27; // - ?
      $M_vWz_z = -15.5; // - ?
      $M_vA_z = -5.2; // - ?
      $M_A_z = -2.69; // - ?
      $M_Dv_z = -0.92;  // - ?

      $k_wz = 1.0; // 1 per s - coefficient
      $T_wz = 0.7; // s - transmitting function
      $ks = 0.112;
      $xv = -17.86;

      $m = $G / $g;
      $C_ybal = (2 * $G) / ($S * $pr * pow($v0, 2));
      $A_bal = 57.3 * (($C_ybal - $C_y0) / $C_A_y);
      $Dv_bal = -57.3 * (($M_z0 + (($M_A_z * $A_bal) / 57.3) + $C_ybal * (($al / 100) - 0.24)) / $M_Dv_z);
      $O_ny = ($M_A_z / $C_A_y) + (($pr * $S * $b_a) / (2 * $m)) * $M_vWz_z;
      $Dv_ny = -57.3 * $O_ny * ($C_ybal / $M_Dv_z);

      $C = array(1 => 0);

      $C[1] = (- ($M_vWz_z / $Iz)) * $S * pow($b_a, 2) * (($pr * $v0) / 2);
      $C[2] = (- ($M_A_z / $Iz)) * $S * $b_a * (($pr * pow($v0, 2)) / 2);
      $C[3] = (- ($M_Dv_z / $Iz)) * $S * $b_a * (($pr * pow($v0, 2)) / 2);
      $C[4] = ( (($C_A_y + $C_x) / $m)) * $S * (($pr * $v0) / 2);
      $C[5] = (- ($M_vA_z / $Iz)) * $S * pow($b_a, 2) * (($pr * $v0) / 2);
      $C[9] = ( (($C_Dv_y) / $m)) * $S * (($pr * $v0) / 2);
      $C[16] = $v0 / (57.3 * $g);

      echo "<p>" . var_dump($C) . "</p>";

      $graph_dat = array_fill(1,3,array());

      for($demper = 1; $demper <= 3; $demper++){

        $t = 0; // s - flight time
        $td = 0; // s - output time
        $tf = 20.001; // s - flight end time
        $dt = 0.01; // 1 per s - integration step
        $dd = 0.5; // s - output time

        echo "<h3 aling=\"left\"> Dempfer value = $demper.</h3>";

        $X = array (1 => 0);
        $Y = array (1 => 0);

        $X[1] = 0; // acceleration Y[1]
        $X[2] = 0; // acceleration Y[2]
        $X[3] = 0; // acceleration Y[3]
        $X[4] = 0; // acceleration Y[4]
        $X[5] = 0; // acceleration Y[5]

        $Y[1] = 0; // pitch angle
        $Y[2] = 0; // pitch angle speed
        $Y[3] = 0; // trajectory inclination 
        $Y[4] = 0; // drive (attack) angle
        $Y[5] = 0; // Dvd (helm inclination by pitch dempfer)

        $n_y = 0;
        $dv = 0;
        $dvd = 0;

        echo
        "<table width=\"100%\" cellspacing=\"0\" border=\"1\">
        <tr>
          <th>T</th>
          <th>XV</th>
          <th>DV</th>
          <th>ALF</th>
          <th>TANG</th>
          <th>N_y</th>
        </tr>";

        for($t; $t <= $tf; $t+=$dt){

            $X[1] = $Y[2];
            $X[2] = -$C[1] * $Y[2] - $C[2] * $Y[4] - $C[5] * $X[4] - $C[3] * $dv;
            $X[3] = $C[4] * $Y[4] + $C[9] * $dv;
            $X[4] = $X[1] - $X[3];
            $n_y = $C[16] * $X[3];

            for($i = 1; $i <= 4; $i++){
                $Y[$i] += $X[$i] * $dt;
            }

            $mode = $demper;

            switch($mode){
                case 1:
                $dvd = 0;
                break;
                case 2:
                $dvd = $k_wz * $Y[2];
                break;
                case 3:
                $X[5] = $dvd;
                $Y[5] += $X[5] * $dt;
                $dvd = $k_wz * $Y[2] - ($Y[5]/$T_wz);
                break;
            }

            $dvs = $ks * $xv;
            $dv = $dvs + $dvd;

            for($t; $t >= $td; $td+=$dd){
              array_push($graph_dat[$demper], ["time"=>$td, "alf"=>$Y[4], "tang"=>$Y[1], "n_y"=>$n_y]);
                echo  "<tr>
                <td>" . number_format($td, 1, '.', ' ') . "</td>
                <td>$xv</td>
                <td>" . number_format($dv, 4, '.', ' ') . "</td>";
                    echo
                    "<td>" . number_format($Y[4], 4, '.', ' ') . "</td>
                    <td>" . number_format($Y[1], 4, '.', ' ') . "</td>
                    <td>" . number_format($n_y, 4, '.', ' ') . "</td>
                    </tr>";
            }
        }
        echo "</table><br/>";

        $graph_dat_file = 'data' . $demper . '.json';
        $handle = fopen($graph_dat_file, 'w') or die ('Cannot open file: ' . $graph_dat_file);
        $graph_content = json_encode($graph_dat[$demper]);
        fwrite($handle, $graph_content);
      }
      echo
      "<table width=\"100%\" cellspacing=\"0\" border=\"1\">
       <tr>
        <th rowspan=\"2\">C_Ybal</th>
        <th rowspan=\"2\">A_bal</th>
        <th colspan=\"2\">D_vbal</th>
        <th colspan=\"2\">t_ppa</th>
        <th colspan=\"2\">T_a</th>
        <th colspan=\"3\">D_ny_v</th>
       </tr>
       <tr>
        <th>analyt design</th>
        <th>real obj</th>
        <th>model</th>
        <th>real obj</th>
        <th>model</th>
        <th>real obj</th>
        <th>analyt design</th>
        <th>model</th>
        <th>real obj</th>
       </tr>
       <tr>
        <td>" . number_format($C_ybal, 2, '.', ' ') . "</td> <td>" . number_format($A_bal, 2, '.', ' ') . "</td> <td>" . number_format($Dv_bal, 2, '.', ' ') . "</td> <td>0</td> <td>6.5</td> <td>2.4</td> <td>3</td> <td>3-4,5</td> <td>" . number_format($Dv_ny, 2, '.', ' ') . "</td> <td>" . number_format(-2 / 0.1941, 2, '.', ' ') . "</td> <td>-11<br/></td>
       </tr>
      </table><br/>";
      echo "<h3>M = " . $v0 / 299.53 . "</h3>";
      echo "<h2>Vi = " . $v0 * sqrt(($pr * 10)/(0.1249)) . "</h2> "; //НУЖНОПРЕОБРАЗОВАТЬ
    ?>

<!--
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
        data.addColumn('number', '1 damper');
        data.addColumn('number', '2 damper');
        data.addColumn('number', '3 damper');

        data.addRows([
        <?php
          $a = file_get_contents("./data1.json");
          $a1 = file_get_contents("./data2.json");
          $a2 = file_get_contents("./data3.json");
          $json_a = json_decode($a, true);
          $json_a1 = json_decode($a1, true);
          $json_a2 = json_decode($a2, true);

          for ($i = 0; $i <= (count($json_a)-1); $i++) {
            echo "["
            . $json_a[$i]['time'] . ",  "
            . $json_a[$i]['alf'] . ", "
            . $json_a1[$i]['alf'] . ", "
            . $json_a2[$i]['alf']
            . "],";
          }
        ?>
        ]);

        var options = {
          chart: {
            title: 'attack angle',
            subtitle: 'for 3 damper modes'
          },
          width: 1000,
          height: 1000,
          colors: ['red', 'green', 'cyan']
        };

        var chart = new google.charts.Line(document.getElementById('chart_div_a'));

          chart.draw(data, google.charts.Line.convertOptions(options));
        }

        function draw_chart_b() {

          var data = new google.visualization.DataTable();
          data.addColumn('number', 'flight time');
          data.addColumn('number', '1 damper');
          data.addColumn('number', '2 damper');
          data.addColumn('number', '3 damper');

          data.addRows([
          <?php
            $a = file_get_contents("./data1.json");
            $a1 = file_get_contents("./data2.json");
            $a2 = file_get_contents("./data3.json");
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
            chart: {
              title: 'pitch angle',
              subtitle: 'for 3 damper modes'
            },
            curveType: 'function',
            width: 1000,
            height: 1000,
            colors: ['red', 'green', 'cyan']
          };

          var chart = new google.charts.Line(document.getElementById('chart_div_b'));

          chart.draw(data, google.charts.Line.convertOptions(options));
        }

        function draw_chart_c() {

          var data = new google.visualization.DataTable();
          data.addColumn('number', 'flight time');
          data.addColumn('number', '1 damper');
          data.addColumn('number', '2 damper');
          data.addColumn('number', '3 damper');

          data.addRows([
          <?php
            $a = file_get_contents("./data1.json");
            $a1 = file_get_contents("./data2.json");
            $a2 = file_get_contents("./data3.json");
            $json_a = json_decode($a, true);
            $json_a1 = json_decode($a1, true);
            $json_a2 = json_decode($a2, true);

            for ($i = 0; $i <= (count($json_a)-1); $i++) {
              echo "["
              . $json_a[$i]['time'] . ",  "
              . $json_a[$i]['n_y'] . ", "
              . $json_a1[$i]['n_y'] . ", "
              . $json_a2[$i]['n_y']
              . "],";
            }
          ?>
          ]);

          var options = {
            chart: {
              title: 'vertical overloading',
              subtitle: 'for 3 damper modes'
            },
            width: 1000,
            height: 1000,
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
    <br/>
-->


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
            // Define the chart to be drawn.
            var data = new google.visualization.DataTable();
            data.addColumn('number', 'flight time');
            data.addColumn('number', '1 damper');
            data.addColumn('number', '2 damper');
            data.addColumn('number', '3 damper');
            data.addRows([
              <?php
                $a = file_get_contents("./data1.json");
                $a1 = file_get_contents("./data2.json");
                $a2 = file_get_contents("./data3.json");
                $json_a = json_decode($a, true);
                $json_a1 = json_decode($a1, true);
                $json_a2 = json_decode($a2, true);

                for ($i = 0; $i <= (count($json_a)-1); $i++) {
                  echo "["
                  . $json_a[$i]['time'] . ",  "
                  . $json_a[$i]['alf'] . ", "
                  . $json_a1[$i]['alf'] . ", "
                  . $json_a2[$i]['alf']
                  . "],";
                }
              ?>
            ]);
            
            // Set chart options
            var options = {
              'title' : 'attack angle',
              'width': 1000,
              'height': 500,
              curveType: 'function',
              colors: ['red', 'green', 'blue']
            };

            // Instantiate and draw the chart.
            var chart = new google.visualization.LineChart(document.getElementById('chart_div_a'));
            chart.draw(data, options);
         }
         google.charts.setOnLoadCallback(chart_div_a);

         function chart_div_b() {
            // Define the chart to be drawn.
            var data = new google.visualization.DataTable();
            data.addColumn('number', 'flight time');
            data.addColumn('number', '1 damper');
            data.addColumn('number', '2 damper');
            data.addColumn('number', '3 damper');
            data.addRows([
              <?php
                $a = file_get_contents("./data1.json");
                $a1 = file_get_contents("./data2.json");
                $a2 = file_get_contents("./data3.json");
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
            
            // Set chart options
            var options = {
              'title' : 'pitch angle', //tangaj
              'width': 1000,
              'height': 500,
              curveType: 'function',
              colors: ['red', 'green', 'blue']
            };

            // Instantiate and draw the chart.
            var chart = new google.visualization.LineChart(document.getElementById('chart_div_b'));
            chart.draw(data, options);
         }
         google.charts.setOnLoadCallback(chart_div_b);

         function chart_div_c() {
            // Define the chart to be drawn.
            var data = new google.visualization.DataTable();
            data.addColumn('number', 'flight time');
            data.addColumn('number', '1 damper');
            data.addColumn('number', '2 damper');
            data.addColumn('number', '3 damper');
            data.addRows([
              <?php
                $a = file_get_contents("./data1.json");
                $a1 = file_get_contents("./data2.json");
                $a2 = file_get_contents("./data3.json");
                $json_a = json_decode($a, true);
                $json_a1 = json_decode($a1, true);
                $json_a2 = json_decode($a2, true);

                for ($i = 0; $i <= (count($json_a)-1); $i++) {
                  echo "["
                  . $json_a[$i]['time'] . ",  "
                  . $json_a[$i]['n_y'] . ", "
                  . $json_a1[$i]['n_y'] . ", "
                  . $json_a2[$i]['n_y']
                  . "],";
                }
              ?>
            ]);
            
            // Set chart options
            var options = {
              'title' : 'vertical overloading', 
              'width': 1000,
              'height': 500,
              curveType: 'function',
              colors: ['red', 'green', 'blue']
            };

            // Instantiate and draw the chart.
            var chart = new google.visualization.LineChart(document.getElementById('chart_div_c'));
            chart.draw(data, options);
         }
         google.charts.setOnLoadCallback(chart_div_c);


      </script>
   </body>
</html>

    <?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      /*$t = 0; // s - flight time
      $td = 0; // s - output time
      $tf = 20.001; // s - flight end time
      $dt = 0.01; // 1 per s - integration step
      $dd = 0.5; // s - output time*/

      #$C_y0 = -0.280; // - aerodynamic characteristic of ?
      #$C_A_y = 5.90; // - aerodynamic characteristic of ?
      #$C_Dv_y = 0.2865; // - aerodynamic characteristic of ?
      #$C_x = 0.033; // - aerodynamic characteristic of ?
      #$M_z0 = 0.22; // - ?
      #$M_vWz_z = -13.4; // - ?
      #$M_vA_z = -4.0; // - ?
      #$M_A_z = -1.95; // - ?
      #$M_Dv_z = -0.92;  // - ?
      
      //$v0 = 190.0; // m per s - speed
      //$h = 6400; // m - height
      //$pr = 0.0636; // (kg * s^2) per m^2 - pressure
      //$An = 314.34; // m per s - ?
    ?> 
  </body>
</html>
