<?php
namespace User\Controller;

use Common\Controller\MemberbaseController;

class CenterController extends MemberbaseController {
    const APPID ='wx43146ddee44acd70';
    const APPSECRET='e2a5b390c76be0b9c9b99795200022f4';

	function _initialize(){
		parent::_initialize();
	}
	
    // 待接任务
	public function index() {
    	$this->display('index');
    }

    //获取待接任务列表
    public function getTasks()
    {
        $id = session('user');
        $post = I('post.');
        if ($post['project_name']) {
            $where['project_name'] = array('like','%'.$post['project_name'].'%');
        }

        $where['state'] = 3;
        $where['sid'] = $id;

        $tasks = M('sales_record')->where($where)->order('id desc')->select();
        foreach ($tasks as &$v)
        {
            $v['create_time'] = date('Y-m-d H:i:s',$v['create_time']);
            $v['fault_type'] = getRepairsInfo($v['fault_type']);
            $v['description'] = $v['emergency_degree']==1?'正常':($v['emergency_degree']==2?'紧急':'非常紧急');
            $v['url'] = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?g=User&m=Center&a=detail&id='.$v['id'];
        }

	
        $this->ajaxReturn(['status'=>1,'data'=>$tasks]);
    }

    public function detail()
    {
        $id = I('get.id');

        $record = M('sales_record')->where(['id'=>$id])->find();
        $record['fault_type'] = getRepairsInfo($record['fault_type']);
        $record['create_time'] = date('Y-m-d H:i:s',$record['create_time']);
        $record['degree'] = $record['emergency_degree']==1?'正常':($record['emergency_degree']==2?'紧急':'非常紧急');
        $userInfo = M('users')->where(['id'=>$record['uid']])->find();
		
		// 百度api 地址转 经纬度
		$url='http://api.map.baidu.com/geocoder/v2/?address='.$record['project_name'].'&output=json&ak=SrnEnuKohLnuaNixMwTHhDtDnWB5f2gK';
		$content = file_get_contents($url);
		$msg =json_decode($content,true);
		$address =$msg['result']['location']['lat'].','.$msg['result']['location']['lng'];	
	
		$pic = M('repair_info')->where(['record_id'=>$id,'type'=>1,'format'=>1])->select();
		$video = M('repair_info')->where(['record_id'=>$id,'type'=>1,'format'=>2])->select();
		
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
		foreach($video as $k=>$v)
		{
			if(!empty($v['url']))
			{
				$videostr[$k]['imgs'] ='http://'.$_SERVER['HTTP_HOST'].'/'.$v['url'];
			}
		}
		array_values($videostr);
		$this->assign('pic',json_encode($picstr));
        $this->assign('video',json_encode($videostr));
		$this->assign('msgaddress',$address);
        $this->assign('data',$record);
        $this->assign('info',$userInfo);
       
        $this->display();
    }

    //接收任务
    public function receive()
    {
        $id = I('post.id');

        $rst = M('sales_record')->where(['id'=>$id])->save(['state'=>4]);

        if ($rst) {
			$this->send_message($id,'售后人员已接受您的申请，将于三天内前往处理，请您耐心等待~',1);
			M('record_logs')->add(['action'=>'接单','uid'=>session('user'),'record_id'=>$id,'create_time'=>time()]);
            $this->ajaxReturn(['status'=>1]);
        }else{
            $this->ajaxReturn(['status'=>0]);
        }
    }

    // 我的任务
    public function work()
    {
        $this->display();
    }

    //获取我的任务
    public function getRecord()
    {
        $id = session('user');
        $post = I('post.');
        if ($post['project_name']) {
            $where['project_name'] = array('like','%'.$post['project_name'].'%');
        }
		
		$where['state'] = ['gt',3];

        if ($post['state']) {
            $where['state'] = $post['state'];
        }

        $where['sid'] = $id;

        $tasks = M('sales_record')->where($where)->order('id desc')->select();
        foreach ($tasks as &$v)
        {
            $v['create_time'] = date('Y-m-d H:i:s',$v['create_time']);
            $v['fault_type'] = getRepairsInfo($v['fault_type']);
            $v['description'] = $v['emergency_degree']==1?'正常':($v['emergency_degree']==2?'紧急':'非常紧急');
            $v['url'] = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?g=User&m=Center&a=work_detail&id='.$v['id'];
        }

        $this->ajaxReturn(['status'=>1,'data'=>$tasks]);
    }

