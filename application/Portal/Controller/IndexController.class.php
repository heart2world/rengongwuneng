<?php
namespace Portal\Controller;
use Portal\Model\SalesRecordModel;
use Think\Controller;
use Think\Upload;

class IndexController extends Controller {
    const APPID ='wx43146ddee44acd70';
    const APPSECRET='e2a5b390c76be0b9c9b99795200022f4';
    const TOKEN = "ngwl";
    const LISTROWS = 15;
	public function index() {
        $openid = session('user_openid');
        $userInfo = M('users')->where(['openid'=>$openid,'user_type'=>2])->find();
        if (!$userInfo) {
            $str=urlencode('http://'.$_SERVER['HTTP_HOST'].'/index.php?g=Portal&m=Index&a=getUserInfo');
            $url='https://open.weixin.qq.com/connect/oauth2/authorize?appid='.self::APPID.'&redirect_uri='.$str.'&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';
            header("location:$url");
        }
	    $info = I('get.');

	    $this->assign('project_info',$info);
    	$this->display();
    }

    //我的报修
    public function service()
    {
        $this->display();
    }

    public function getServiceInfo()
    {
        $post = I('post.');
        $srmodel = new SalesRecordModel();
        $uid = session('uid');
        $page = $post['page']?$post['page']:1;

        $where['uid']=$uid;
        if ($post['s_time'] && $post['e_time']) {
            $where['create_time'] = ['between',[strtotime($post['s_time']),strtotime($post['e_time'])+24*60*60-1]];
		}

        if ($post['state']) {
            $where['state'] = $post['state'];
        }

        $count = $srmodel->where($where)->count();
        $pageSize = ceil($count/self::LISTROWS);
        $repairsInfo = $srmodel->where($where)->page($page,self::LISTROWS)->order('id desc')->select();

        foreach ($repairsInfo as &$v)
        {
            $v['url'] = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?g=&m=Index&a=details&id='.$v['id'];
            $v['create_time'] = date('Y-m-d H:i:s',$v['create_time']);
            $v['fault_type'] = getRepairsInfo($v['fault_type']);
        }

       $this->ajaxReturn(['status'=>1,'data'=>$repairsInfo,'page_size'=>$pageSize]);
    }

    //详情
    public function details()
    {
        $id = I('get.id');

        $srmodel = new SalesRecordModel();
        $record = $srmodel->where('id='.$id)->find();
        $record['fault_type'] = getRepairsInfo($record['fault_type']);
		$pic = M('repair_info')->where(['record_id'=>$id,'type'=>1,'format'=>1])->select();
		$pic1 = M('repair_info')->where(['record_id'=>$id,'type'=>2,'format'=>1])->select();
		$videos = M('repair_info')->where(['record_id'=>$id,'type'=>1,'format'=>2])->select();
		$videos1 = M('repair_info')->where(['record_id'=>$id,'type'=>2,'format'=>2])->select();
		
		$picstr=array();
		foreach($pic as $k=>$v)
		{
			if(!empty($v['url']))
			{
				$picstr[$k]['imgs'] ='http://'.$_SERVER['HTTP_HOST'].'/'.$v['url'];
			}
		}
		array_values($picstr);
		$videostr=array();
		foreach($videos as $k=>$v)
		{
			if(!empty($v['url']))
			{
				$videostr[$k]['imgs'] ='http://'.$_SERVER['HTTP_HOST'].'/'.$v['url'];
			}
		}
		
		$pic1 = M('repair_info')->where(['record_id'=>$id,'type'=>2,'format'=>1])->select();
		$video1 = M('repair_info')->where(['record_id'=>$id,'type'=>2,'format'=>2])->select();
		$picstr1=array();
		foreach($pic1 as $k=>$v)
		{
			if(!empty($v['url']))
			{
				$picstr1[$k]['imgs'] ='http://'.$_SERVER['HTTP_HOST'].'/'.$v['url'];
			}
		}
		array_values($picstr1);
		$videostr1=array();
		foreach($videos1 as $k=>$v)
		{
			if(!empty($v['url']))
			{
				$videostr1[$k]['imgs'] ='http://'.$_SERVER['HTTP_HOST'].'/'.$v['url'];	
			}
		}
		array_values($videostr1);
		
		$sales_info = M('users')->where('id='.$record['sid'])->find();

        $this->assign('data',$record);
        $this->assign('info',$sales_info);
        $this->assign('pic',json_encode($picstr));
		$this->assign('pic_f',json_encode($picstr1));
        $this->assign('video',json_encode($videostr));
        $this->assign('video_f',json_encode($videostr1));
        $this->display();
    }

    public function chose_project()
    {
        $this->display();
    }

    //获取项目信息
    public function getProject()
    {
        $post['text'] = I('post.keyword');

        $data = curl_request('http://120.79.207.152:18008/api/project/search-project-bykeyword-10-public',$post);
        if ($data) {
            $data = json_decode($data,true);
            foreach ($data['data']['records'] as &$v)
            {
                $v['url'] = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?project_name='.$v['projectName'].'&address='.$v['address'];
            }
            $this->ajaxReturn(['status'=>1,'data'=>$data]);
        }else{
            $this->ajaxReturn(['status'=>0,'data'=>$data]);
        }
    }

