<?php
include 'html/main_page.html';
ini_set('error_reporting', E_ALL);

  // aerodynamic characteristics
  $V0 = 850 * (1000 / 3600); // m per s - speed // Vhf - speed of horizontal flight
  $DVinst = 4 * (1000 / 3600); // m per s - інструментальна похибка вимірювання швидкості
  $DVst = -25 * (1000 / 3600); // m per s - похибка швидкості викликана стисненням повітря
  $DVa = -1 * (1000 / 3600); // m per s - аеродинамічна похибка вимірювання швидкості
  $Di = 315; // deg - метеорологічний напрямок вітру
  $W = 110 * (1000 / 3600); // m per s - speed of wind
  $DM = 15; // deg - магнітне схолення
  $DK = 3; // deg - девіація
  $ZMSHK = 250; // deg - заданий магнітний шляховий кут
  $Omega_guh = 1.2 * (1 / 3600); // deg / h - швидкість "уходу" гіроскопу
  $Hech_set = 9750; // m - height set by echelon
  $R = 6373 * 1000; // m - радіус Земної сфери

  if($Di >= 180) {
    $NVi = $Di - 180; //
  } else {
    $NVi = $Di + 180; // навігаційний напрямок вітру по істиному меридіану
  }
  $ZISHK = $ZMSHK + $DM; // заданий істинний шляховий кут
  $KV = $NVi - $ZISHK; // кут вітру  
  $KZ = rad2deg(asin(($W * sin(deg2rad($KV))) / $V0)); // кут зносу 
  $Psi_g0 = $ZISHK - $KZ; // початковий гіроскопічний курс
  $Psi_guh = 0; // власний "ухід" гіроскопу
  $Psi_g = $Psi_g0 + $Psi_guh; // поточний гіроскопічний курс
  $Hi = 0; // кут збіжності меридіанів 
  $IK = $Psi_g + $Hi; // істинний курс
  $MK = $IK - $DM; // магнітний курс
  $KK = $MK - $DK; // компасний курс
  $KKV = $NVi - $IK; // курсовий кут вітру
  $Vi = $V0; // індикаторна швидкість
  $Vpr = $Vi - $DVinst - $DVa - $DVst; // приладова швидкість
  //$Vsh1 = $W * sin(deg2rad($KKV)) / sin(deg2rad($KZ)); // 
  $Vsh = $V0 * sin(deg2rad($KKV)) / sin(deg2rad($KV)); // шляхова швидкість
  $Vsh_x = $Vsh * cos(deg2rad($ZISHK)); // 
  $Vsh_z = $Vsh * sin(deg2rad($ZISHK)); // 
  //$Fi_c0 =  $Vsh_x / ($R + $Hech_set); // сферична широта
  //$Lambda_c0 =  $Vsh_z / (cos(deg2rad($Fi_c0)) * ($R + $Hech_set)); // сферична довгота
  $Fi_c0 = 57.181607;
  $Lambda_c0 = 65.348751;

  echo "<div class=\"container\">
    <div class=\"section\">" .
      "<table width=\"100%\" cellspacing=\"0\" border=\"1\" class=\"striped\">
      <tr>
        <th>Fi_c0</th>
        <th>Lambda_c0</th>
        <th>V</th>
        <th>Vi</th>
        <th>Vpr</th>
        <th>Vsh</th>
        <th>ZISHK</th>
        <th>NVi</th>
        <th>KV</th>
        <th>KZ</th>
        <th>KKV</th>
        <th>IK</th>
        <th>MK</th>
        <th>KK</th>
        <th>Psi_g0</th>
      </tr>";
  echo "<tr>
        <th>" . number_format($Fi_c0, 2, '.', ' ') . "</th>
        <th>" . number_format($Lambda_c0, 2, '.', ' ') . "</th>
        <th>" . number_format($V0, 0, '.', ' ') . "</th>
        <th>" . number_format($Vi, 0, '.', ' ') . "</th>
        <th>" . number_format($Vpr, 0, '.', ' ') . "</th>
        <th>" . number_format($Vsh, 2, '.', ' ') . "</th>
        <th>" . number_format($ZISHK, 0, '.', ' ') . "</th>
        <th>" . number_format($NVi, 0, '.', ' ') . "</th>
        <th>" . number_format($KV, 0, '.', ' ') . "</th>
        <th>" . number_format($KZ, 2, '.', ' ') . "</th>
        <th>" . number_format($KKV, 2, '.', ' ') . "</th>
        <th>" . number_format($IK, 2, '.', ' ') . "</th>
        <th>" . number_format($MK, 2, '.', ' ') . "</th>
        <th>" . number_format($KK, 2, '.', ' ') . "</th>
        <th>" . number_format($Psi_g0, 2, '.', ' ') . "</th>
      </tr>";
  echo "</table>
    </div>
  </div>";

  $graph_data = array_fill(0,3,array());

  for($Omega_guh_mode = 0; $Omega_guh_mode <= 1; $Omega_guh_mode++) {

    $t = 0; // s - flight time
    $td = 0; // s - output time
    $tf = 30 * 60 + 0.1; // s - flight ending time
    $dt = 1; // 1 per s - integration step
    $dd = 60; // s - output step

    echo "<div class=\"container\">
      <div class=\"section\">
        <h5 aling=\"left\">" . "Omega_guh = ";
        if($Omega_guh_mode == 0) { 
          echo "0";
        } else {
          echo "1.2";
        }
        echo "</h5>
      </div>
    </div>";

    $X = array_fill(1, 10, 0);
    $Y = array_fill(1, 10, 0);
    $Xc = array_fill(0, 3, 0);
    $Zc = array_fill(0, 3, 0);
    $Y[1] = $Fi_c0;
    $Y[2] = $Lambda_c0;
    $Y[3] = 0;
    $Y[4] = 0;
    $Y[5] = 0;
    $Y[6] = 0;

    echo "<div class=\"container\">
      <div class=\"section\">" .
        "<table width=\"100%\" cellspacing=\"0\" border=\"1\" class=\"striped highlight\">
        <tr>
          <th>T</th>
          <th>Fi_c</th>
          <th>Lambda_c</th>
          <th>Psi_g</th>
          <th>IK</th>
          <th>Hi</th>
          <th>Xc</th>
          <th>Zc</th>
        </tr>";

        $ZISHK = 0;
        $IK = 0;
        $Psi_g = 0;

    for($t; $t <= $tf; $t += $dt){

      $X[1] =  $X[3] / ($R + $Hech_set); // pFi_c
      $X[2] =  $X[4] / (cos(deg2rad($Y[1])) * ($R + $Hech_set)); // pLambda_c
      $X[3] = $Vsh * cos(deg2rad($ZISHK)); // pXc == Vsh_x
      $X[4] = $Vsh * sin(deg2rad($ZISHK)); // pZc == Vsh_z
      $ZISHK = $IK + $KZ; //
      $IK = $Psi_g + $Y[5]; //
      $X[5] = -1 *  $X[4] * tan(deg2rad($Y[1])) / ($R + $Hech_set) ; // pHi
      $Psi_g = $Psi_g0 + $Y[6]; //
      if($Omega_guh_mode == 0) {
        $X[6] = 0; //
      } else {
        $X[6] = $Omega_guh; // pPsi_guh
      }

      for($t; $t >= $td; $td += $dd){
        array_push($graph_data[$Omega_guh_mode], ["time" => $td, "Fi_c" => $Y[1], "Lambda_c" => $Y[2], "Psi_g" => $Psi_g, "IK" => $IK, "Hi"=>$Y[5], "Xc" => $Y[3], "Zc" => $Y[4]]);
        echo  "<tr>
        <td>" . number_format($td, 1, '.', ' ') . "</td>
        <td>" . number_format($Y[1], 4, '.', ' ') . "</td>
        <td>" . number_format($Y[2], 4, '.', ' ') . "</td>
        <td>" . number_format($Psi_g, 4, '.', ' ') . /*"</td>
        <td>" . number_format($Y[6], 4, '.', ' ') . */"</td>
        <td>" . number_format($IK, 4, '.', ' ') . "</td>
        <td>" . number_format($Y[5], 4, '.', ' ') . "</td>
        <td>" . number_format($Y[3], 4, '.', ' ') . "</td>
        <td>" . number_format($Y[4], 4, '.', ' ') . "</td>
        </tr>";
      }

      for($i = 1; $i <= 6; $i++){
        $Y[$i] += $X[$i] * $dt;
      }
    }
    echo "</table><br/>" .
      "</div>
    </div>";

    $graph_data_file = 'data' . $Omega_guh_mode . '.json';
    $handle = fopen($graph_data_file, 'w') or die ('Cannot open file: ' . $graph_data_file);
    $graph_content = json_encode($graph_data[$Omega_guh_mode]);
    fwrite($handle, $graph_content); 
  }
  echo "</div>";  
