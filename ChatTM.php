<?php

class ChatTM
{
    private $chat_text;
    private $tuling_token;

    function __construct($text,$token){
        $this->chat_text = $text;
        $this->tuling_token = $token;
    }

    public function getReturnText(){
        return $this->getTulingText();
    }

    private function getTulingText(){
        $request_jsonarray = array(
            'reqType' => '0',
            'perception' => array(
                'inputText' => array(
                    'text' => $this->chat_text,
                ),
            ),
            'userInfo'=> array(
                'apiKey' => $this->tuling_token,
                'userId' => 'timecanga',
            ),
        );
        $request_json = json_encode($request_jsonarray);
        $data_package = $this->getHttpJson(1,$request_json);
        if(strcmp($data_package[0],'200') != 0){
            return $this->getWeimengText();
        }else{
            $json_de = json_decode($data_package[1],true);
            foreach ($json_de as $key => $value){
                if(strcmp(strtolower($key),'results') == 0){
                    foreach ($value[0] as $key_r=>$value_r){
                        if(strcmp(strtolower($key_r),'values') == 0){
                            foreach ($value_r as $key_t=>$value_t){
                                if(strcmp(strtolower($key_t),'text') == 0) {
                                    if((strcmp(strtolower($value_t),'请求次数超限制!') == 0) || (strcmp(strtolower($value_t),'apikey格式不合法!') == 0)){
                                        return $this->getWeimengText();
                                    }
                                    return $value_t;
                                }
                            }
                        }
                    }
                }
            }
            return $this->getWeimengText();//当没有返回时，说明没有找到对应value，则应该是出错。调用微梦返回
        }
    }

    private function getWeimengText(){
        //看得出来微梦返回的也是json，可以重新写这个方法！！！
        //--------------------------------------------------------------------
        if (strcmp($this->chat_text,"") != 0) {
            $url = "http://open.drea.cc/chat/get?keyWord=" . urlencode("$this->chat_text") . "&userName=type%3Dbbs";//此处如果其中包含中文，则无法访问
            $contents = file_get_contents($url);
            $result_1 = explode(":", $contents);//分割字符串
            $result_2 = explode("}", $result_1[3]);//再次分割
            $result = str_replace('"', '', $result_2[0]);//result_2[0]是回复的话，但是它左右两边带着双引号，此处将双引号去掉。
            return $result;
        }
    }

    private function getHttpJson($method,$json){
        $TULINGURL = "http://openapi.tuling123.com/openapi/api/v2";
        if($method == 1){
            //当为1时进行post请求(图灵机器人)
            $curl_p = curl_init();
            curl_setopt($curl_p,CURLOPT_POST,true);
            curl_setopt($curl_p,CURLOPT_URL,$TULINGURL);
            curl_setopt($curl_p,CURLOPT_POSTFIELDS,$json);
            curl_setopt($curl_p,CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl_p,CURLOPT_HTTPHEADER,array(
                'Content-Type:application/json;charset=utf-8',
                'Content-Length:'.strlen($json)
            ));
            $respense_p = curl_exec($curl_p);
            $httpcode = curl_getinfo($curl_p,CURLINFO_HTTP_CODE);
            curl_close($curl_p);
            return array($httpcode,$respense_p);
        }else{
            //否则进行get请求(微梦机器人)

        }

    }

}
?>