    //获取用户信息
    public function getUserInfo()
    {
        $wechatObj = new \Think\WeChat(self::APPID,self::APPSECRET,self::TOKEN);
        $code=$_GET['code'];
        $openidarr=$wechatObj->get_snsapi_base($code);

        $user_info = M('users')->where(['openid'=>$openidarr['openid'],'user_type'=>2])->find();

        $access_token=$openidarr['access_token'];
        $openid=$openidarr['openid'];

        $info=$wechatObj->get_snsapi_userinfo($access_token, $openid);

        if (!$user_info) {
            $user_info['id'] = M('users')->add(['openid'=>$info['openid'],'nick_name'=>$info['nickname'],'avatar'=>$info['headimgurl'],'user_type'=>2]);
        }else{
            M('users')->where(['id'=>$user_info['id']])->save(['avatar'=>$info['headimgurl']]);
        }

        $_SESSION['user_openid'] =$openidarr['openid'];
        $_SESSION['uid'] = $user_info['id'];
        redirect(U('Portal/Index/index'));exit();
    }

    //报修信息提交
    public function repairs_post()
    {
        if (IS_POST) {
            $post = I('post.');
            $post['uid'] = session('uid');
			$post['create_time'] = time();
            $srmodel  = new SalesRecordModel();
            if ($srmodel->create()) {
				$id = $srmodel->add($post);
                if ($id) {
					$pic = explode(',',$post['pic']);
					if(is_array($pic))
					{
						foreach($pic as $v)
						{
							M('repair_info')->add(['record_id'=>$id,'type'=>1,'url'=>$v,'format'=>1,'create_time'=>time()]);
						}
					}else{
						M('repair_info')->add(['record_id'=>$id,'type'=>1,'url'=>$pic,'format'=>1,'create_time'=>time()]);
					}
					
					$video = explode(',',$post['video']);
					if(is_array($video))
					{
						foreach($video as $v)
						{
							M('repair_info')->add(['record_id'=>$id,'type'=>1,'url'=>$v,'format'=>2,'create_time'=>time()]);
						}
					}else{
						M('repair_info')->add(['record_id'=>$id,'type'=>1,'url'=>$video,'format'=>2,'create_time'=>time()]);
					}
					
					M('record_logs')->add(['action'=>'申请售后','uid'=>session('uid'),'record_id'=>$id,'create_time'=>time()]);
					$this->ajaxReturn(['status'=>1]);
                }else{
                    $this->ajaxReturn(['status'=>0,'msg'=>$srmodel->getError()]);
                }
            }else{
                $this->ajaxReturn(['status'=>0,'msg'=>$srmodel->getError()]);
            }
        }
    }

    //评价
    public function evaluate()
    {
        $id = I('get.id');

        $this->assign('id',$id);
        $this->display();
    }

    //保存评价
    public function save_evaluate()
    {
        $post = I('post.');
        $srmodel = new SalesRecordModel();
        $srmodel->total_ate = $post['data'][0]['val'];
        $srmodel->timeliness_ate = $post['data'][1]['val'];
        $srmodel->attitude_ate = $post['data'][2]['val'];
        $srmodel->proffesional_ate = $post['data'][3]['val'];
		$srmodel->state = 6;
        $result = $srmodel->where('id='.$post['id'])->save();

        if ($result) 
		{	
			M('record_logs')->add(['action'=>'评价','uid'=>session('uid'),'record_id'=>$post['id'],'create_time'=>time()]);
            $this->ajaxReturn(['status'=>1]);
        }else{
            $this->ajaxReturn(['status'=>0]);
        }
	}
	
	//保存图片
	public function save_upload()
    {
        $upload = new Upload();
		
        foreach ($_FILES as $k=>$v)
        {
            if(count($_FILES[$k]) == count($_FILES[$k],1)){//判断$_FILES变量是否是二维数组
                $info = $upload->uploadOne($_FILES[$k]);// 如果不是二维数组，使用单文件依次上传的方法
                unset($_FILES[$k]);
                $arr[$k] = $info;
                if(!$info){
                    $this->ajaxReturn(['status'=>0,'msg'=>$upload->getError()]);
                    exit;
                }
            }
        }
        if(count($_FILES)){
            $info = $upload->upload();// 如果是二维数组，使用批量上传文件的方法(上传文件时，每个文件域的name属性是未知的或者以数组形式定义的)
            if(!$info){
                $this->ajaxReturn($upload->getError());
                exit;
            }
            $arr['array'] = $info;//数组上传的返回信息全部在键名为array的
        }
        $this->ajaxReturn($arr);
    }
	
	public function save_vedio()
	{
		$upload = new Upload();
		
		$info = $upload->uploadOne($_FILES);// 如果不是二维数组，使用单文件依次上传的方法
		unset($_FILES[$k]);
		$arr[$k] = $info;
		if(!$info){
			$this->ajaxReturn(['status'=>0,'msg'=>$upload->getError()]);
			exit;
		}
	}
}


