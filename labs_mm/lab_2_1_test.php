<?php
require "C:\\xampp\htdocs\php\helpers.php";
Page::TopHead("Lab 2_1");

$S = 201.45; // m^2 - square
$b_a = 5.285; // m - average aerodynamic wing chord
$G = 73000; // kg - weight
$al = 24; // pers AEwC -  alignment (tsentrovka)
$Iz = 660000; // kg * m * s^2 - lengthwise moment of inertia

#$v0 = 190.0; // m per s - speed
#$h = 6400; // m - height
#$pr = 0.0636; // (kg * s^2) per m^2 - pressure
#$An = 314.34; // m per s - ?

$v0 = 250.0; // m per s - speed
$h = 11300; // m - height
$pr = 0.0372; // (kg * s^2) per m^2 - pressure
$An = 295.06; // m per s - ?

$g = 9.81; // m per s^2 - gravitational acceleration

$t = 0; // s - flight time
$td = 0; // s - output time
$tf = 20.0001; // s - flight end time
$dt = 0.01; // 1 per s - integration step
$dd = 0.5; // s - output time

#$C_y0 = -0.280; // - aerodynamic characteristic of ?
#$C_A_y = 5.90; // - aerodynamic characteristic of ?
#$C_Dv_y = 0.2865; // - aerodynamic characteristic of ?
#$C_x = 0.033; // - aerodynamic characteristic of ?
#$M_z0 = 0.22; // - ?
#$M_vWz_z = -13.4; // - ?
#$M_vA_z = -4.0; // - ?
#$M_A_z = -1.95; // - ?
#$M_Dv_z = -0.92;  // - ?

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

//echo
#"<table width=\"100%\" cellspacing=\"0\" border=\"1\">
# <tr>
#  <th rowspan=\"2\">C_Ybal</th>
#  <th rowspan=\"2\">A_bal</th>
#  <th colspan=\"2\">D_vbal</th>
#  <th colspan=\"2\">t_ppa</th>
#  <th colspan=\"2\">T_a</th>
#  <th colspan=\"3\">D_ny_v</th>
# </tr>
# <tr>
#  <th>analyt design</th>
#  <th>real obj</th>
#  <th>model</th>
#  <th>real obj</th>
#  <th>model</th>
#  <th>real obj</th>
#  <th>analyt design</th>
#  <th>model</th>
#  <th>real obj</th>
# </tr>
# <tr>
#  <td>0</td>
#  <td>1</td>
#  <td>2</td>
#  <td>3</td>
#  <td>4</td>
#  <td>5</td>
#  <td>6</td>
#  <td>7</td>
#  <td>8</td>
#  <td>9</td>
#  <td>10</td>
# </tr>
#</table>";

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

echo "<h3 aling=\"left\"> Dempfer value = 0.</h3>";

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

$N_y = 0;
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

 $graph_dat = [];

for($t; $t <= $tf; $t+=$dt){

    $X[1] = $Y[2];
    $X[2] = -$C[1] * $Y[2] - $C[2] * $Y[4] - $C[5] * $X[4] - $C[3] * $dv;
    $X[3] = $C[4] * $Y[4] + $C[9] * $dv;
    $X[4] = $X[1] - $X[3];
    $N_y = $C[16] * $X[3];

    for($i = 1; $i <= 4; $i++){
        $Y[$i] += $X[$i] * $dt;
    }

    $mode = 0;

    switch($mode){
        case 0:
        $dvd = 0;
        break;
        case 1:
        $dvd = $k_wz * $Y[2];
        break;
        case 2:
        $X[5] = $dvd;
        $Y[5] += $X[5] * $dt;
        $dvd = $k_wz * $Y[2] - ($Y[5]/$T_wz);
        break;
    }

    $dvs = $ks * $xv;
    $dv = $dvs + $dvd;

    for($t; $t >= $td; $td+=$dd){
      array_push($graph_dat, ["t"=>$t, "alfa"=>$Y[4], "tang"=>$Y[1], "N_y"=>$N_y ]);
        echo  "<tr>
        <td>" . number_format($t, 1, '.', ' ') . "</td>
        <td>$xv</td>
        <td>" . number_format($dv, 4, '.', ' ') . "</td>";
        //if($t == 0){
        //    echo
        //    "<td>0.0000</td>
        //     <td>0.0000</td>
        //     <td>0.0000</td>
        //     </tr>";
        //}
        //else{
            echo
            "<td>" . number_format($Y[4], 4, '.', ' ') . "</td>
             <td>" . number_format($Y[1], 4, '.', ' ') . "</td>
             <td>" . number_format($N_y, 4, '.', ' ') . "</td>
             </tr>";
        //}
    }
}
echo "</table>";

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$t = 0;
$td = 0;
$tf = 20.0001;
$dt = 0.01;
$dd = 0.5;

