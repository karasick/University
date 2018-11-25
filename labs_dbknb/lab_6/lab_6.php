<?php
include 'html/main_page.html';
ini_set('error_reporting', E_ALL);

$server = 'localhost';
$username = 'root';
$password = '';
$db = 'university';

$link = mysqli_connect($server, $username, $password, $db);
if(mysqli_connect_errno()) {
    echo "Error (" . mysqli_connect_errno() . "): " . mysqli_connect_error();
    exit();
} else {
    $sql = "SELECT * FROM `boards` WHERE 
    `description` LIKE 'M%' OR 
    `description` LIKE 'N%' OR 
    `description` LIKE 'O%' OR 
    `description` LIKE 'P%' OR 
    `description` LIKE 'Q%' OR 
    `description` LIKE 'R%' OR
    `description` LIKE 'S%' ORDER BY `boards`.`description` ASC";
    $result = mysqli_query($link, "SET NAMES utf8");
    $result = mysqli_query($link, $sql);
    $table = mysqli_fetch_all($result, MYSQLI_ASSOC);

    echo "<div class=\"section no-pad-bot scrollspy\" id=\"task_1\">
        <div class=\"container\">
            <h4>Task 1:</h4>
        </div>
        <div class=\"container\">
            <table width=\"100%\" cellspacing=\"0\" border=\"1\" class=\"highlight\">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Headline</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Amount of threads</th>
                    </tr>
                </thead>
                <tbody>";
                foreach ($table as $row) {
                    echo "<tr>" .
                        "<th>" . $row['id'] . "</th>" .
                        "<th>" . $row['headline'] . "</th>" .
                        "<th>" . $row['type'] . "</th>" .
                        "<th>" . $row['description'] . "</th>" .
                        "<th>" . $row['amount_of_threads'] . "</th>" .
                    "</tr>";
                }
                echo "</tbody>
            </table>
        </div>
    </div>";
    echo "<div class=\"section no-pad-bot scrollspy\" id=\"task_2\">
        <div class=\"container\">
            <h4>Task 2:</h4>
        </div>";
    echo "</div>";
}
?>
<html>
<body>
    </main>
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
        function scrollToTop() {
            var graphics = document.getElementById("top-nav");
            graphics.scrollIntoView({block: "start", behavior: "smooth"});
        }
    </script>
</body>
</html>


