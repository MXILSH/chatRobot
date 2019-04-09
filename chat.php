<?php
include "ChatTM.php";
$_BACKGROUND_COLOR = "#2196F3";
$_TULING_API = "";
$chat_history = "";
if(isset($_COOKIE["chathistory"])){
    $chat_history = $_COOKIE["chathistory"];
}
if(isset($_GET['chat-value'])) {
    $chat_value = $_GET['chat-value'];
    if (strcmp($chat_value,"") != 0) {
        //构造实例返回数据
        $chat_obj = new ChatTM($chat_value,$_TULING_API);
        $rt_text = $chat_obj->getReturnText();
        //判断字数短句？-----------------------------------------------------------
        //
        //聊天历史
        $chat_history = $chat_history."|".$chat_value."|".$rt_text;
        setcookie("chathistory",$chat_history);
    }
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="mui/css/mui.min.css" rel="stylesheet" type="text/css" />
    <script src="mui/js/mui.min.js"></script>
</head>
<body>
<div style="width:100%;height:100%;margin: auto">
    <table width="100%">
    <?php
    if(strcmp($chat_history,"") != 0){
        $temp = explode("|",$chat_history);
        $i = 1;
        foreach ($temp as $value){
            if(strcmp($value,"") == 0){
                continue;
            }
            if($i % 2 != 0){
                echo "<tr>", "<td align=\"right\"><button class=\"mui-btn\" disabled style=\"background-color:$_BACKGROUND_COLOR;color:white;\">$value</button></td>", "</tr>";
            }else{
                echo "<tr>", "<td align=\"left\"><button class=\"mui-btn\" style=\"background-color:$_BACKGROUND_COLOR;color:white;\">$value</button></td>", "</tr>";
            }
            $i = $i + 1;
        }
    }
    ?>
    </table>

</div>
</body>
</html>