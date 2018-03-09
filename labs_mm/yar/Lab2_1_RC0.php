<?php

class flightDemo {

  public $S; // Площа крила
  public $b_A; // Середня аеродинамічна – хорда крила
  public $G; // Польотна вага
  public $X_T; // Центрівка, % САХ
  public $I_z; // Повздовжній момент інерції
  //
  public $V_0;
  public $H_0;
  public $Ro;
  public $a_n;
  public $g;
  public $C_y_0;
  public $C_y_alf;
  public $C_y_dv;
  public $C_x;
  public $m_Z_0;
  public $m_Z_omgZ;
  public $m_Z_alf1;
  public $m_Z_alf;
  public $m_Z_dv;
  //
  public $k_omg_Z;
  public $T_omg_Z;
  public $K_s;
  public $X_V;
  //
  public $T;
  public $T_d;
  public $T_f;
  public $d_T;
  public $d_d;
  //
  public $m;
  //
  public $c;
  public $x;
  public $y;
  public $N_y;
  public $dv;
  public $dvd;
  public $dvs;
  public $state;
  //
  public $charts_dat;
  //

  /* all parameters in the constructor all placed in the order, in which
  variables appear in the class declaration */

  function __construct($wing_surface,
                       $wing_chord,
                       $flight_weight,
                       $alignment,
                       $inertia,
                       $init_speed,
                       $init_height,
                       $density,
                       $an,
                       $gravity_force,
                       $cy0,
                       $cyalfa,
                       $cydv,
                       $cx,
                       $mz0,
                       $mzomgz,
                       $mzalf1,
                       $mzalf,
                       $mzdv,
                       $komgz,
                       $tomgz,
                       $ks,
                       $xv,
                       $flight_time,
                       $display_time,
                       $flight_end_time,
                       $Integration_step,
                       $output_time,
                       $switch_state) {

    $this->S = $wing_surface;
    $this->b_A = $wing_chord;
    $this->G = $flight_weight;
    $this->X_T = $alignment;
    $this->I_z = $inertia;
    $this->V_0 = $init_speed;
    $this->H_0 = $init_height;
    $this->Ro = $density;
    $this->a_n = $an;
    $this->g = $gravity_force;
    $this->C_y_0 = $cy0;
    $this->C_y_alf = $cyalfa;
    $this->C_y_dv = $cydv;
    $this->C_x = $cx;
    $this->m_Z_0 = $mz0;
    $this->m_Z_omgZ = $mzomgz;
    $this->m_Z_alf1 = $mzalf1;
    $this->m_Z_alf = $mzalf;
    $this->m_Z_dv = $mzdv;
    $this->k_omg_Z = $komgz;
    $this->T_omg_Z = $tomgz;
    $this->K_s = $ks;
    $this->X_V = $xv;
    $this->m = ($flight_weight / $gravity_force);
    $this->T = $flight_time;
    $this->T_d = $display_time;
    $this->T_f = $flight_end_time;
    $this->d_T = $Integration_step;
    $this->d_d = $output_time;
    $this->state = $switch_state;
    //
    $this->charts_dat = [];
  }

  function CreateIndexArr() {
    $this->c = [];
    $this->c[1] = ((-($this->m_Z_omgZ/$this->I_z)) * $this->S * ($this->b_A ** 2) * (($this->Ro * $this->V_0)/2));
    $this->c[2] = ((-($this->m_Z_alf/$this->I_z)) * $this->S * ($this->b_A) * (($this->Ro * ($this->V_0 ** 2))/2));
    $this->c[3] = ((-($this->m_Z_dv/$this->I_z)) * $this->S * ($this->b_A) * (($this->Ro * ($this->V_0 ** 2))/2));
    $this->c[4] = ((($this->C_y_alf + $this->C_x)/$this->m) * $this->S * (($this->Ro * $this->V_0)/2));
    $this->c[5] = ((-($this->m_Z_alf1/$this->I_z)) * $this->S * ($this->b_A ** 2) * (($this->Ro * $this->V_0)/2));
    $this->c[9] = (($this->C_y_dv/$this->m) * $this->S * (($this->Ro * $this->V_0)/2));
    $this->c[16] = ($this->V_0 / (57.3 * $this->g));
    return $this->c;
  }

