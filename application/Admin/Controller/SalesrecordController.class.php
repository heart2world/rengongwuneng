<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/7/11
 * Time: 17:21
 */

namespace Admin\Controller;


use Common\Controller\AdminbaseController;

class SalesrecordController extends AdminbaseController
{
    const APPID ='wx43146ddee44acd70';
    const APPSECRET='e2a5b390c76be0b9c9b99795200022f4';
    protected $sales_record_model,$users_model,$record_logs_model;

    public function _initialize() {
        parent::_initialize();
        $this->sales_record_model = D("Common/SalesRecord");
        $this->users_model = D("Common/Users");
        $this->record_logs_model = D("Common/RecordLogs");
    }

    public function index()
    {
        /**搜索条件**/
        $condition = I('request.');
        if($condition['project_name']){
            $where['project_name'] = array('like',"%".$condition['project_name']."%");
        }

        if ($condition['state']) {
            $where['state'] = $condition['state'];
        }

        if ($condition['start_time'] || $condition['end_time']) {
            $s_time = strtotime($condition['start_time']);
            $e_time = strtotime($condition['end_time']);
            $where['create_time'] = array('between',[$s_time,$e_time]);
        }

        $count = $this->sales_record_model->where($where)->count();
        $page = $this->page($count, 20);
        $records = $this->sales_record_model->where($where)->order('id desc')->limit($page->firstRow, $page->listRows)->order('time_state desc,create_time desc')->select();
       
        foreach ($records as &$v)
        {
			if($v['uid']){
                $uinfo = M('users')->where(['id'=>$v['uid']])->find();
				$v['avatar'] =$uinfo['avatar'];
				$v['nick_name'] =$uinfo['nick_name'];
				$v['openid'] =$uinfo['openid'];
            }
            if($v['sid']){
                $v['s_info'] = M('users')->where(['id'=>$v['sid']])->find();
            }
        }
        $sales_member = $this->users_model->where(['user_type'=>3,'user_status'=>1])->order('id desc')->select();
		foreach($sales_member as &$v)
		{
			$v['area'] = M('area')->where('uid='.$v['id'])->select();
		}
		
		$arr = json_encode($sales_member,true);
		
		if(I('request.project_name')){
            $pname = I('request.project_name');
            $this->assign('pname',$pname);
        }
        if(I('request.start_time')){
            $stime = I('request.start_time');
            $this->assign('stime',$stime);
        }
        if(I('request.end_time')){
            $etime = I('request.end_time');
            $this->assign('etime',$etime);
        }
		if(I('request.state')){
			$state = I('request.state');
			$this->assign('state',$state);
        }

        $this->assign('page',$page->show('Admin'));
        $this->assign('records',$records);
        $this->assign('sales',$sales_member);
        $this->assign('arr',$arr);
        $this->display();
    }

