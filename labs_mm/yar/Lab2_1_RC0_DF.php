<?php
require './html_wrap_up.php';
require './Lab2_1_RC0.php';
pageTop();

echo "<h1 align=\"center\"> Dempfer signal = 0 </h1>";
echo "<p></p>";

$var0 = 201.45;
$var1 = 5.285;
$var2 = 73000;
$var3 = 24;
$var4 = 660000;
$var5 = 250.0;
$var6 = 11300;
$var7 = 0.0372;
$var8 = 295.06;
$var9 = 9.81;
$var10 = -0.320;
$var11 = 6.30;
$var12 = 0.2635;
$var13 = 0.031;
$var14 = 0.27;
$var15 = -15.5;
$var16 = -5.2;
$var17 = -2.69;
$var18 = -0.92;
$var19 = 1.0;
$var20 = 0.7;
$var21 = 0.112;
$var22 = -17.86;
$var23 = 0;
$var24 = 0;
$var25 = 20.0001;
$var26 = 0.01;
$var27 = 0.5;
$var28 = 0;

$test = new flightDemo($var0,
                      $var1,
                      $var2,
                      $var3,
                      $var4,
                      $var5,
                      $var6,
                      $var7,
                      $var8,
                      $var9,
                      $var10,
                      $var11,
                      $var12,
                      $var13,
                      $var14,
                      $var15,
                      $var16,
                      $var17,
                      $var18,
                      $var19,
                      $var20,
                      $var21,
                      $var22,
                      $var23,
                      $var24,
                      $var25,
                      $var26,
                      $var27,
                      $var28);

$test->CreateIndexArr();
$test->DisplayTabHead();
$test->CalculateParams();

echo "<h1 align=\"center\"> Dempfer signal = 1 </h1>";
echo "<p></p>";

$var28 = 1;

$test1 = new flightDemo($var0,
                      $var1,
                      $var2,
                      $var3,
                      $var4,
                      $var5,
                      $var6,
                      $var7,
                      $var8,
                      $var9,
                      $var10,
                      $var11,
                      $var12,
                      $var13,
                      $var14,
                      $var15,
                      $var16,
                      $var17,
                      $var18,
                      $var19,
                      $var20,
                      $var21,
                      $var22,
                      $var23,
                      $var24,
                      $var25,
                      $var26,
                      $var27,
                      $var28);

$test1->CreateIndexArr();
$test1->DisplayTabHead();
$test1->CalculateParams();

echo "<h1 align=\"center\"> Dempfer signal = 2 </h1>";
echo "<p></p>";

$var28 = 2;

$test2 = new flightDemo($var0,
                      $var1,
                      $var2,
                      $var3,
                      $var4,
                      $var5,
                      $var6,
                      $var7,
                      $var8,
                      $var9,
                      $var10,
                      $var11,
                      $var12,
                      $var13,
                      $var14,
                      $var15,
                      $var16,
                      $var17,
                      $var18,
                      $var19,
                      $var20,
                      $var21,
                      $var22,
                      $var23,
                      $var24,
                      $var25,
                      $var26,
                      $var27,
                      $var28);

$test2->CreateIndexArr();
$test2->DisplayTabHead();
$test2->CalculateParams();

echo "<p></p>";
echo "<p></p>";
echo "<p></p>";
var_dump($test->charts_dat);
echo "<p></p>";
echo "<p></p>";
echo "<p></p>";
var_dump($test1->charts_dat);
echo "<p></p>";
echo "<p></p>";
echo "<p></p>";
var_dump($test2->charts_dat);
echo "<p></p>";
echo "<p></p>";
echo "<p></p>";

$chart_dat_file = 'cdf.json';
$handle = fopen($chart_dat_file, 'w') or die ('Can`t open file:  ' . $chart_dat_file);
$chart_data = json_encode($test->charts_dat);
fwrite($handle, $chart_data);

$chart_dat_file1 = 'cdf1.json';
$handle1 = fopen($chart_dat_file1, 'w') or die ('Can`t open file:  ' . $chart_dat_file1);
$chart_data1 = json_encode($test1->charts_dat);
fwrite($handle1, $chart_data1);

$chart_dat_file2 = 'cdf2.json';
$handle2 = fopen($chart_dat_file2, 'w') or die ('Can`t open file:  ' . $chart_dat_file2);
$chart_data2 = json_encode($test2->charts_dat);
fwrite($handle2, $chart_data2);


?>
<!---
<?php
$a = file_get_contents("./cdf.json");
$a1 = file_get_contents("./cdf1.json");
$a2 = file_get_contents("./cdf2.json");
$json_a = json_decode($a, true);
$json_a1 = json_decode($a1, true);
$json_a2 = json_decode($a2, true);

