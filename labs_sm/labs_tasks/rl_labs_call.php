<?php
require ".\\topics.php";
Page::TopHead("RL Codes");
?>
<form action="rl_labs.php" method="post" target="_blank">
    <h4>What're you want to do with RL codes:</h4>
    <p><input list="task" name="task">
        <datalist id="task">
            <option value="Addition"></option>
            <option value="Subtraction"></option>
            <option value="Multiplication"></option>
            <option value="Division"></option>
        </datalist></p>
    <h4>Enter RL code A:</h4>
    <input type="text" name="as" size="2"/>.<input type="text" name="aa" size="2"/>&nbsp
        <input type="text" name="a0" size="2"/>.<input type="text" name="a1" size="2"/>.<input type="text" name="a2" size="2"/>.<input type="text" name="a3" size="2"/>.<input type="text" name="a4" size="2"/>.<input type="text" name="a5" size="2"/>.<input type="text" name="a6" size="2"/>.<input type="text" name="a7" size="2"/>.<input type="text" name="a8" size="2"/>.<input type="text" name="a9" size="2"/>.<input type="text" name="a10" size="2"/>.<input type="text" name="a11" size="2"/><br />
    <h4>Enter RL code B:</h4>
    <input type="text" name="bs" size="2"/>.<input type="text" name="ba" size="2"/>&nbsp
        <input type="text" name="b0" size="2"/>.<input type="text" name="b1" size="2"/>.<input type="text" name="b2" size="2"/>.<input type="text" name="b3" size="2"/>.<input type="text" name="b4" size="2"/>.<input type="text" name="b5" size="2"/>.<input type="text" name="b6" size="2"/>.<input type="text" name="b7" size="2"/>.<input type="text" name="b8" size="2"/>.<input type="text" name="b9" size="2"/>.<input type="text" name="b10" size="2"/>.<input type="text" name="b11" size="2"/><br />
    <br /><input type="submit" name="submit" value="That's all" />
</form>
<?php
Page::Bottom();
?>