    //点击查看详情跳转到该方法
    public function check(){
        //获取传过来的ID，便于查找详情
        $id = I('get.id');
        $where['id'] = $id;
        $model=M('sales_record');
        $row=$model->where($where)->find();
        $row['create_time']=date("Y-m-d H:i:s",$row['create_time']);
        //用户ID
        $where2['id']=$row['uid'];
        //处理人ID
        $where3['id']=$row['sid'];
        //报修信息关联ID
        $where4['record_id']=$id;
        $where4['type']=1;
        //处理结果信息关联ID
        $where5['record_id']=$id;
        $where5['type']=2;
        $usermodel=M('users');
        //查询到用户信息
        $user_info=$usermodel->where($where2)->find();
        //查询到处理人信息
        $cluser_info=$usermodel->where($where3)->find();

        $row['cluser_name']=$cluser_info['user_name'];
        $row['clmobile']=$cluser_info['mobile'];

        //报修信息图片查询
        $repairdmodel=M('repair_info');
        $repaird_info=$repairdmodel->where($where4)->select();
        //处理结果信息图片查询
        $cljg_info=$repairdmodel->where($where5)->select();
		
        //分发部分
        $this->assign('repaird_info',$repaird_info);
        $this->assign('cljg_info',$cljg_info);
        $this->assign('check',$row);
        $this->display();


    }
//excel导出
    public function exportExcel($expTitle,$expCellName,$expTableData){

        $xlsTitle = iconv('utf-8', 'gb2312', $expTitle);//文件名称
        $fileName = date('YmdHis');//or $xlsTitle 文件名称可根据自己情况设定
        $cellNum = count($expCellName);
        $dataNum = count($expTableData);

        vendor("PHPExcel.PHPExcel");

        $objPHPExcel = new \PHPExcel();

        // $cellName = array('A','B','C','D','E','F','G','H','I','J');

        $cellName = array('A','B','C','D','E','F','G','H','I','J',
            'K','L','M','N','O','P','Q','R','S','T','U',
            'V','W','X','Y','Z','AA','AB','AC','AD','AE',
            'AF','AG','AH','AI','AJ','AK','AL','AM','AN',
            'AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ');

        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
//        $objPHPExcel->getActiveSheet(0)->mergeCells('A1:'.$cellName[$cellNum-1].'1');//合并单元格
//         $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $expTitle.'  Export time:'.date('Y-m-d H:i:s'));

        //处理表数据
        for($i=0;$i<$cellNum;$i++){
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i].'1', $expCellName[$i][1]);
        }
        // Miscellaneous glyphs, UTF-8
        for($i=0;$i<$dataNum;$i++){
            for($j=0;$j<$cellNum;$j++){
                $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j].($i+2), $expTableData[$i][$expCellName[$j][0]]);
            }
        }
        header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$xlsTitle.'.xls"');
        header("Content-Disposition:attachment;filename=$fileName.xls");//attachment新窗口打印inline本窗口打印
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');//Excel5为xls格式，excel2007为xlsx格式
        $objWriter->save('php://output');
        exit;
    }

    /**
     * 导出数据表的数据
     */
    function export()
    {//导出Excel
        //数据库中的数据表
        $xlsName = "Stamp";
        $xlsCell = array(
            array('id', '编号'),
            array('create_time', '申请时间'),
            array('project_name', '项目名称'),
            array('user_name', '申请人'),
            array('mobile', '联系方式'),
            array('fault_type', '故障类型'),
            array('emergency_degree', '紧急程度'),
            array('sid', '售后人员'),
            array('state', '状态'),
            array('total_ate', '1.总体评价'),
            array('timeliness_ate', '2.服务及时性'),
            array('attitude_ate', '3.服务态度'),
            array('proffesional_ate', '4.专业性评价'),
        );
//        $data['is_deleted'] = 0;
        $xlsModel = M('sales_record');
        //导出所有的内容
        $xlsData = $xlsModel->
        Field('id,create_time,project_name,user_name,mobile,fault_type,emergency_degree,
				sid,state,total_ate,timeliness_ate,attitude_ate,
				proffesional_ate')
            //->order('id desc')
            ->select();
        //excel里面字段处理存表
        foreach($xlsData as $k=>$v){
            //时间处理
            $xlsData[$k]['create_time']=date("Y-m-d H:i:s",$v['create_time']);
            //售后人员信息处理
            $where2['id']=$v['sid'];
            $shmodel=M('users');
            $sh_info=$shmodel->where($where2)->find();
//            var_dump($sh_info);
//            die();
            $newinfo=array();
            $newinfo['user_name']=$sh_info['user_name'];
            $newinfo['mobile']=$sh_info['mobile'];

            $xlsData[$k]['sid']=implode("/",$newinfo);

            //评价字段处理
            if($v['total_ate']){
                  $xlsData[$k]['total_ate'] =substr($v['total_ate'],0,1);
              }
            if($v['timeliness_ate']){
                $xlsData[$k]['timeliness_ate'] =substr($v['timeliness_ate'],0,1);
            }
            if($v['attitude_ate']){
                $xlsData[$k]['attitude_ate'] =substr($v['attitude_ate'],0,1);
            }
            if($v['proffesional_ate']){
                $xlsData[$k]['proffesional_ate'] =substr($v['proffesional_ate'],0,1);
            }

            //故障类型处理
            if($v['fault_type']=='1'){
                $xlsData[$k]['fault_type']='塔机故障';
            }
            if($v['fault_type']=='2'){
                $xlsData[$k]['fault_type']='升降机故障';
            }
            if($v['fault_type']=='3'){
                $xlsData[$k]['fault_type']='扬尘故障';
            }
            if($v['fault_type']=='4'){
                $xlsData[$k]['fault_type']='视频故障';
            }
            if($v['fault_type']=='5'){
                $xlsData[$k]['fault_type']='门禁故障';
            }

            //紧急程度处理
            if($v['emergency_degree']=='1'){
                $xlsData[$k]['emergency_degree']='正常';
            }
            if($v['emergency_degree']=='2'){
                $xlsData[$k]['emergency_degree']='紧急';
            }
            if($v['emergency_degree']=='3'){
                $xlsData[$k]['emergency_degree']='非常紧急';
            }

            //状态处理
            if($v['state']=='1'){
                $xlsData[$k]['state']='待指派';
            }
            if($v['state']=='2'){
                $xlsData[$k]['state']='已驳回';
            }
            if($v['state']=='3'){
                $xlsData[$k]['state']='指派中';
            }
            if($v['state']=='4'){
                $xlsData[$k]['state']='已接收';
            }
            if($v['state']=='5'){
                $xlsData[$k]['state']='已处理';
            }
            if($v['state']=='6'){
                $xlsData[$k]['state']='已评价';
            }
        }

        /* 调用导出方法 */
        $this->exportExcel($xlsName, $xlsCell, $xlsData);
    }


    //超时设置
    public function config()
    {
        $config = M('config')->where(['config_name'=>'timeout'])->find();

        $this->assign('config',$config);
        $this->display();
    }

    //添加编辑配置
    public function save_config()
    {
        if (IS_POST) {
            $post = I('post.');
            if (!empty($post['id'])) {
                $result = M('config')->save($post);
				$config_time = M('config')->where(['id=1'])->find();
				$time = $config_time['param']*24*60*60;
				 
				$list =M('sales_record')->where("state=4")->select();
				foreach($list as $k=>$v)
				{
					M('sales_record')->where("id='".$v['id']."'")->setField('time_state',0);
					$v['userinfo'] = M('users')->where(['id'=>$v['uid']])->find();
					if ($v['create_time'] + $time < time()) {
						M('sales_record')->where("id='".$v['id']."'")->setField('time_state',1);
					}
				}
            }else{
                $post['config_name'] = 'timeout';
                $post['create_time'] = time();
                $result = M('config')->add($post);
            }

            if ($result != false) {
                $this->ajaxReturn(['status'=>0,'msg'=>'保存成功']);
            }else{
                $this->ajaxReturn(['status'=>0,'msg'=>'保存失败']);
            }
        }
    }

    //指派信息修改
    public function reassign()
    {
		$post = I('post.');
        if (IS_POST) {
            if ($this->sales_record_model->create()) {
                if ($this->sales_record_model->save())
                    $url = '?g=&m=Index&a=details&id='.$post['id'];
                    $this->send_message($post['id'],'感谢您的耐心等待，您申请的售后服务已受理~',$url,1);
                    if($post['step'] == 1)
					{
						$step ='指派';
					}else{
						$step ='改派';
					}
					
					//售后人员推送
					$info = $this->sales_record_model->where('id='.$post['id'])->find();
					$weChatAuth = new \Com\WechatAuth(self::APPID,self::APPSECRET);
					$token=$weChatAuth->getAccessToken('client')['access_token'];
					$url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$token;
					
					$type = getRepairsInfo($info['fault_type']);
					$userInfo = M('users')->where('id='.$info['sid'])->find();
					$data1=array(
							"touser"=>$userInfo['openid'],
							"topcolor"=>"#151516",
							"template_id"=>"tVFDIdAPeiEKVf8pqnAMCkPZB07AzU6E9nkZvbwaEQ8",
							"url"=>'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?g=User&m=Center&a=detail&id='.$post['id'],
							'data'=>array(
							'first'=>array('value'=>urlencode('您有新的指派任务【'.$info['project_name'].'】，赶紧去处理吧~'),'color'=>"#151516"),
							'HandleType'=>array('value'=>urlencode($type),'color'=>"#151516"),
							'RowCreateDate'=>array('value'=>date('Y-m-d H:i:s'),'color'=>"#151516"),
							'Status'=>array('value'=>urlencode("已指派"),'color'=>"#151516"),
							'LogType'=>array('value'=>urlencode("待接收"),'color'=>"#151516"),
							));
			
					$data1=urldecode(json_encode($data1));
					$res= _request($url,true,'POST',$data1);
					
					$this->record_logs_model->add(['action'=>$step,'record_id'=>$post['id'],'uid'=>session('ADMIN_ID'),'create_time'=>time()]);
                    $this->ajaxReturn(array('status' =>0,'msg'=>'指派成功！'));
                }
                $this->ajaxReturn(array('status' =>1,'msg'=>'指派失败！'));
            }
            $this->ajaxReturn(array('status' =>1,'msg'=>$this->sales_record_model->getError()));
    }

    //驳回消息
    public function reject()
    {
        if (IS_POST) {
            if ($this->sales_record_model->create()) {
                if ($this->sales_record_model->save()) {
					$this->record_logs_model->add(['action'=>'驳回','record_id'=>$post['id'],'uid'=>session('ADMIN_ID'),'create_time'=>time()]);
                    $url = '?g=&m=Index&a=details&id='.$_POST['id'];
                    $this->send_message($_POST['id'],'非常抱歉，您申请的售后服务已被驳回，前往查看原因吧~',$url,2);
                    $this->ajaxReturn(array('status' =>0,'msg'=>'操作成功！'));
                }
                $this->ajaxReturn(array('status' =>1,'msg'=>'操作失败！'));
            }
            $this->ajaxReturn(array('status' =>1,'msg'=>$this->sales_record_model->getError()));
        }
    }

    //推送消息
    public function send_message($id,$message,$jump_url,$type)
    {
        $info = $this->sales_record_model->where('id='.$id)->find();
        $weChatAuth = new \Com\WechatAuth(self::APPID,self::APPSECRET);
        $token=$weChatAuth->getAccessToken('client')['access_token'];
        $url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$token;

        if ($type == 1) {
            $userInfo = M('users')->where('id='.$info['sid'])->find();
			$user = M('users')->where('id='.$info['uid'])->find();
            //指派、改派、接收
            $data=array(
                "touser"=>$user['openid'],
                "topcolor"=>"#151516",
                "template_id"=>"IhEAY9za07THgQfieNCRncYRD-yZbZp5_vaU_PLd_Kk",
                "url"=>'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].$jump_url,
                'data'=>array(
                    'first'=>array('value'=>urlencode($message),'color'=>"#151516"),
                    'keyword1'=>array('value'=>urlencode($userInfo['user_name']),'color'=>"#151516"),
                    'keyword2'=>array('value'=>urlencode($userInfo['mobile']),'color'=>"#151516"),
                    'keyword3'=>array('value'=>date('Y-m-d H:i:s'),'color'=>"#151516"),
                ));
        }else{
            $userInfo = M('users')->where('id='.$info['uid'])->find();
			$fault_type = getRepairsInfo($info['fault_type']);
            //驳回、评价
            $data=array(
                "touser"=>$userInfo['openid'],
                "topcolor"=>"#151516",
                "template_id"=>"Mt_dpQ8FiJ4XX17sOw8Qr_ZWQSrkdHfjt1ApFP56jGA",
                "url"=>'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].$jump_url,
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

    //催单
    public function reminder()
    {
        $post = I('post.');
        $info = $this->users_model->where('id='.$post['uid'])->find();
        $record = $this->sales_record_model->where('id='.$post['id'])->find();
        $type = getRepairsInfo($record['fault_type']);

        $weChatAuth = new \Com\WechatAuth(self::APPID,self::APPSECRET);
        $token=$weChatAuth->getAccessToken('client')['access_token'];
        $url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$token;
        $data=array(
            "touser"=>$info['openid'],
            "topcolor"=>"#151516",
            "template_id"=>"tVFDIdAPeiEKVf8pqnAMCkPZB07AzU6E9nkZvbwaEQ8",
            "url"=>'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?g=User&m=Center&a=detail&id='.$record['id'],
            'data'=>array(
                'first'=>array('value'=>urlencode('您接收的任务【'.$record['project_name'].'】已经超时啦，赶紧去处理吧~'),'color'=>"#151516"),
                'HandleType'=>array('value'=>urlencode($type),'color'=>"#151516"),
                'RowCreateDate'=>array('value'=>date('Y-m-d H:i:s'),'color'=>"#151516"),
                'Status'=>array('value'=>urlencode("待处理"),'color'=>"#151516"),
                'LogType'=>array('value'=>urlencode("已超时"),'color'=>"red"),
            ));
        $data= urldecode(json_encode($data));
        $res= _request($url,true,'POST',$data);
        $this->ajaxReturn(['status'=>0,'msg'=>'已推送消息']);
    }

    //更变订单状态
    public function change_status()
    {
        if (IS_POST) {
            if ($this->sales_record_model->create()) {
                if ($this->sales_record_model->save()) {
                    $this->record_logs_model->add_log('催单',session('ADMIN_ID'),$_POST['id']);
                    $this->ajaxReturn(array('status' =>0,'msg'=>'操作成功！'));
                }
                $this->ajaxReturn(array('status' =>1,'msg'=>'操作失败！'));
            }
            $this->ajaxReturn(array('status' =>1,'msg'=>$this->sales_record_model->getError()));
        }
    }
	
	public function change_status2()
	{
		if (IS_POST) {
			$pdata =I('post.');
			$this->sales_record_model->where("id='".$pdata['id']."'")->setField('emergency_degree',$pdata['emergency_degree']);
			   
			$this->ajaxReturn(array('status' =>0,'msg'=>'操作成功！'));
		}
	}
    //处理进度列表
    public function process()
    {
        $id = I('get.id');
        $record = $this->sales_record_model->find($id);
        $record['user'] = $this->users_model->where('id='.$record['uid'])->find();

        $where['record_id'] = $id;
        $count = D('record_logs a')->where($where)->count();
        $page = $this->page($count, 20);
        $logs = $this->record_logs_model->where($where)->limit($page->firstRow, $page->listRows)->select();

		foreach($logs as &$v)
		{
			$v['record'] = M('sales_record')->where(['id'=>$v['record_id']])->find();
			$v['user_info'] = M('users')->where(['id'=>$v['uid']])->find();
		}

        $this->assign('record',$record);
        $this->assign('page',$page);
        $this->assign('logs',$logs);
        $this->display();
    }
}