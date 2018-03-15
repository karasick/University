<html>
  <head>
    <title>
      Hw 2_1
    </title>
  </head>

  <body>
    <?php

        class LagrangePolynomial {

            public $data;

            public $size_b;
            public $size_m_prev;
            public $size_m;

            public $xValues_b;
            public $yValues_b;
            public $xValues_m;
            public $yValues_m;

            public $x = 0.825;
            public $y;

            public $graph_dat_b = array();
            public $graph_dat_m = array();
        
            function __construct($f_path) {
            
            $this->data = file_get_contents($f_path);
            }

            function ExtractAbsOrds() {

                $absords = explode("\n", $this->data);
                $this->size_b = count($absords);
                foreach ($absords as &$absord) {
        
                    $absord = explode(" ", substr($absord, 0));
                }
                for($i = 0; $i < $this->size_b; $i++){

                    $this->xValues_b[$i] = (float)$absords[$i][0];
                    $this->yValues_b[$i] = (float)$absords[$i][1];
                }
            }

            function InterpolateLagrangePolynomialForBasic() {

                $lagrangePol = 0;
    
                for ($i = 0; $i < $this->size_b; $i++) {
                            
                    $basicsPol = 1;
                    for ($j = 0; $j < $this->size_b; $j++) {
                                
                        if ($j != $i) {
                                    
                            $basicsPol *= ($this->x - (double)$this->xValues_b[$j])/((double)$this->xValues_b[$i] - (double)$this->xValues_b[$j]);
                        }
                    }
                    $lagrangePol += $basicsPol * (double)$this->yValues_b[$i];
                }
                $this->y = $lagrangePol;
            }

            function AddToBasic() {

                $neededkey;

                for ($i = 0; $i < $this->size_b; $i++) {

                    if($this->x < (double)$this->xValues_b[$i]) {

                        $neededkey = $i;
                        break;
                    } else {

                        $neededkey = $this->size_b;
                    }
                }
                array_splice($this->xValues_b, $neededkey, 0, $this->x);
                array_splice($this->yValues_b, $neededkey, 0, $this->y);
                $this->size_b++;
            }

            function CreateData() {

                for($i = 0; $i < $this->size_b; $i++){

                    array_push($this->graph_dat_b, ["xValues_b"=>(double)$this->xValues_b[$i], "yValues_b"=>(double)$this->yValues_b[$i]]);
                }

                for($i = 0; $i < $this->size_m_prev; $i++){

                    array_push($this->graph_dat_m, ["xValues_m"=>(double)$this->xValues_m[$i], "yValues_m"=>(double)$this->yValues_m[$i]]);
                }

                $graph_dat_file_b = 'data_b_1.json';
                $handle_b = fopen($graph_dat_file_b, 'w') or die ('Cannot open file: ' . $graph_dat_file_b);
                $graph_content_b = json_encode($this->graph_dat_b);
                fwrite($handle_b, $graph_content_b);

                $graph_dat_file_m = 'data_m_1.json';
                $handle_m = fopen($graph_dat_file_m, 'w') or die ('Cannot open file: ' . $graph_dat_file_m);
                $graph_content_m = json_encode($this->graph_dat_m);
                fwrite($handle_m, $graph_content_m);
            }

            function SetModelXYValues() {

                $this->size_m_prev = count($this->xValues_b);

                for($i = 0; $i < $this->size_m_prev; $i++) {

                    $this->xValues_m[$i] = $this->xValues_b[$i];
                    $this->yValues_m[$i] = $this->yValues_b[$i];
                }

                var_dump($this->xValues_m);
                echo "<br/>";
                var_dump($this->yValues_m);
                echo "<br/><br/>";
            }

            function InterpolateLagrangePolynomialForModel() {

                $this->size_m = (($this->size_m_prev - 1) * 2) + 1;
                $t_step = round(((round($this->xValues_m[1], 5) - round($this->xValues_m[0], 5)) / 2), 5);
                $start_point = round($this->xValues_m[0], 5);
                echo "<b>t_step:</b> ";
                var_dump($t_step);
                echo "<br/><br/>";

                for($itt = 0, $t = 0.0; $itt < $this->size_m; $itt++, $t += $t_step ) {

                    $new_x = $start_point + $t;

                    $lagrangePol = 0;
                
                    for ($i = 0; $i < $this->size_m_prev; $i++) {
                                        
                        $basicsPol = 1;
        
                        for ($j = 0; $j < $this->size_m_prev; $j++) {
                                            
                            if ($j != $i) {
                                                
                                $basicsPol *= ($new_x - (double)$this->xValues_m[$j])/((double)$this->xValues_m[$i] - (double)$this->xValues_m[$j]);
                            }
                        }
                        $lagrangePol += $basicsPol * (double)$this->yValues_m[$i];
                    }
                    $new_y = $lagrangePol;

                    echo "<b>size_m:</b> ";
                    var_dump($this->size_m);
                    echo " <b>size_m_prev:</b> ";
                    var_dump($this->size_m_prev);
                    echo " <b>new_x:</b> ";
                    var_dump($new_x);
                    echo " <b>new_y:</b> ";
                    var_dump($new_y);
                    echo "<br/>";

                    $needed_key = "pusto";

                    echo "if new_x == xValues_m[iter] => needed_key = NULL<br/>";

                    for ($iter = 0; $iter < $this->size_m_prev; $iter++) {

                        echo "<b>xValues_m[$iter]:</b> ";
                        var_dump($this->xValues_m[$iter]);
                        echo " <b>|</b> ";
                        var_dump($this->xValues_m[$iter]);
                        echo "<b> - </b>";
                        var_dump($new_x);
                        echo " <b>=</b> ";
                        var_dump($this->xValues_m[$iter] - $new_x);
                        echo " <b>|</b> <b>needed_key:</b> ";
                        var_dump($needed_key);
                        echo "<br/>";

                        if (round($new_x, 5) == round($this->xValues_m[$iter], 5)) {
                            
                            $needed_key = NULL;
                            
                            echo "<b>needed_key:</b> ";
                            var_dump($needed_key);
                            echo "<br/>";

                            break;
                        } elseif(round($this->xValues_m[$iter], 5) > round($new_x, 5)) {

                            echo "TRUE<br/>";
                            $needed_key = $iter;

                            break;
                        } else {

                            $needed_key = $this->size_m_prev;
                        }
                    }

                    if($needed_key == $this->size_m_prev){

                        echo "UPLOAD NEW<br/>";
                    }

                    if (($needed_key != "pusto") && ($needed_key != NULL)) {
                            
                        array_splice($this->xValues_m, $needed_key, 0, $new_x);
                        array_splice($this->yValues_m, $needed_key, 0, $new_y);
                        $this->size_m_prev++;
                    }
                    echo "<br/>";
                }
                var_dump($this->xValues_m);
                echo "<br/>";
                var_dump($this->yValues_m);
                echo "<br/>";
            }

            function SetTable() {

                echo "<table style = \"width: 600px;
                        border:1px #000;
                        border-style: solid;
                        border-collapse: collapse;
                        text-align: center;
                        padding: 10px;
                        vertical-align: middle\">

                        <colgroup span = \"2\"></colgroup>
                    <tr>
                        <th style = \"width: 600px;
                        height: 50px;
                        border:2px #000;
                        border-style: solid;
                        vertical-align: middle\"> xValues </th>

                        <th style = \"width: 600px;
                        height: 50px;
                        border:2px #000;
                        border-style: solid;
                        vertical-align: middle\"> yValues </th>
                    </tr>";
                
                for($i = 0; $i < $this->size_m; $i++) {

                    echo "<tr>
                            <td style = \"width: 600px;
                            height: 40px;
                            border:2px #000;
                            background: #fff;
                            border-style: solid;
                            text-align: center;
                            vertical-align: middle\">" . $this->xValues_m[$i] . "</td>

                            <td style = \"width: 600px;
                            height: 40px;
                            border:2px #000;
                            background: #fff;
                            border-style: solid;
                            text-align: center;
                            vertical-align: middle\">" . $this->yValues_m[$i] . "</td>
                        </tr>";
                }
                echo "</table>";
            }   
        }

        $test = new LagrangePolynomial("./hw_2_data_1.txt");
        $test->ExtractAbsOrds();

        $test->SetModelXYValues();
        $test->InterpolateLagrangePolynomialForModel();

        #$test->InterpolateLagrangePolynomialForBasic();
        #$test->AddToBasic();

        $test->CreateData();

