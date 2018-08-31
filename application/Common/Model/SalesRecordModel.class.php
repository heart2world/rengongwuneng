<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/7/12
 * Time: 10:39
 */

namespace Common\Model;


class SalesRecordModel extends CommonModel
{

    protected $_auto = array(
        array('create_time','mGetDate',CommonModel:: MODEL_INSERT,'callback'),
    );

    function mGetDate() {
        return date('Y-m-d H:i:s');
    }

    protected function _before_write(&$data) {
        parent::_before_write($data);
    }

    /**
     * 获取所有用户信息
     * @param $where
     * @param $page
     * @return mixed
     */
    public function getData($where,$page)
    {
        $users = M('sales_record a')
            ->join(C('DB_PREFIX').'users b on a.uid = b.id')
            ->field('a.id,a.uid,a.project_name,a.fault_type,a.emergency_degree,a.user_name,a.mobile,a.sid,a.state,a.create_time,b.avatar,b.nick_name,b.openid')
            ->where($where)
            ->order("a.create_time DESC")
            ->limit($page->firstRow, $page->listRows)
            ->order('a.id desc')
            ->group('a.id')
            ->select();

        return $users;
    }
}