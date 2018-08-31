<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/7/13
 * Time: 12:44
 */

namespace User\Controller;


use Think\Controller;

class WxController extends Controller
{
    const APPID ='wx43146ddee44acd70';
    const APPSECRET='e2a5b390c76be0b9c9b99795200022f4';
    const TOKEN = "ngwl";
    public function index()
    {
        $str=urlencode('http://'.$_SERVER['HTTP_HOST'].'/index.php?g=User&m=Wx&a=get_user');
        $url='https://open.weixin.qq.com/connect/oauth2/authorize?appid='.self::APPID.'&redirect_uri='.$str.'&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';
        header("location:$url");
    }

    public function get_user()
    {
        $wechatObj = new \Think\WeChat(self::APPID,self::APPSECRET,self::TOKEN);
        $code=$_GET['code'];
        $openidarr=$wechatObj->get_snsapi_base($code);

        $user_info = M('users')->where(['openid'=>$openidarr['openid'],'user_type'=>3])->find();

        $openid=$openidarr['openid'];

        if($user_info && $user_info['user_type']==3)
        {
            $_SESSION['openid'] =$openid;
            $_SESSION['user'] =$user_info['id'];
            redirect(U('User/center/index'));exit();
        }
        else
        {
            $this->display('registerbefore');
        }
    }

    public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }

    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = self::TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

    public function test()
    {
        $post['text'] = '重庆永川';
        $data = $this->curl_request('http://58.17.245.161:18008/api/project/search-project-bykeyword-10-public',$post);
        $decode = json_decode($data,true);
        dump($data);
    }

    function curl_request($url,$post='',$cookie='', $returnCookie=0){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        curl_setopt($curl, CURLOPT_REFERER, "http://XXX");
        if($post) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
        }
        if($cookie) {
            curl_setopt($curl, CURLOPT_COOKIE, $cookie);
        }
        curl_setopt($curl, CURLOPT_HEADER, $returnCookie);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($curl);
        if (curl_errno($curl)) {
            return curl_error($curl);
        }
        curl_close($curl);
        if($returnCookie){
            list($header, $body) = explode("\r\n\r\n", $data, 2);
            preg_match_all("/Set\-Cookie:([^;]*);/", $header, $matches);
            $info['cookie']  = substr($matches[1][0], 1);
            $info['content'] = $body;
            return $info;
        }else{
            return $data;
        }
    }
}