<html>
    <head>
        <title>
            Lab 1
        </title>
    </head>
    <body>
        <?php
            $graphics_data = array_fill(1, 2, array());

            // primary (from table)
            $M = array(0 => 0, 1 => 0.125, 0.25, 0.375, 0.5, 0.625, 0.75, 0.875, 1);
            $H = array(0 => 6000, 1 => 5950, 5880, 5800, 5680, 5540, 5310, 4900, 4360);
            echo "<table width=\"50%\" cellspacing=\"0\" border=\"1\"><tr>
                <th>M</th>
                <th>H</th>
            </tr>";
            for($i = 0; $i < count($M); $i++) {
                array_push($graphics_data[1], ["M" => $M[$i], "H" => $H[$i]]);
                echo  "<tr>
                    <td>" . number_format($M[$i], 3, '.', ' ') . "</td>
                    <td>" . number_format($H[$i], 1, '.', ' ') . "</td>
                </tr>";
            }
            echo "</table>";
            echo "<br/><br/>";

            // by piecewise linear function
            $Mf = array();
            $Hf = array();
            echo "<table width=\"50%\" cellspacing=\"0\" border=\"1\"><tr>
                <th>Mf</th>
                <th>Hf</th>
            </tr>";
            for($k = 0, $i = 0; round($k, 3) <= 1; $k+=0.025, $i++) {
                $Mf[$i] = round($k, 3);
            }
            for($i = 0; $i < count($Mf); $i++) {
                $x = $Mf[$i];
                for($j = 0; $j < count($M); $j++) {
                    if($x == $M[$j]){
                        $y = $H[$j];
                        array_push($Hf, $y);
                        break 1;
                    } elseif($x > $M[$j] && $x < $M[$j + 1]) {
                        $x1 = $M[$j];
                        $x2 = $M[$j + 1];
                        $y1 = $H[$j];
                        $y2 = $H[$j + 1];
                        $a = ($y2 - $y1) / ($x2 - $x1);
                        $b = $y1 - $a * $x1;
                        $y = $a * $x + $b;
                        array_push($Hf, $y);
                        break 1;
                    }
                }
            }
            for($i = 0; $i < count($Mf); $i++) {
                array_push($graphics_data[2], ["M" => $Mf[$i], "H" => $Hf[$i]]);
                echo  "<tr>
                    <td>" . number_format($Mf[$i], 3, '.', ' ') . "</td>
                    <td>" . number_format($Hf[$i], 1, '.', ' ') . "</td>
                </tr>";
            }
            echo "</table>";

            // to googlecharts
            for($i = 1; $i <= 2; $i++) {
                $graphics_data_filename = 'data_' . $i . '.json';
                $handler = fopen($graphics_data_filename, 'w') or die ('Cannot open file: ' . $graphics_data_filename);
                $graphics_content = json_encode($graphics_data[$i]);
                fwrite($handler, $graphics_content);
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
        <div id = "chart_div_1" style = "width: 1000px; height: 500px">
        </div>
        <div id = "chart_div_2" style = "width: 1000px; height: 500px">
        </div>
        <script language = "JavaScript">

            function chart_div_1() {
                var data = new google.visualization.DataTable();
                data.addColumn('number', 'M');
                data.addColumn('number', 'H');
                data.addRows([
                    <?php
                        $a = file_get_contents("./data_1.json");
                        $json_a = json_decode($a, true);
                        for ($i = 0; $i <= (count($json_a)-1); $i++) {
                            echo "["
                            . $json_a[$i]['M'] . ",  "
                            . $json_a[$i]['H']
                            . "],";
                        }
                    ?>
                ]);
                var options = {
                    'title' : 'H',
                    'width': 1400,
                    'height': 700,
                    curveType: 'function',
                    colors: ['blue']
                };
                var chart = new google.visualization.LineChart(document.getElementById('chart_div_1'));
                chart.draw(data, options);
            }
            google.charts.setOnLoadCallback(chart_div_1);

            function chart_div_2() {
                var data = new google.visualization.DataTable();
                data.addColumn('number', 'M');
                data.addColumn('number', 'H');
                data.addRows([
                    <?php
                        $a = file_get_contents("./data_2.json");
                        $json_a = json_decode($a, true);
                        for ($i = 0; $i <= (count($json_a)-1); $i++) {
                            echo "["
                            . $json_a[$i]['M'] . ",  "
                            . $json_a[$i]['H']
                            . "],";
                        }
                    ?>
                ]);
                var options = {
                    'title' : 'H',
                    'width': 1400,
                    'height': 700,
                    curveType: 'function',
                    colors: ['red']
                };
                var chart = new google.visualization.LineChart(document.getElementById('chart_div_2'));
                chart.draw(data, options);
            }
            google.charts.setOnLoadCallback(chart_div_2);
        </script>
    </body>
</html>
