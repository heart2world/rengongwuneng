<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/7/12
 * Time: 17:01
 */

namespace Admin\Controller;


use Common\Controller\AdminbaseController;

class SalesmemberController extends AdminbaseController
{
    protected $users_model,$area_model,$sales_record_model;

    public function _initialize() {
        parent::_initialize();
        $this->users_model = D("Common/Users");
        $this->area_model = D("Common/Area");
        $this->sales_record_model = D("Common/SalesRecord");
    }

    public function index()
    {
        /**搜索条件**/
        $keyword = I('request.keyword');
        if($keyword){
            if (is_numeric($keyword)) {
                $where['mobile'] = array('like',"%$keyword%");
            }else{
                $where['user_name'] = array('like',"%$keyword%");
            }
        }

        $where['user_type'] = 3;
        $count = $this->users_model->where($where)->count();
        $page = $this->page($count, 20);
        $members = $this->users_model->where($where) ->limit($page->firstRow, $page->listRows)->select();

        foreach ($members as &$v)
        {
            $v['area'] = $this->area_model->where(['uid'=>$v['id']])->select();
        }
		 if(I('request.keyword')){
            $newkeyword = I('request.keyword');
			
            $this->assign('newkeyword',$newkeyword);
        }
	

        $this->assign('members',$members);
        $this->assign('page',$page);
        $this->display();
    }

    //注册码
    public function qrcode()
    {
        $code = M('config')->where(['config_name'=>'qrcode'])->find();

        $this->assign('code',$code);
        $this->display();
    }

    //售后人员记录详情
    public function order()
    {
        $member = I('get.');

        $where['a.sid'] = $member['id'];
        $count = D('sales_record a')->where($where)->count();
        $page = $this->page($count, 20);
        $order = $this->sales_record_model->getData($where,$page);

        $this->assign('member',$member);
        $this->assign('page',$page->show('Admin'));
        $this->assign('order',$order);
        $this->display();
    }

    //常规售后人员编辑

    /**
     * @return mixed
     */
    public function shedit()
    {
        //根据是否有提交数据来进行页面的显示
        if(IS_POST){
            
        }else{
            //获取售后人员ID
            $id = I('get.id');
            $where['id'] = $id;
            $usermodel=M('users');
            //查询到用户信息
            $user_info=$usermodel->where($where)->find();
            //查询售后人员负责区域
            $areamodel=M('area');
            $where2['uid']=$id;
            $userarea=$areamodel->where($where2)->select();
            
			$this->assign('area',$userarea);
			$this->assign('count',count($userarea));
			$this->assign('count2',5-count($userarea));
			$this->assign('id',$id);
            $this->assign('sh',$user_info);
            $this->display();
        }
    }

    // 售后人员编辑ajax
    public function edit(){
        $id = I('post.id',0,'intval');
        $user=$this->users_model->where(array("id"=>$id))->find();
        if (!$user) {
            $this->ajaxReturn(array('status'=>1,'msg'=>"信息不存在！"));
        }
        $user['area'] = $this->area_model->where(['uid'=>$id])->select();
        $this->ajaxReturn(array('status'=>0,'data'=>$user));
    }
	public function edit_post()
	{
		if(IS_POST)
		{
			//执行更新数据操作
            $data=array();
			$pdata =I('post.');
			$id = I('post.userid');
            $where['id']=$id;
			M('area')->where("uid='".$id."'")->delete();
			foreach($pdata['shen'] as $key=>$v)
			{
				$count =M('area')->where("uid='$id' and province='".$v."' and city='".$pdata['shi'][$key]."' and district='".$pdata['qu'][$key]."'")->find();
				if($count ==0)
				{					
					if($v!='' && $pdata['shi'][$key]!='' && $pdata['qu'][$key]!='')
					{
						$data2['uid'] = $pdata['userid'];
						$data2['province']=$v;
						$data2['city']=$pdata['shi'][$key];
						$data2['district']=$pdata['qu'][$key];
						$data2['create_time'] =time();
						M('area')->add($data2);
					}
				}
			}
            $data['user_name']=I('post.user_name');
            $data['mobile']=I('post.mobile');
            $usermodel=M('users');
            $reslut=$usermodel->where($where)->save($data);
            if($reslut!=='false'){
                $this->ajaxReturn(array('status'=>0,'msg'=>'修改成功'));
            }else{
                $this->ajaxReturn(array('status'=>1,'msg'=>'修改失败'));
            }
		}
	}
    // 售后人员删除
    public function delete(){
        $id = I('post.id',0,'intval');
        if ($this->users_model->delete($id)!==false) {
            $this->ajaxReturn(['status'=>0,'msg'=>"删除成功！"]);
        } else {
            $this->ajaxReturn(['status'=>1,'msg'=>"删除失败！"]);
        }
    }

    // 更变用户状态
    public function change_status(){
        $post = I('post.');
        if (!empty($post)) {
            $result = $this->users_model->where(array("id"=>$post['id']))->save(['user_status'=>$post['user_status']]);
            if ($result!==false) {
                $this->ajaxReturn(['status'=>0,'msg'=>'操作成功！']);
            } else {
                $this->ajaxReturn(['status'=>1,'msg'=>'操作失败！']);
            }
        } else {
            $this->ajaxReturn(['status'=>1,'msg'=>'数据传入失败！']);
        }
    }
}