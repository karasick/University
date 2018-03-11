<html>
  <head>
    <title>
      Lab 2_1
    </title>
    <link rel="icon"
          type = "image/png"
          href = "./plane.png"
    />
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
        $dd = 0.1; // s - output time

        echo "<h3 aling=\"left\"> Dempfer value = $demper.</h3>";

        $X = array (1 => 0);
        $Y = array (1 => 0);

        $X[1] = 0;
        $X[2] = 0;
        $X[3] = 0;
        $X[4] = 0;
        $X[5] = 0;

        $Y[1] = 0;
        $Y[2] = 0;
        $Y[3] = 0;
        $Y[4] = 0;
        $Y[5] = 0;

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
        echo "</table>";

        $graph_dat_file = 'data' . $demper . '.json';
        $handle = fopen($graph_dat_file, 'w') or die ('Cannot open file: ' . $graph_dat_file);
        $graph_content = json_encode($graph_dat[$demper]);
        fwrite($handle, $graph_content);
      }
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
          height: 350,
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