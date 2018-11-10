var express = require("express");
var router = express.Router();

router.get("/", function(req, res, next) {
  var arrKurs = mat1(1, 4);
  var arrShlyax = mat1(2, 4);
  var arrMarshrut = mat1(3, 4);

  res.render("index", {
    title: "Express",
    with1method: arrKurs,
    with2method: arrShlyax,
    with3method: arrMarshrut
  });
});

function mat1(sposib, znach) {
  var resultArr = [];
  var arrZ = [];
  var arrX = [];

  var a1 = 0.10330992711273342;
  var a2 = 1.5395081306139728;
  var a3 = 0.641237825200468;
  var a4 = 0.0924402163430025;
  var a5 =0.5388278457148905;
  var a6=0.27430566854070587;
  var a7=0.0189182478821805;
  var q= 1035.9456;
  var A_bal =6.328194510145747;
  var b1 =1.6458340112442356;
  var b2=3.5921856380992696;
  var b3=1.3478532498976314;
  var b4=0.040977636616236136;
  var b5=0;
  var b6=0.004274893535699313;
  var b7=0.11021532750613282;
  var C6 = 1.6963350785340316;

  var x = [0,0,0,0,0,0,0,0];
  x[5] = 1;
  var y = [0,0,0,0,0,0,0,0];

  y[5] = -50000;
  y[7] = 20000;

  switch (znach) {
    case 1:
      y[6] = 0;
      var W = 0;
      var HB = 0;
      break;

    case 2:
      y[6] = -2000;
      var W = 0;
      var HB = 0;
      break;

    case 3:
      y[6] = 0;
      var W = 40;
      var HB = 0;
      break;

    case 4:
      y[6] = 0;
      var W = 40;
      var HB = 180;
      break;

    case 5:
      y[6] = 0;
      var W = 40;
      var HB = 135;
      break;

    default:
      break;
  }

  var Vo = 236;

  var DN,
    DE,
    Bb,
    Bw,
    Wz,
    Wx,
    Psi_g,
    V_sh,
    P_zt,
    KKzt,
    y_zad,
    delta_ShK,
    ShK,
    zd;

  var myK = 0;

  for (var T = 0; T < 248; T = T + 0.05) {
    Psi_g = -y[0];

    Wx = W * Math.cos((HB - Psi_g) / 57.3);
    Wz = W * Math.sin((HB - Psi_g) / 57.3);

    Bw = (-57.3 * Wz) / Vo;
    Bb = y[4] + Bw;

    V_sh = Vo + Wx;

    P_zt = 57.3 * Math.atan(y[6] / y[5]);

    x[5] = V_sh * Math.cos((Psi_g + y[4]) / 57.3);
    x[6] = V_sh * Math.sin((Psi_g + y[4]) / 57.3);

    x[7] = -3 * 0.585;

    switch (sposib) {
      case 1:
        KKzt = P_zt - Psi_g;

        if (0.7 * V_sh * Math.sin(KKzt / 57.3) > 20) {
          y_zad = 20;
        } else if (0.7 * V_sh * Math.sin(KKzt / 57.3) < -20) {
          y_zad = -20;
        } else {
          y_zad = 0.7 * V_sh * Math.sin(KKzt / 57.3);
        }

        break;

      case 2:
        ShK = 57.3 * Math.atan(x[6] / x[5]);
        delta_ShK = P_zt - ShK;
        zd = 0.7 * V_sh * Math.sin(delta_ShK / 57.3);

        if (zd > 20) {
          y_zad = 20;
        } else if (zd < -20) {
          y_zad = -20;
        } else {
          y_zad = zd;
        }

        break;

      case 3:
        if (-(0.02 * y[6] + 0.7 * x[6]) > 20) {
          y_zad = 20;
        } else if (-(0.02 * y[6] + 0.7 * x[6]) < -20) {
          y_zad = -20;
        } else {
          y_zad = -(0.02 * y[6] + 0.7 * x[6]);
        }
        break;

      default:
        break;
    }

    DE = 2 * (y[1] - y_zad) + 1.5 * y[3];
    DN = 2.5 * y[2];

    x[0] = y[2];
    x[1] = y[3];
    x[2] = -a1 * x[0] - b6 * x[1] - a2 * Bb - a3 * DN - b5 * DE;
    x[3] = -a6 * x[0] - b1 * x[1] - b2 * Bb - a5 * DN - b3 * DE;
    x[4] = x[0] + b7 * x[1] + b4 * y[1] + a4 * Bb - a7 * DN;

    for (var i = 0; i < 8; i++) {
      y[i] += x[i] * 0.05;
    }

    if ((Math.round(T * 100) / 100) % 5 == 0) {
      if (T === 0) {
        console.log(
          "T" +
            "        " +
            "Gamma" +
            "       " +
            "PsiG" +
            "       " +
            "X" +
            "       " +
            "Z" +
            "       " +
            "Gp"
        );
      }
      console.log(
        Math.round(T * 100) / 100 +
          "        " +
          Math.round(-y[1] * 100000) / 100000 +
          "       " +
          Math.round(Psi_g * 100000) / 100000 +
          "       " +
          Math.round(y[5] * 100000) / 100000 +
          "       " +
          Math.round(y[6] * 100000) / 100000 +
          "       " +
          Math.round(y[7] * 100000) / 100000
      );
    }

    arrX[myK] = y[5];
    arrZ[myK] = -y[6];

    myK++;
  }

  for (var i = 0; i < 4960; i++) {
    resultArr[i] = [];
    resultArr[i][0] = arrX[i];
    resultArr[i][1] = arrZ[i];
  }

  return resultArr;
}

module.exports = router;
