<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/7/16
 * Time: 10:18
 */

namespace Portal\Model;


use Common\Model\CommonModel;

class SalesRecordModel extends CommonModel
{
    protected $_auto = array (
        array('create_time', 'mGetDate', self::MODEL_INSERT, 'callback' ),
    );

    // 获取当前时间
    public function mGetDate() {
        return time();
    }

    protected function _before_write(&$data) {
        parent::_before_write($data);
    }
}