echo "<h3 aling=\"left\"> Dempfer value = 1.</h3>";

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

$N_y = 0;
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

 $graph_dat_1 = [];

for($t; $t <= $tf; $t+=$dt){

    $X[1] = $Y[2];
    $X[2] = -$C[1] * $Y[2] - $C[2] * $Y[4] - $C[5] * $X[4] - $C[3] * $dv;
    $X[3] = $C[4] * $Y[4] + $C[9] * $dv;
    $X[4] = $X[1] - $X[3];
    $N_y = $C[16] * $X[3];

    for($i = 1; $i <= 4; $i++){
        $Y[$i] += $X[$i] * $dt;
    }

    $mode = 1;

    switch($mode){
        case 0:
        $dvd = 0;
        break;
        case 1:
        $dvd = $k_wz * $Y[2];
        break;
        case 2:
        $X[5] = $dvd;
        $Y[5] += $X[5] * $dt;
        $dvd = $k_wz * $Y[2] - ($Y[5]/$T_wz);
        break;
    }

    $dvs = $ks * $xv;
    $dv = $dvs + $dvd;

    for($t; $t >= $td; $td+=$dd){
      array_push($graph_dat_1, ["t"=>$t, "alfa"=>$Y[4], "tang"=>$Y[1], "N_y"=>$N_y ]);
        echo  "<tr>
        <td>" . number_format($t, 1, '.', ' ') . "</td>
        <td>$xv</td>
        <td>" . number_format($dv, 4, '.', ' ') . "</td>";
            echo
            "<td>" . number_format($Y[4], 4, '.', ' ') . "</td>
             <td>" . number_format($Y[1], 4, '.', ' ') . "</td>
             <td>" . number_format($N_y, 4, '.', ' ') . "</td>
             </tr>";
    }
}
echo "</table>";

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$t = 0;
$td = 0;
$tf = 20.0001;
$dt = 0.01;
$dd = 0.5;

echo "<h3 aling=\"left\"> Dempfer value = 2.</h3>";

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

$N_y = 0;
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

 $graph_dat_2 = [];

for($t; $t <= $tf; $t+=$dt){

    $X[1] = $Y[2];
    $X[2] = -$C[1] * $Y[2] - $C[2] * $Y[4] - $C[5] * $X[4] - $C[3] * $dv;
    $X[3] = $C[4] * $Y[4] + $C[9] * $dv;
    $X[4] = $X[1] - $X[3];
    $N_y = $C[16] * $X[3];

    for($i = 1; $i <= 4; $i++){
        $Y[$i] += $X[$i] * $dt;
    }

    $mode = 2;

    switch($mode){
        case 0:
        $dvd = 0;
        break;
        case 1:
        $dvd = $k_wz * $Y[2];
        break;
        case 2:
        $X[5] = $dvd;
        $Y[5] += $X[5] * $dt;
        $dvd = $k_wz * $Y[2] - ($Y[5]/$T_wz);
        break;
    }

    $dvs = $ks * $xv;
    $dv = $dvs + $dvd;

    for($t; $t >= $td; $td+=$dd){
      array_push($graph_dat_2, ["t"=>$t, "alfa"=>$Y[4], "tang"=>$Y[1], "N_y"=>$N_y ]);
        echo  "<tr>
        <td>" . number_format($t, 1, '.', ' ') . "</td>
        <td>$xv</td>
        <td>" . number_format($dv, 4, '.', ' ') . "</td>";
            echo
            "<td>" . number_format($Y[4], 4, '.', ' ') . "</td>
             <td>" . number_format($Y[1], 4, '.', ' ') . "</td>
             <td>" . number_format($N_y, 4, '.', ' ') . "</td>
             </tr>";
    }
}
echo "</table>";

//
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//

$graph_dat_file = 'data.json';
$handle = fopen($graph_dat_file, 'w') or die ('Cannot open file: ' . $graph_dat_file);
$graph_content = json_encode($graph_dat);
fwrite($handle, $graph_content);

$graph_dat_file_1 = 'data1.json';
$handle_1 = fopen($graph_dat_file_1, 'w') or die ('Cannot open file: ' . $graph_dat_file1);
$graph_content_1 = json_encode($graph_dat_1);
fwrite($handle_1, $graph_content_1);

