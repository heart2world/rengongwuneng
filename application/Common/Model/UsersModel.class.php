<?php
namespace Common\Model;
use Common\Model\CommonModel;
class UsersModel extends CommonModel
{
	
	protected $_validate = array(
		//array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
		array('user_login', 'require', '用户名称不能为空！', 0, 'regex', CommonModel:: MODEL_BOTH  ),
		array('mobile', 'require', '手机号不能为空！', 0, 'regex', CommonModel:: MODEL_BOTH  ),
	    array('mobile','','手机号已经存在！',0,'unique',CommonModel:: MODEL_BOTH ), // 验证mobile字段是否唯一
	);
	
	protected $_auto = array(
	    array('create_time','mGetDate',CommonModel:: MODEL_INSERT,'callback'),
	    array('birthday','',CommonModel::MODEL_UPDATE,'ignore')
	);

	function mGetDate() {
		return time();
	}
	
	protected function _before_write(&$data) {
		parent::_before_write($data);

		if(empty($data['user_pass'])){
			$data['user_pass']=sp_password(123456);
		}
	}

    /**
     * 获取所有用户信息
     * @param $where
     * @param $page
     * @return mixed
     */
    public function getUsers($where,$page)
    {
        $users = M('users as a')
            ->join(C('DB_PREFIX').'role_user as b on a.id = b.user_id','left')
            ->join(C('DB_PREFIX').'role as c on c.id = b.role_id','left')
            ->field('a.id,a.mobile,a.user_name,a.user_status,a.create_time,c.name role_name')
            ->where($where)
            ->order("a.create_time DESC")
            ->limit($page->firstRow, $page->listRows)
            ->group('a.id')
            ->select();

        return $users;
    }

    /**
     * 获取用户信息
     * @param $where
     * @return mixed
     */
    public function getUserInfo($where)
    {
        $info = M('users a')
            ->join(C('DB_PREFIX').'role_user b on a.id = b.user_id')
            ->join(C('DB_PREFIX').'role c on c.id = b.role_id')
            ->where($where)
            ->find();
        return $info;
    }
}

