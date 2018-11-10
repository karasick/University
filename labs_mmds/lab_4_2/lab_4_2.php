<?php
include 'html/main_page.html';
ini_set('error_reporting', E_ALL);

  // airplane characteristics
  $S = 201.45; // m^2 - square
  $l = 37.55; // m - wingspan
  $b_a = 5.285; // m - average aerodynamic wing chord
  $al = 0.24; // pers AEwC - alignment
  $G0 = 80000; // kg - weight with fuel
  $G_f0 = 20000; // kg - fuel weight
  $q_eng = 0.585; // kg per s - fuel consumption for one engine
  $I_x = 250000; // kg * m * s^2 - cross moment of inertia
  $I_y = 875000; // kg * m * s^2 - roadway moment of inertia
  $I_z = 660000; // kg * m * s^2 - lengthwise moment of inertia

  // hs flight mode
  $V0 = 236; // m per s - speed // Vhf - speed of  horizontal flight
  $H0 = 11050; // m - height
  $pr = 0.0372; // (kg * s^2) per m^2 - pressure
  $An = 338.36; // m per s - sound velocity
  $Alpha_bal = 6.04; // deg
  $Tetta0 = 0; // deg
  $g = 9.73; // m per s^2 - gravitational acceleration
  $m = $G0 / $g; // N - Weight

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

  $C_z_B = -0.8595;
  $C_z_Dn = -0.1759;

  $m_x_Dn = -0.01719;
  $m_x_vWy = -0.11;
  $m_x_vWx = -0.66;
  $m_x_B = -0.1146;
  $m_x_De = -0.043;

  $m_y_vWy = -0.145;
  $m_y_B = -0.1719;
  $m_y_Dn = -0.0716;
  $m_y_De = 0;
  $m_y_vWx = -0.006;

  $m_z0 = 0.2;
  $m_z_vWz = -13;
  $m_z_vDAlpha = -3.8;
  $m_z_vAlpha = -1.38;
  $m_z_Dv = -0.96;
  $m_z_M = 0;

  // automatic control rule
  $k_Gamma = 2.0;
  $k_Wx = 1.5;
  $k_Wy = 2.5;

  // aircraft positioning methods
  $k_Gamma_set = 0.7; // (deg * s) per m
  /// flight by course
  ### F_Gamma = +- 20 dergees
  /// flight by path
  ### F_Gamma = +- 20 dergees
  /// flight by way
  $k_Z = 0.02; // deg per m
  $k_pZ = 0.7; // (deg * s) per m
  ### F_Gamma = +- 20 dergees

  $SHK = 0;

  // for calculations
  $Ga_B = $m_y_B - (($C_z_B * $pr * $S * $l) / (4 * $m)) * $m_y_vWy;
  $W_x_De = -0.73;
  $Xx = (($m_x_B * $I_y) / ($m_y_B * $I_x)) * (1 / sqrt(1 - pow(($m_x_vWx / $I_x), 2) * $I_y * $S * pow($l, 2) * ($pr / (4 * $m_y_B))));
  $C_ybal = (2 * $G0) / ($S * $pr * pow($V0, 2));
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
  $b[4] = ($g / $V0) * cos(deg2rad($Alpha_bal)); // * cos($A_hf)
  $b[5] = -(($m_y_De * $pr * pow($V0, 2)) / (2 * $I_y)) * $S * pow($l, 1);
  $b[6] = -(($m_y_vWx * $pr * pow($V0, 1)) / (4 * $I_y)) * $S * pow($l, 2);
  $b[7] = sin(deg2rad($Alpha_bal)); // sin($A_hf)
  echo "<div class=\"container\">
    <div class=\"section\">";
  echo "<b>a</b>: ";
  var_dump($a);
  echo "<br/><b>b</b>: ";
  var_dump($b);
  echo "<br/>";
  echo "</div>
  </div>";

  //////////////////////////////////////
  ////////////Control Panel/////////////
  //////////////////////////////////////
  // $mode = "free flight kappa";     // 
  // $mode = "free flight De";        // 
  $mode = "regulation";            //
  //                                  //
  // $positioning_method = "course";  //
  // $positioning_method = "path";    //
  $positioning_method = "way";     //
  //                                  //
  $integration_method = "eiler";   //
  //                                  //
  // $signal = "zero";                // 
  $signal = "normal";              //
  //////////////////////////////////////

  $graph_data = array_fill(1,5,array());

  for($flight_case = 1; $flight_case <= 5; $flight_case++) {

    $t = 0; // s - flight time
    $td = 0; // s - output time
    $tf = 260.1; // s - flight ending time
    $dt = 0.01; // 1 per s - integration step
    $dd = 1; // s - output step

    $X = array_fill(1, 10, 0);
    $Y = array_fill(1, 10, 0);
    //$Y[1] = -1 * $P_p0;
    $X[6] = 1;
    $Y[6] = -50000;
    $Y[8] = $G_f0;

    switch($flight_case) {
      case 1 : {
        $Y[7] = 0;
        $W = 0;
        $NV = 0;
      break;
      }
      case 2 : {
        $Y[7] = 2000;
        $W = 0;
        $NV = 0;
      break;
      }
      case 3 : {
        $Y[7] = 0;
        $W = 40;
        $NV = 0;
      break;
      }
      case 4 : {
        $Y[7] = 0;
        $W = 40;
        $NV = 180;
      break;
      }
      case 5 : {
        $Y[7] = 0;
        $W = 40;
        $NV = 135;
      break;
      }
    }

    $Betta_v = 0;
    $Betta_w = 0;
    $V_sh = 0;
    $W_x = 0;
    $W_z = 0;
    $Dn = 0;
    $De = 0;

    echo "<div class=\"container\">
      <div class=\"section\">"
      . "<h5 aling=\"left\">" .
      "Mode value = <u>$mode</u>. " .
      "Positioning method = <u>$positioning_method</u>.</br>" .
      "Integration method value = <u>$integration_method</u>. " .
      "Integration step value = <u>$dt</u></br>" .
      "Z0 value = <u>" . $Y[7] . "</u>. W value = <u>$W</u>. NV value = <u>$NV</u>" .
      "</h3>" .
      "</div>
    </div>";

    echo "<div class=\"container\">
    <div class=\"section\">";
    echo
      "<table width=\"100%\" cellspacing=\"0\" border=\"1\">
      <tr>
        <th>T</th>
        <th>De</th>
        <th>Dn</th>
        <th>Gamma</th>
        <th>Psi_g</th>
        <th>X</th>
        <th>Z</th>
        <th>G_f</th>
      </tr>";

    for($t; $t <= $tf; $t += $dt) {

      $Psi_g = -1 * $Y[1];

      $W_x = $W * cos(deg2rad($NV - $Psi_g));
      $W_z = $W * sin(deg2rad($NV - $Psi_g));

      $Betta_w = -1 * rad2deg($W_z) / $V0;
      $Betta_v = $Y[5] + $Betta_w;
      
      $V_sh = $V0 + $W_x;
      
      $P_p = rad2deg(atan($Y[7] / $Y[6]));
      
      $X[6] = $V_sh * cos(deg2rad($Psi_g + $Y[5])); // pX
      $X[7] = $V_sh * sin(deg2rad($Psi_g + $Y[5])); // pZ

      switch($mode) {
        case "regulation" : {
          switch($positioning_method) {
            case "course" : {
              $KKp = $P_p - $Psi_g;
              $Gamma_set = $k_Gamma_set * $V_sh * sin(deg2rad($KKp));
              if($Gamma_set > 20) {
                $Gamma_set = 20;
              } elseif($Gamma_set < -20) {
                $Gamma_set = -20;
              }
            break;
            }
            case "path" : {
              $SHK = rad2deg(atan(($X[7] / $X[6])));
              $DSHK = $P_p - $SHK;
              $Gamma_set = $k_Gamma_set * $V_sh * sin(deg2rad($DSHK));
              if($Gamma_set > 20) {
                $Gamma_set = 20;
              } elseif($Gamma_set < -20) {
                $Gamma_set = -20;
              }
            break;
            }
            case "way" : {
              $Gamma_set = -1 * ($k_Z * $Y[7] + $k_pZ * $X[7]);
              if($Gamma_set > 20) {
                $Gamma_set = 20;
              } elseif($Gamma_set < -20) {
                $Gamma_set = -20;
              }
            break;
            }
          }
        break;
        }
      }
      
      $De = $k_Gamma * ($Y[3] - $Gamma_set) + $k_Wx * $Y[4];
      $Dn = $k_Wy * $Y[2];

      $X[1] = $Y[2]; // pPsi
      $X[2] = -$a[1] * $Y[2] - $b[6] * $Y[4] - $a[2] * $Betta_v - $a[3] * $Dn - $b[5] * $De; // pWy
      $X[3] = $Y[4]; // pGamma
      $X[4] = -$b[1] * $Y[4] - $a[6] * $Y[2] - $b[2] * $Betta_v - $a[5] * $Dn - $b[3] * $De; // pWx
      $X[5] = $Y[2] + $b[4] * $Y[3] + $b[7] * $Y[4] - $a[4] * $Betta_v - $a[7] * $Dn; // pBetta
      $X[8] = -3 * $q_eng; // pG_f

      switch($integration_method) {
        case "eiler" : {
          for($i = 1; $i <= 8; $i++){
            $Y[$i] += $X[$i] * $dt;
          }
        break;
        }
      }

      
      for($t; $t >= $td; $td += $dd){
        if($Y[7] < 0.001) {
          array_push($graph_data[$flight_case], ["time" => $td, "Gamma" => $Y[3], "Psi_g" => $Psi_g, "X" => $Y[6], "Z" => 0, "G_f" => $Y[8]]);
        } else {
          array_push($graph_data[$flight_case], ["time" => $td, "Gamma" => $Y[3], "Psi_g" => $Psi_g, "X" => $Y[6], "Z" => $Y[7], "G_f" => $Y[8]]);
        }
        echo  "<tr>
        <td>" . number_format($td, 1, '.', ' ') . "</td>
        <td>" . number_format($De, 4, '.', ' ') . "</td>
        <td>" . number_format($Dn, 4, '.', ' ') . "</td>
        <td>" . number_format($Y[3], 4, '.', ' ') . "</td>
        <td>" . number_format($Psi_g, 4, '.', ' ') . "</td>
        <td>" . number_format($Y[6], 4, '.', ' ') . "</td>
        <td>" . number_format($Y[7], 4, '.', ' ') . "</td>
        <td>" . number_format($Y[8], 4, '.', ' ') . "</td>
        </tr>";
        if($Y[6] >= -10) {
          break 2;
        }
      }
    }
    echo "</table><br/>";
    echo  "</div>
    </div>";

    $graph_data_file = 'data' . $flight_case . '.json';
    $handle = fopen($graph_data_file, 'w') or die ('Cannot open file: ' . $graph_data_file);
    $graph_content = json_encode($graph_data[$flight_case]);
    fwrite($handle, $graph_content); 
  }
  $Vi = $V0 * 3.6 * sqrt(($pr)/(0.1249)); // Vhf
  $M = $Vi / $An;
  echo "<div class=\"container\">
    <div class=\"section\">";
    echo "<h4>Vi = " . $Vi . "</h2>";
    echo "<h5>M = " . $M . "</h3>";
  echo "</div>
  </div>";
  echo "</div>";  
