<?php
namespace User\Controller;

use Common\Controller\HomebaseController;

class IndexController extends HomebaseController {
    
    // 前台用户首页 (公开)
	public function index() {
	    
		$id=I("get.id",0,'intval');
		
		$users_model=M("Users");
		
		$user=$users_model->where(array("id"=>$id))->find();

		$this->assign($user);
		$this->display(":index");
    }

    // 我的报修
    public function service()
    {
        $this->display(":service");
    }
}