    public function work_detail()
    {
        $id = I('get.id');

        $record = M('sales_record')->where(['id'=>$id])->find();
        $record['fault_type'] = getRepairsInfo($record['fault_type']);
        $record['create_time'] = date('Y-m-d H:i:s',$record['create_time']);
        $record['degree'] = $record['emergency_degree']==1?'正常':($record['emergency_degree']==2?'紧急':'非常紧急');
        $record['stateText'] = $record['state']==4?'已接收':($record['state']==5?'已处理':'已评价');
        $userInfo = M('users')->where(['id'=>$record['uid']])->find();
		// 百度api 地址转 经纬度
		$url='http://api.map.baidu.com/geocoder/v2/?address='.$record['project_name'].'&output=json&ak=SrnEnuKohLnuaNixMwTHhDtDnWB5f2gK';
		$content = file_get_contents($url);
		$msg =json_decode($content,true);
		$address =$msg['result']['location']['lat'].','.$msg['result']['location']['lng'];

		$pic = M('repair_info')->where(['record_id'=>$id,'type'=>1,'format'=>1])->select();
		$video = M('repair_info')->where(['record_id'=>$id,'type'=>1,'format'=>2])->select();
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
		foreach($video as $k=>$v)
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
		foreach($video1 as $k=>$v)
		{
			if(!empty($v['url']))
			{
				$videostr1[$k]['imgs'] ='http://'.$_SERVER['HTTP_HOST'].'/'.$v['url'];
			}
		}
		array_values($videostr1);
		
		$this->assign('pic',json_encode($picstr));
		$this->assign('pic_f',json_encode($picstr1));
        $this->assign('video',json_encode($videostr));
        $this->assign('video_f',json_encode($videostr1));
		$this->assign('msgaddress',$address);
        $this->assign('id',$id);
        $this->assign('data',$record);
        $this->assign('info',$userInfo);
        $this->display();
    }

    public function dealwith()
    {
        $id = I('get.id');

        $this->assign('id',$id);
        $this->display();
    }

    //处理信息提交
    public function deal()
    {
        $post = I('post.');

        $result = M('sales_record')->where(['id'=>$post['id']])->save(['deal_message'=>$post['deal_message'],'state'=>5]);

        if ($result) 
		{
			$pic = explode(',',$post['pic']);
			if(is_array($pic))
			{
				foreach($pic as $v)
				{
					M('repair_info')->add(['record_id'=>$post['id'],'type'=>2,'url'=>$v,'format'=>1,'create_time'=>time()]);
				}
			}else{
				M('repair_info')->add(['record_id'=>$post['id'],'type'=>2,'url'=>$pic,'format'=>1,'create_time'=>time()]);
			}
			
			$video = explode(',',$post['video']);
			if(is_array($video))
			{
				foreach($video as $v)
				{
					M('repair_info')->add(['record_id'=>$post['id'],'type'=>2,'url'=>$v,'format'=>2,'create_time'=>time()]);
				}
			}else{
				M('repair_info')->add(['record_id'=>$post['id'],'type'=>2,'url'=>$video,'format'=>2,'create_time'=>time()]);
			}
					
			M('record_logs')->add(['action'=>'处理完成','uid'=>session('user'),'record_id'=>$post['id'],'create_time'=>time()]);
			$rst = $this->send_message($post['id'],'您申请的售后服务已处理完成，赶紧前往评价吧~',2);
            $this->ajaxReturn(['status'=>1,'msg'=>$rst]);
        }else{
            $this->ajaxReturn(['status'=>0]);
        }
    }

    //推送消息
    public function send_message($id,$message,$type)
    {
        $info = M('sales_record')->where('id='.$id)->find();
        $weChatAuth = new \Com\WechatAuth(self::APPID,self::APPSECRET);
        $token=$weChatAuth->getAccessToken('client')['access_token'];
        $url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$token;

		$userInfo = M('users')->where('id='.$info['uid'])->find();
		
		 if ($type == 1) {
			$salesInfo = M('users')->where('id='.$info['sid'])->find();
            //指派、改派、接收
            $data=array(
                "touser"=>$userInfo['openid'],
                "topcolor"=>"#151516",
                "template_id"=>"IhEAY9za07THgQfieNCRncYRD-yZbZp5_vaU_PLd_Kk",
                "url"=>'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?g=&m=Index&a=details&id='.$id,
                'data'=>array(
                    'first'=>array('value'=>urlencode($message),'color'=>"#151516"),
                    'keyword1'=>array('value'=>urlencode($salesInfo['user_name']),'color'=>"#151516"),
                    'keyword2'=>array('value'=>urlencode($salesInfo['mobile']),'color'=>"#151516"),
					'keyword3'=>array('value'=>date('Y-m-d H:i:s'),'color'=>"#151516"),
                ));
        }else{
			$fault_type = getRepairsInfo($info['fault_type']);
            //驳回、评价
			$data=array(
				"touser"=>$userInfo['openid'],
				"topcolor"=>"#151516",
				"template_id"=>"Mt_dpQ8FiJ4XX17sOw8Qr_ZWQSrkdHfjt1ApFP56jGA",
				"url"=>'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?g=&m=Index&a=details&id='.$id,
				'data'=>array(
					'first'=>array('value'=>urlencode($message),'color'=>"#151516"),
					'keyword1'=>array('value'=>urlencode($fault_type),'color'=>"#151516"),
					'keyword2'=>array('value'=>date('Y-m-d H:i:s'),'color'=>"#151516"),
				));
        }

        $data=urldecode(json_encode($data));
        $res= _request($url,true,'POST',$data);
        return $res;
    }
}