?>
<html>
<body>
      <div class="section no-pad-bot scrollspy" id="graphics">
          <!--
          <div id = "chart_div_X" style = "width: 100%; height: 100%"></div>
          <div id = "chart_div_Z" style = "width: 100%; height: 100%"></div>
          -->
          <div id = "chart_div_fc1" style = "width: 100%; height: 100%"></div>
          <div id = "chart_div_fc2" style = "width: 100%; height: 100%"></div>
          <div id = "chart_div_fc3" style = "width: 100%; height: 100%"></div>
          <div id = "chart_div_fc4" style = "width: 100%; height: 100%"></div>
          <div id = "chart_div_fc5" style = "width: 100%; height: 100%"></div>
      </div>
    </div>
  </div>
  <?php include 'html/footer.html';?>
  <!---  Scripts--->
  <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
  <script src="js/materialize.js"></script>
  <script src="js/init.js"></script>
  <script type = "text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
      var SSpy_elements = document.querySelectorAll('.scrollspy');
      var SSpy_options = {throttle: 100, scrollOffset: 5, activeClass: "active"};
      var instances = M.ScrollSpy.init(SSpy_elements, SSpy_options);
    });
  </script>
  <script type = "text/javascript">
    function scrollToGraphics() {
      var graphics = document.getElementById("chart_div_fc1");
      graphics.scrollIntoView({block: "start", behavior: "smooth"});
    }
    function scrollToTop() {
      var graphics = document.getElementById("nav-bar");
      graphics.scrollIntoView({block: "start", behavior: "smooth"});
    }
  </script>
  <script type = "text/javascript" src = "https://www.gstatic.com/charts/loader.js"></script>
  <script type = "text/javascript">
    google.charts.load('current', {packages: ['corechart','line']});  
  </script>
  <!----
  <script>
    function chart_div_X() {
      var data = new google.visualization.DataTable();
      data.addColumn('number', 'flight time');
      data.addColumn('number', 'flight case 1');
      data.addColumn('number', 'flight case 2');
      data.addColumn('number', 'flight case 3');
      data.addColumn('number', 'flight case 4');
      data.addColumn('number', 'flight case 5');
      data.addRows([
        <?php
          $data = array();
          $json_data = array();
          for($i = 1; $i <= 5; $i++) {
            $data[$i] = file_get_contents("./data" . $i . ".json");
            $json_data[$i] = json_decode($data[$i], true);
          }
          $max_time = 0;
          $min_time = 999;
          for($i = 1; $i <= 5; $i++) {
            $max_time = max($max_time, count($json_data[$i]));
            $min_time = min($min_time, count($json_data[$i]));
          }
          for ($i = 0; $i <= ($min_time - 1); $i++) {
            echo "["
            . $json_data[1][$i]['time'] . ",  "
            . $json_data[1][$i]['X'] . ",  "
            . $json_data[2][$i]['X'] . ",  "
            . $json_data[3][$i]['X'] . ",  "
            . $json_data[4][$i]['X'] . ",  "
            . $json_data[5][$i]['X']
            . "],";
          }
        ?>
      ]);
      var chart_options = {
        'title' : 'X',
        'height': 800,
        'width': 1200,
        curveType: 'function',
        colors: ['blue', 'red', 'yellow', 'purple', 'green']
      };
      var chart = new google.visualization.LineChart(document.getElementById('chart_div_X'));
      chart.draw(data, chart_options);
    }
    google.charts.setOnLoadCallback(chart_div_X);

    function chart_div_Z() {
      var data = new google.visualization.DataTable();
      data.addColumn('number', 'flight time');
      data.addColumn('number', 'flight case 1');
      data.addColumn('number', 'flight case 2');
      data.addColumn('number', 'flight case 3');
      data.addColumn('number', 'flight case 4');
      data.addColumn('number', 'flight case 5');
      data.addRows([
        <?php
          $data = array();
          $json_data = array();
          $max_time = 0;
          $min_time = 999;
          for($i = 1; $i <= 5; $i++) {
            $data[$i] = file_get_contents("./data" . $i . ".json");
            $json_data[$i] = json_decode($data[$i], true);
            $max_time = max($max_time, count($json_data[$i]));
            $min_time = min($min_time, count($json_data[$i]));
          }
          for ($i = 0; $i <= ($min_time - 1); $i++) {
            echo "["
            . $json_data[1][$i]['time'] . ",  "
            . $json_data[1][$i]['Z'] . ",  "
            . $json_data[2][$i]['Z'] . ",  "
            . $json_data[3][$i]['Z'] . ",  "
            . $json_data[4][$i]['Z'] . ",  "
            . $json_data[5][$i]['Z']
            . "],";
          }
        ?>
      ]);
      var chart_options = {
        'title' : 'Z',
        'height': 2400,
        'width': 1200,
        curveType: 'function',
        colors: ['blue', 'red', 'yellow', 'purple', 'green']
      };
      var chart = new google.visualization.LineChart(document.getElementById('chart_div_Z'));
      chart.draw(data, chart_options);
    }
    google.charts.setOnLoadCallback(chart_div_Z);

    function chart_div_fc1() {
      var data = new google.visualization.DataTable();
      data.addColumn('number', 'flight time');
      data.addColumn('number', 'flight case 1');
      data.addRows([
        <?php
          $data = array();
          $json_data = array();
          $max_time = 0;
          for($i = 1; $i <= 1; $i++) {
            $data[$i] = file_get_contents("./data" . $i . ".json");
            $json_data[$i] = json_decode($data[$i], true);
            $max_time = max($max_time, count($json_data[$i]));
          }
          for ($i = 0; $i <= ($max_time - 1); $i++) {
            echo "["
            . $json_data[1][$i]['X'] . ",  "
            . $json_data[1][$i]['Z']
            . "],";
          }
        ?>
      ]);
      var chart_options = {
        'title' : 'flight case 1',
        'height': 2400,
        'width': 1200,
        curveType: 'function',
        colors: ['blue', 'red', 'yellow', 'purple', 'green']
      };
      var chart = new google.visualization.LineChart(document.getElementById('chart_div_fc1'));
      chart.draw(data, chart_options);
    }
    google.charts.setOnLoadCallback(chart_div_fc1);
  </script>
  ---->
  <script>
    function chart_div_fc1() {
      var data = new google.visualization.DataTable();
      data.addColumn('number', 'flight time');
      data.addColumn('number', 'flight case 1');
      data.addRows([
        <?php
          $data = array();
          $json_data = array();
          for($i = 1; $i <= 5; $i++) {
            $data[$i] = file_get_contents("./data" . $i . ".json");
            $json_data[$i] = json_decode($data[$i], true);
          }
          for ($i = 0; $i <= (count($json_data[1]) - 1); $i++) {
            echo "["
            . $json_data[1][$i]['X'] . ",  "
            . $json_data[1][$i]['Z']
            . "],";
          }
        ?>
      ]);
      var chart_options = {
        'title' : 'flight case 1',
        'height': 600,
        'width': 1200,
        curveType: 'function',
        colors: ['blue', 'red', 'yellow', 'purple', 'green']
      };
      var chart = new google.visualization.LineChart(document.getElementById('chart_div_fc1'));
      chart.draw(data, chart_options);
    }
    google.charts.setOnLoadCallback(chart_div_fc1);

    function chart_div_fc2() {
      var data = new google.visualization.DataTable();
      data.addColumn('number', 'flight time');
      data.addColumn('number', 'flight case 2');
      data.addRows([
        <?php
          $data = array();
          $json_data = array();
          for($i = 1; $i <= 5; $i++) {
            $data[$i] = file_get_contents("./data" . $i . ".json");
            $json_data[$i] = json_decode($data[$i], true);
          }
          for ($i = 0; $i <= (count($json_data[2]) - 1); $i++) {
            echo "["
            . $json_data[2][$i]['X'] . ",  "
            . $json_data[2][$i]['Z']
            . "],";
          }
        ?>
      ]);
      var chart_options = {
        'title' : 'flight case 2',
        'height': 600,
        'width': 1200,
        curveType: 'function',
        colors: ['blue', 'red', 'yellow', 'purple', 'green']
      };
      var chart = new google.visualization.LineChart(document.getElementById('chart_div_fc2'));
      chart.draw(data, chart_options);
    }
    google.charts.setOnLoadCallback(chart_div_fc2);

    function chart_div_fc3() {
      var data = new google.visualization.DataTable();
      data.addColumn('number', 'flight time');
      data.addColumn('number', 'flight case 3');
      data.addRows([
        <?php
          $data = array();
          $json_data = array();
          for($i = 1; $i <= 5; $i++) {
            $data[$i] = file_get_contents("./data" . $i . ".json");
            $json_data[$i] = json_decode($data[$i], true);
          }
          for ($i = 0; $i <= (count($json_data[3]) - 1); $i++) {
            echo "["
            . $json_data[3][$i]['X'] . ",  "
            . $json_data[3][$i]['Z']
            . "],";
          }
        ?>
      ]);
      var chart_options = {
        'title' : 'flight case 3',
        'height': 600,
        'width': 1200,
        curveType: 'function',
        colors: ['blue', 'red', 'yellow', 'purple', 'green']
      };
      var chart = new google.visualization.LineChart(document.getElementById('chart_div_fc3'));
      chart.draw(data, chart_options);
    }
    google.charts.setOnLoadCallback(chart_div_fc3);

    function chart_div_fc4() {
      var data = new google.visualization.DataTable();
      data.addColumn('number', 'flight time');
      data.addColumn('number', 'flight case 4');
      data.addRows([
        <?php
          $data = array();
          $json_data = array();
          for($i = 1; $i <= 5; $i++) {
            $data[$i] = file_get_contents("./data" . $i . ".json");
            $json_data[$i] = json_decode($data[$i], true);
          }
          for ($i = 0; $i <= (count($json_data[4]) - 1); $i++) {
            echo "["
            . $json_data[4][$i]['X'] . ",  "
            . $json_data[4][$i]['Z']
            . "],";
          }
        ?>
      ]);
      var chart_options = {
        'title' : 'flight case 4',
        'height': 600,
        'width': 1200,
        curveType: 'function',
        colors: ['blue', 'red', 'yellow', 'purple', 'green']
      };
      var chart = new google.visualization.LineChart(document.getElementById('chart_div_fc4'));
      chart.draw(data, chart_options);
    }
    google.charts.setOnLoadCallback(chart_div_fc4);

    function chart_div_fc5() {
      var data = new google.visualization.DataTable();
      data.addColumn('number', 'flight time');
      data.addColumn('number', 'flight case 5');
      data.addRows([
        <?php
          $data = array();
          $json_data = array();
          for($i = 1; $i <= 5; $i++) {
            $data[$i] = file_get_contents("./data" . $i . ".json");
            $json_data[$i] = json_decode($data[$i], true);
          }
          for ($i = 0; $i <= (count($json_data[5]) - 1); $i++) {
            echo "["
            . $json_data[5][$i]['X'] . ",  "
            . $json_data[5][$i]['Z']
            . "],";
          }
        ?>
      ]);
      var chart_options = {
        'title' : 'flight case 5',
        'height': 600,
        'width': 1200,
        curveType: 'function',
        colors: ['blue', 'red', 'yellow', 'purple', 'green']
      };
      var chart = new google.visualization.LineChart(document.getElementById('chart_div_fc5'));
      chart.draw(data, chart_options);
    }
    google.charts.setOnLoadCallback(chart_div_fc5);
  </script>
</body>
</html>