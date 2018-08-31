<?php
namespace Admin\Controller;

use Common\Controller\AdminbaseController;

class UserController extends AdminbaseController{

    protected $users_model,$role_model;

    public function _initialize() {
        parent::_initialize();
        $this->users_model = D("Common/Users");
        $this->role_model = D("Common/Role");
    }

    // 管理员列表
    public function index(){
        /**搜索条件**/
        $user_name = I('request.user_name');
        $user_mobile = trim(I('request.mobile'));
        if($user_name){
            $where['a.user_name'] = array('like',"%$user_name%");
        }

        if($user_mobile){
            $where['a.mobile'] = array('like',"%$user_mobile%");;
        }

        $where['a.id'] = array('NEQ',get_current_admin_id());
        $where['a.user_type'] = 1;
        $count = D('users as a')->where($where)->count();
        $page = $this->page($count, 8);
        $users = $this->users_model->getUsers($where,$page);
        $roles_src=$this->role_model->where('id != 1')->select();
		
		if(I('request.user_name')){
            $sname=I('request.user_name');
            $this->assign("sname",$sname);
        }
        if(I('request.mobile')){
            $smobile=I('request.mobile');
            $this->assign("smobile",$smobile);
        }
        $this->assign("page", $page->show('Admin'));
        $this->assign("roles",$roles_src);
        $this->assign("users",$users);
        $this->display();
    }

    // 管理员添加提交
    public function add_post(){
        if (IS_POST) {
            $post = I('post.');
            $post['user_pass'] = sp_password(123456);
            if ($this->users_model->create($post)!==false) {
                $result = $this->users_model->add($post);
                if ($result!==false) {
                    $role_user_model=M("RoleUser");
                    $role_user_model->where(array("user_id"=>$result))->delete();
                    if(sp_get_current_admin_id() != 1 && $_POST['role_id'] == 1){
                        $this->ajaxReturn(array('status' =>1,'msg'=>'您不能创建超级管理员'));
                    }
                    $role_user_model->add(array("role_id"=>$_POST['role_id'],"user_id"=>$result));
                    $this->ajaxReturn(array('status' =>0,'msg'=>'保存成功！'));
                }else {
                    $this->ajaxReturn(array('status' =>1,'msg'=>'添加失败！'));
                }
            }else{
                $this->ajaxReturn(array('status' =>1,'msg'=>$this->users_model->getError()));
            }
        }
    }

    // 管理员编辑
    public function edit(){
        $id = I('post.id',0,'intval');
        $user=$this->users_model->where(array("id"=>$id))->find();
        if (!$user) {
            $this->ajaxReturn(array('status'=>1,'msg'=>"信息不存在！"));
        }
        $role_user_model=M("RoleUser");
        $user['role_id']=$role_user_model->where(array("user_id"=>$id))->getField("role_id",true);
        $this->ajaxReturn(array('status'=>0,'data'=>$user));
    }

    // 管理员编辑提交
    public function edit_post(){
        if (IS_POST) {
            $post = I('post.');
            $user = $this->users_model->where(['id'=>$post['id']])->find();
            if ($user['user_pass'] === $post['user_pass']) {
                unset($post['user_pass']);
            }
            if ($this->users_model->create($post)!==false) {
                $result = $this->users_model->save($post);
                if ($result!==false) {
                    $role_user_model=M("RoleUser");
                    $role_user_model->where(array("user_id"=>$post['id']))->delete();
                    if(sp_get_current_admin_id() != 1 && $_POST['role_id'] == 1){
                        $this->ajaxReturn(array('status' =>1,'msg'=>'您不能更改超级管理员身份'));
                    }
                    $role_user_model->add(array("role_id"=>$_POST['role_id'],"user_id"=>$post['id']));
                    $this->ajaxReturn(array('status' =>0,'msg'=>'保存成功！'));
                }else {
                    $this->ajaxReturn(array('status' =>1,'msg'=>'添加失败！'));
                }
            }else{
                $this->ajaxReturn(array('status' =>1,'msg'=>$this->users_model->getError()));
            }
        }
    }

    // 管理员删除
    public function delete(){
        $id = I('post.id',0,'intval');
        if($id==1){
            $this->error("最高管理员不能删除！");
        }

        if ($this->users_model->delete($id)!==false) {
            M("RoleUser")->where(array("user_id"=>$id))->delete();
            $this->success(['status'=>0,'msg'=>"删除成功！"]);
        } else {
            $this->error(['status'=>1,'msg'=>"删除失败！"]);
        }
    }

    // 管理员个人信息修改
    public function userinfo(){
        $id=sp_get_current_admin_id();
        $user=$this->users_model->where(array("id"=>$id))->find();
        $this->assign($user);
        $this->display();
    }

    // 管理员个人信息修改提交
    public function userinfo_post(){
        if (IS_POST) {
            $_POST['id']=sp_get_current_admin_id();
            $create_result=$this->users_model
                ->field("id,user_nicename,sex,birthday,user_url,signature")
                ->create();
            if ($create_result!==false) {
                if ($this->users_model->save()!==false) {
                    $this->success("保存成功！");
                } else {
                    $this->error("保存失败！");
                }
            } else {
                $this->error($this->users_model->getError());
            }
        }
    }

    // 更变管理员状态
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