$graph_dat_file_2 = 'data2.json';
$handle_2 = fopen($graph_dat_file_2, 'w') or die ('Cannot open file: ' . $graph_dat_file2);
$graph_content_2 = json_encode($graph_dat_2);
fwrite($handle_2, $graph_content_2);
?>

<html>
  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
    google.charts.load('current', {'packages':['line']});
    google.charts.setOnLoadCallback(drawChart_a);
    google.charts.setOnLoadCallback(drawChart_b);
    google.charts.setOnLoadCallback(drawChart_c);

    function drawChart_a() {

      var data = new google.visualization.DataTable();
      data.addColumn('number', 'Flight time');
      data.addColumn('number', 'Damper mode 1');
      data.addColumn('number', 'Damper mode 2');
      data.addColumn('number', 'Damper mode 3');

      data.addRows([
        <?php
        $c = file_get_contents("./data.json");
        $c1 = file_get_contents("./data1.json");
        $c2 = file_get_contents("./data2.json");
        $file = json_decode($c, true);
        $file1 = json_decode($c1, true);
        $file2 = json_decode($c2, true);

        for ($i = 0; $i <= (count($file)-1); $i++) {
          echo "["
          . $file[$i]['t'] . ",  "
          . $file[$i]['alfa'] . ", "
          . $file1[$i]['alfa'] . ", "
          . $file2[$i]['alfa']
          . "],";
        }
        ?>
      ]);

      var options = {
        chart: {
          title: 'Angle of Attack',
          subtitle: 'For 3 different damper modes'
        },
        width: 900,
        height: 500,
        //colors: ['red', 'green', 'cyan']
      };

      var chart = new google.charts.Line(document.getElementById('chart_a_div'));

      chart.draw(data, google.charts.Line.convertOptions(options));
    }

    function drawChart_b() {

      var data = new google.visualization.DataTable();
      data.addColumn('number', 'Flight time');
      data.addColumn('number', 'Damper mode 1');
      data.addColumn('number', 'Damper mode 2');
      data.addColumn('number', 'Damper mode 3');

      data.addRows([
        <?php
        $c = file_get_contents("./data.json");
        $c1 = file_get_contents("./data1.json");
        $c2 = file_get_contents("./data2.json");
        $file = json_decode($c, true);
        $file1 = json_decode($c1, true);
        $file2 = json_decode($c2, true);

        for ($i = 0; $i <= (count($json_a)-1); $i++) {
          echo "["
          . $file[$i]['t'] . ",  "
          . $file[$i]['tang'] . ", "
          . $file1[$i]['tang'] . ", "
          . $file2[$i]['tang']
          . "],";
        }
        ?>
      ]);

      var options = {
        chart: {
          title: 'Pitch Attitude Angle',
          subtitle: 'For 3 different damper modes'
        },
        width: 900,
        height: 500,
        //colors: ['red', 'green', 'cyan']
      };

      var chart = new google.charts.Line(document.getElementById('chart_b_div'));

      chart.draw(data, google.charts.Line.convertOptions(options));
    }

    function drawChart_c() {

      var data = new google.visualization.DataTable();
      data.addColumn('number', 'Flight time');
      data.addColumn('number', 'Damper mode 1');
      data.addColumn('number', 'Damper mode 2');
      data.addColumn('number', 'Damper mode 3');

      data.addRows([
        <?php
        $c = file_get_contents("./data.json");
        $c1 = file_get_contents("./data1.json");
        $c2 = file_get_contents("./data2.json");
        $file = json_decode($c, true);
        $file1 = json_decode($c1, true);
        $file2 = json_decode($c2, true);

        for ($i = 0; $i <= (count($json_a)-1); $i++) {
          echo "["
          . $file[$i]['t'] . ",  "
          . $file[$i]['N_y'] . ", "
          . $file1[$i]['N_y'] . ", "
          . $file2[$i]['N_y']
          . "],";
        }
        ?>
      ]);

      var options = {
        chart: {
          title: 'Vertical G',
          subtitle: 'For 3 different damper modes'
        },
        width: 900,
        height: 500,
        //colors: ['red', 'green', 'cyan']
      };

      var chart = new google.charts.Line(document.getElementById('chart_c_div'));

      chart.draw(data, google.charts.Line.convertOptions(options));
    }

    </script>
 </head>
<body>
  <div id="chart_a_div"></div>
  <div id="chart_b_div"></div>
  <div id="chart_c_div"></div>
</body>
</html>
