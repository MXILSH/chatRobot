<?php
include "ChatTM.php";
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
        //聊天历史
        $chat_history = $chat_history."|".$chat_value."|".$rt_text;
        setcookie("chathistory",$chat_history);
    }
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">

		<!--标准mui.css-->
		<link rel="stylesheet" href="mui/css/mui.min.css">
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
                    echo "<div align=\"right\"><div class=\"mui-card\" style=\"width:50%\"><div class=\"mui-card-content\"><div class=\"mui-card-content-inner\" style=\"background-color:#2196F3;color:white;\">".$value."</div></div></div></div>";
                }else{
                    echo "<div align=\"left\"><div class=\"mui-card\" style=\"width:50%\"><div class=\"mui-card-content\"><div class=\"mui-card-content-inner\" style=\"background-color:#2196F3;color:white;\">".$value."</div></div></div></div>";
                }
                $i = $i + 1;
            }
        }
        ?>
        </table>

    </div>
    </body>
</html>