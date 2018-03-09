<html>
    <head>
        <title>Lists</title>
    </head>
    <body> 
        <?php

        $info = array('кофе', 'коричневый', 'кофеин');

        // Составить список всех переменных
        list($drink, $color, $power) = $info;
        echo "$drink - $color, а $power делает его особенным.\n";

        // Составить список только некоторых из них
        list($drink, , $power) = $info;
        echo "В $drink есть $power.\n";

        // Или только третья
        list( , , $power) = $info;
        echo "Мне нужен $power!\n";

        // list() не работает со строками
        list($bar) = "abcde";
        var_dump($bar); // NULL
        ?>
    </body>
</html>