?>
<html>
<body>
      <div class="section no-pad-bot scrollspy" id="graphics">
        <div class="container">
          <div id = "chart_div_Fi_c" style = "width: 100%; height: 100%"></div>
          <div id = "chart_div_Lambda_c" style = "width: 100%; height: 100%"></div>
          <div id = "chart_div_Xc" style = "width: 100%; height: 100%"></div>
          <div id = "chart_div_Zc" style = "width: 100%; height: 100%"></div>
          <?php
            $Delta = sqrt(pow(-31063.1349 + 33077.3779, 2) + pow(-385646.7450 + 385477.5906, 2));
            echo "<h5>Delta = " . number_format($Delta, 2, '.', ' ') . " m</h5>";
          ?>
        </div>
      </div>
    </div>
  </div>
  <?php include 'html/footer.html';?>
  <!--  Scripts-->
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
      var graphics = document.getElementById("chart_div_Fi_c");
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
  <script>
    function chart_div_Fi_c() {
      var data = new google.visualization.DataTable();
      data.addColumn('number', 'flight time');
      data.addColumn('number', 'Omega_guh - off');
      data.addColumn('number', 'Omega_guh - on');
      data.addRows([
        <?php
          $data_0 = file_get_contents("./data0.json");
          $data_1 = file_get_contents("./data1.json");
          $json_data_0 = json_decode($data_0, true);
          $json_data_1 = json_decode($data_1, true);
          for ($i = 0; $i <= (count($json_data_0)-1); $i++) {
            echo "["
            . $json_data_0[$i]['time'] . ",  "
            . $json_data_0[$i]['Fi_c'] . ",  "
            . $json_data_1[$i]['Fi_c']
            . "],";
          }
        ?>
      ]);
      var chart_options = {
        'title' : 'Fi_c',
        'height': 400,
        curveType: 'function',
        colors: ['blue', 'red']
      };
      var chart = new google.visualization.LineChart(document.getElementById('chart_div_Fi_c'));
      chart.draw(data, chart_options);
    }
    google.charts.setOnLoadCallback(chart_div_Fi_c);

    function chart_div_Lambda_c() {
      var data = new google.visualization.DataTable();
      data.addColumn('number', 'flight time');
      data.addColumn('number', 'Omega_guh - off');
      data.addColumn('number', 'Omega_guh - on');
      data.addRows([
        <?php
          $data_0 = file_get_contents("./data0.json");
          $data_1 = file_get_contents("./data1.json");
          $json_data_0 = json_decode($data_0, true);
          $json_data_1 = json_decode($data_1, true);
          for ($i = 0; $i <= (count($json_data_0)-1); $i++) {
            echo "["
            . $json_data_0[$i]['time'] . ",  "
            . $json_data_0[$i]['Lambda_c'] . ",  "
            . $json_data_1[$i]['Lambda_c']
            . "],";
          }
        ?>
      ]);
      var chart_options = {
        'title' : 'Lambda_c',
        'height': 400,
        curveType: 'function',
        colors: ['blue', 'red']
      };
      var chart = new google.visualization.LineChart(document.getElementById('chart_div_Lambda_c'));
      chart.draw(data, chart_options);
    }
    google.charts.setOnLoadCallback(chart_div_Lambda_c);

    function chart_div_Xc() {
      var data = new google.visualization.DataTable();
      data.addColumn('number', 'flight time');
      data.addColumn('number', 'Omega_guh - off');
      data.addColumn('number', 'Omega_guh - on');
      data.addRows([
        <?php
          $data_0 = file_get_contents("./data0.json");
          $data_1 = file_get_contents("./data1.json");
          $json_data_0 = json_decode($data_0, true);
          $json_data_1 = json_decode($data_1, true);
          for ($i = 0; $i <= (count($json_data_0)-1); $i++) {
            echo "["
            . $json_data_0[$i]['time'] . ",  "
            . $json_data_0[$i]['Xc'] . ",  "
            . $json_data_1[$i]['Xc']
            . "],";
          }
        ?>
      ]);
      var chart_options = {
        'title' : 'Xc',
        'height': 400,
        curveType: 'function',
        colors: ['blue', 'red']
      };
      var chart = new google.visualization.LineChart(document.getElementById('chart_div_Xc'));
      chart.draw(data, chart_options);
    }
    google.charts.setOnLoadCallback(chart_div_Xc);

    function chart_div_Zc() {
      var data = new google.visualization.DataTable();
      data.addColumn('number', 'flight time');
      data.addColumn('number', 'Omega_guh - off');
      data.addColumn('number', 'Omega_guh - on');
      data.addRows([
        <?php
          $data_0 = file_get_contents("./data0.json");
          $data_1 = file_get_contents("./data1.json");
          $json_data_0 = json_decode($data_0, true);
          $json_data_1 = json_decode($data_1, true);
          for ($i = 0; $i <= (count($json_data_0)-1); $i++) {
            echo "["
            . $json_data_0[$i]['time'] . ",  "
            . $json_data_0[$i]['Zc'] . ",  "
            . $json_data_1[$i]['Zc']
            . "],";
          }
        ?>
      ]);
      var chart_options = {
        'title' : 'Zc',
        'height': 400,
        curveType: 'function',
        colors: ['blue', 'red']
      };
      var chart = new google.visualization.LineChart(document.getElementById('chart_div_Zc'));
      chart.draw(data, chart_options);
    }
    google.charts.setOnLoadCallback(chart_div_Zc);
  </script>
</body>
</html>