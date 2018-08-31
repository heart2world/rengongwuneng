<?php
namespace User\Controller;

use Common\Controller\HomebaseController;
use Common\Model\UsersModel;

class RegisterController extends HomebaseController {
    const APPID ='wx43146ddee44acd70';
    const APPSECRET='e2a5b390c76be0b9c9b99795200022f4';
    const TOKEN = "ngwl";

	public function index(){
        $str=urlencode('http://'.$_SERVER['HTTP_HOST'].'/index.php?g=User&m=Register&a=register');
        $url='https://open.weixin.qq.com/connect/oauth2/authorize?appid='.self::APPID.'&redirect_uri='.$str.'&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';
        header("location:$url");
	}

	//注册页面
	public function register()
    {
        $wechatObj = new \Think\WeChat(self::APPID,self::APPSECRET,self::TOKEN);
        $code=$_GET['code'];
        $openidarr=$wechatObj->get_snsapi_base($code);
        $access_token=$openidarr['access_token'];
        $openid=$openidarr['openid'];
		
		$userinfo = M('users')->where(['openid'=>$openid,'user_type'=>3])->find();
		if ($userinfo) {
            $this->redirect('User/Center/index');
        }

        $info=$wechatObj->get_snsapi_userinfo($access_token, $openid);

        $this->assign('info',$info);
        $this->display();
    }

    //保存信息
    public function save_info()
    {
        $post = I('post.');
        $userModel = new UsersModel();
        $userModel->openid = $post['openid'];
        $userModel->avatar = $post['avatar'];
        $userModel->user_name = $post['user_name'];
        $userModel->mobile = $post['mobile'];
		$userModel->user_type = 3;
        $id = $userModel->add();

        if ($id) {
            foreach ($post['area'] as $v)
            {
                $arr = explode(' ',$v['areas']);
                $result = M('area')->add(['province'=>$arr[0],'city'=>$arr[1],'district'=>$arr[2],'uid'=>$id,'create_time'=>time()]);
            }
            if ($result != false) {
				session('openid',$post['openid']);
				session('user',$id);
                $this->ajaxReturn(['status'=>1,'msg'=>'注册成功']);
            }
        }else{
            $this->ajaxReturn(['status'=>0,'msg'=>'用户注册信息失败']);
        }
    }

}