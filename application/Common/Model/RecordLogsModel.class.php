<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/7/13
 * Time: 9:33
 */

namespace Common\Model;


class RecordLogsModel extends CommonModel
{
    protected $_auto = array(
        array('create_time','mGetDate',CommonModel:: MODEL_INSERT,'callback'),
    );

    function mGetDate() {
        return time();
    }

    //添加日志
    public function add_log($action,$uid,$record_id)
    {
        $data['action'] = $action;
        $data['uid'] = $uid;
        $data['record_id'] = $record_id;
        $data['create_time'] = time();
        $this->add($data);
    }

    //获取日志
    public function getData($where,$page)
    {
        $data = M('record_logs a')
            ->join(C('DB_PREFIX').'users b on a.uid = b.id')
            ->field('a.id,a.action,a.create_time,b.user_name,b.mobile')
            ->where($where)
            ->order("a.create_time DESC")
            ->limit($page->firstRow, $page->listRows)
            ->group('a.id')
            ->select();

        return $data;
    }
}