  function DisplayTabHead() {
    echo "<table style = \"width: 1000px;
            border:1px #000;
            background: #828282;
            border-style: solid;
            margin-left: auto;
            margin-right: auto;
            text-align: center;
            vertical-align: middle\">

            <colgroup span = \"6\"></colgroup>
            <tr>
              <th style = \"width: 1000px;
              height: 50px;
              border:1px #000;
              background: #fff;
              border-style: solid;
              text-align: center;
              vertical-align: middle\"> T </th>

              <th style = \"width: 1000px;
              height: 50px;
              border:1px #000;
              background: #fff;
              border-style: solid;
              text-align: center;
              vertical-align: middle\"> X_V </th>

              <th style = \"width: 1000px;
              height: 50px;
              border:1px #000;
              background: #fff;
              border-style: solid;
              text-align: center;
              vertical-align: middle\"> D_V </th>

              <th style = \"width: 1000px;
              height: 50px;
              border:1px #000;
              background: #fff;
              border-style: solid;
              text-align: center;
              vertical-align: middle\"> ALF </th>

              <th style = \"width: 1000px;
              height: 50px;
              border:1px #000;
              background: #fff;
              border-style: solid;
              text-align: center;
              vertical-align: middle\"> TANG </th>

              <th style = \"width: 1000px;
              height: 50px;
              border:1px #000;
              background: #fff;
              border-style: solid;
              text-align: center;
              vertical-align: middle\"> N_Y </th>
            </tr>";
  }

  function CalculateParams() {
    $this->x = [];
    $this->y = [];

    for ($i = 1; $i <= 5; $i++) {
      $this->x[$i] = 0;
      $this->y[$i] = 0;
    }

    $this->N_y = 0;
    $this->dv = 0;
    $this->dvd = 0;

    for ($this->T; $this->T <= $this->T_f; $this->T += $this->d_T) {

      $this->x[1] = $this->y[2];
      $this->x[2] = -$this->c[1] * $this->y[2] - $this->c[2] * $this->y[4] -$this->c[5] * $this->x[4] - $this->c[3] * $this->dv;
      $this->x[3] = $this->c[4] * $this->y[4] + $this->c[9] * $this->dv;
      $this->x[4] = $this->x[1] - $this->x[3];
      $this->N_y = $this->c[16] * $this->x[3];

      for ($it = 1; $it <= 4; $it++) {
        $this->y[$it] += ($this->x[$it] * $this->d_T);
      }

      switch ($this->state) {
        case 0:
          $this->dvd = 0;
          break;
        case 1:
          $this->dvd = $this->k_omg_Z * $this->y[2];
          break;
        case 2:
          // HERE GOES MAGIC? NOBODY REALLY KNOWS WHAT THIS ACTUALLY IS
          $this->x[5] = $this->dvd;
          $this->y[5] += ($this->x[5] * $this->d_T);
          $this->dvd = ($this->k_omg_Z * $this->y[2] - ($this->y[5] / $this->T_omg_Z));
          break;

      }
      $this->dvs = ($this->K_s * $this->X_V);
      $this->dv = ($this->dvs + $this->dvd);

      for ($this->T; $this->T >= $this->T_d; $this->T_d += $this->d_d) {
        array_push($this->charts_dat, ["time"=>$this->T, "ALF"=>$this->y[4], "TANG"=>$this->y[1], "N_Y"=>$this->N_y]);
        echo "<tr>
                <td style = \"width: 1000px;
                  height: 40px;
                  border:1px #000;
                  background: #fff;
                  border-style: solid;
                  text-align: center;
                  vertical-align: middle\"> " . number_format($this->T, 1, '.', ' ') . " </td>

                  <td style = \"width: 1000px;
                  height: 40px;
                  border:1px #000;
                  background: #fff;
                  border-style: solid;
                  text-align: center;
                  vertical-align: middle\"> $this->X_V </td>

                  <td style = \"width: 1000px;
                  height: 40px;
                  border:1px #000;
                  background: #fff;
                  border-style: solid;
                  text-align: center;
                  vertical-align: middle\"> " . number_format($this->dv, 4, '.', ' ') . " </td>

                  <td style = \"width: 1000px;
                  height: 40px;
                  border:1px #000;
                  background: #fff;
                  border-style: solid;
                  text-align: center;
                  vertical-align: middle\"> " . number_format($this->y[4], 4, '.', ' ') . " </td>

                  <td style = \"width: 1000px;
                  height: 40px;
                  border:1px #000;
                  background: #fff;
                  border-style: solid;
                  text-align: center;
                  vertical-align: middle\"> " . number_format($this->y[1], 4, '.', ' ') . " </td>

                  <td style = \"width: 1000px;
                  height: 40px;
                  border:1px #000;
                  background: #fff;
                  border-style: solid;
                  text-align: center;
                  vertical-align: middle\"> " . number_format($this->N_y, 4, '.', ' ') . " </td>
              </tr>";
      }
    }
    echo "</table>";
  }
}

?>