?>

<html>
   <head>
      <script type = "text/javascript" src = "https://www.gstatic.com/charts/loader.js"></script>
      <script type = "text/javascript">
         google.charts.load('current', {packages: ['corechart','line']});  
      </script>
   </head>
   
   <body>
      <div id = "chart_div_b" style = "width: 1000px; height: 500px"></div>
      <div id = "chart_div_m" style = "width: 1000px; height: 500px"></div>
      <script language = "JavaScript">
         function chart_div_b() {
            var data = new google.visualization.DataTable();
            data.addColumn('number', 'flight time');
            data.addColumn('number', 'm_z_fe - basic');
            //data.addColumn('number', 'm_z_fe - model');
            data.addRows([
              <?php
                $b = file_get_contents("./data_b_1.json");
                //$m = file_get_contents("./data_m_1.json");
                $json_b = json_decode($b, true);
                //$json_m = json_decode($m, true);

                for ($i = 0; $i <= (count($json_b)-1); $i++) {
                  echo "["
                  . $json_b[$i]['xValues_b'] . ",  "
                  . $json_b[$i]['yValues_b']/* . ",  "
                  . $json_m[$i]['yValues_m']*/
                  . "],";
                }
              ?>
            ]);
            
            var options = {
              'title' : '1b',
              'width': 1000,
              'height': 500,
              curveType: 'function',
              colors: ['red', 'blue']
            };

            var chart = new google.visualization.LineChart(document.getElementById('chart_div_b'));
            chart.draw(data, options);
         }
         google.charts.setOnLoadCallback(chart_div_b);

         function chart_div_m() {
            var data = new google.visualization.DataTable();
            data.addColumn('number', 'flight time');
            //data.addColumn('number', 'm_z_fe - basic');
            data.addColumn('number', 'm_z_fe - model');
            data.addRows([
              <?php
                //$b = file_get_contents("./data_b_1.json");
                $m = file_get_contents("./data_m_1.json");
                //$json_b = json_decode($b, true);
                $json_m = json_decode($m, true);

                for ($i = 0; $i <= (count($json_m)-1); $i++) {
                  echo "["
                  . $json_m[$i]['xValues_m'] . ",  "
                  . $json_m[$i]['yValues_m']/* . ",  "
                  . $json_m[$i]['yValues_m']*/
                  . "],";
                }
              ?>
            ]);
            
            var options = {
              'title' : '1m',
              'width': 1000,
              'height': 500,
              curveType: 'function',
              colors: ['blue', 'red']
            };

            var chart = new google.visualization.LineChart(document.getElementById('chart_div_m'));
            chart.draw(data, options);
         }
         google.charts.setOnLoadCallback(chart_div_m);

        </script>
        <?php
            $test->SetTable();
        ?>
    </body>
</html>