for ($i = 0; $i <= (count($json_a)-1); $i++) {
  echo "["
  . $json_a[$i]['time'] . ",  "
  . $json_a[$i]['ALF'] . ", "
  . $json_a1[$i]['ALF'] . ", "
  . $json_a2[$i]['ALF']
  . "],";
}
pageBottom();
?>
-->

<html>
  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
    google.charts.load('current', {'packages':['line']});
    google.charts.setOnLoadCallback(drawAAChart);
    google.charts.setOnLoadCallback(drawPAChart);
    google.charts.setOnLoadCallback(drawVLFChart);

    function drawAAChart() {

      var data = new google.visualization.DataTable();
      data.addColumn('number', 'Flight time');
      data.addColumn('number', 'Damper mode 1');
      data.addColumn('number', 'Damper mode 2');
      data.addColumn('number', 'Damper mode 3');

      data.addRows([
        <?php
        $a = file_get_contents("./cdf.json");
        $a1 = file_get_contents("./cdf1.json");
        $a2 = file_get_contents("./cdf2.json");
        $json_a = json_decode($a, true);
        $json_a1 = json_decode($a1, true);
        $json_a2 = json_decode($a2, true);

        for ($i = 0; $i <= (count($json_a)-1); $i++) {
          echo "["
          . $json_a[$i]['time'] . ",  "
          . $json_a[$i]['ALF'] . ", "
          . $json_a1[$i]['ALF'] . ", "
          . $json_a2[$i]['ALF']
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

      var chart = new google.charts.Line(document.getElementById('AA_chart_div'));

      chart.draw(data, google.charts.Line.convertOptions(options));
    }

    function drawPAChart() {

      var data = new google.visualization.DataTable();
      data.addColumn('number', 'Flight time');
      data.addColumn('number', 'Damper mode 1');
      data.addColumn('number', 'Damper mode 2');
      data.addColumn('number', 'Damper mode 3');

      data.addRows([
        <?php
        $a = file_get_contents("./cdf.json");
        $a1 = file_get_contents("./cdf1.json");
        $a2 = file_get_contents("./cdf2.json");
        $json_a = json_decode($a, true);
        $json_a1 = json_decode($a1, true);
        $json_a2 = json_decode($a2, true);

        for ($i = 0; $i <= (count($json_a)-1); $i++) {
          echo "["
          . $json_a[$i]['time'] . ",  "
          . $json_a[$i]['TANG'] . ", "
          . $json_a1[$i]['TANG'] . ", "
          . $json_a2[$i]['TANG']
          . "],";
        }
        ?>
      ]);

      var options = {
        chart: {
          title: 'Pitch Angle',
          subtitle: 'For 3 different damper modes'
        },
        width: 900,
        height: 500,
        //colors: ['red', 'green', 'cyan']
      };

      var chart = new google.charts.Line(document.getElementById('PA_chart_div'));

      chart.draw(data, google.charts.Line.convertOptions(options));
    }

    function drawVLFChart() {

      var data = new google.visualization.DataTable();
      data.addColumn('number', 'Flight time');
      data.addColumn('number', 'Damper mode 1');
      data.addColumn('number', 'Damper mode 2');
      data.addColumn('number', 'Damper mode 3');

      data.addRows([
        <?php
        $a = file_get_contents("./cdf.json");
        $a1 = file_get_contents("./cdf1.json");
        $a2 = file_get_contents("./cdf2.json");
        $json_a = json_decode($a, true);
        $json_a1 = json_decode($a1, true);
        $json_a2 = json_decode($a2, true);

        for ($i = 0; $i <= (count($json_a)-1); $i++) {
          echo "["
          . $json_a[$i]['time'] . ",  "
          . $json_a[$i]['N_Y'] . ", "
          . $json_a1[$i]['N_Y'] . ", "
          . $json_a2[$i]['N_Y']
          . "],";
        }
        ?>
      ]);

      var options = {
        chart: {
          title: 'Vertical Load Factor',
          subtitle: 'For 3 different damper modes'
        },
        width: 900,
        height: 500,
        //colors: ['red', 'green', 'cyan']
      };

      var chart = new google.charts.Line(document.getElementById('VLF_chart_div'));

      chart.draw(data, google.charts.Line.convertOptions(options));
    }

    </script>
 </head>
<body>
  <div id="AA_chart_div"></div>
  <div id="PA_chart_div"></div>
  <div id="VLF_chart_div"></div>
</body>
</html>
