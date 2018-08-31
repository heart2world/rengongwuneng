<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/7/12
 * Time: 18:12
 */

namespace Common\Model;


class AreaModel extends CommonModel
{
    function mGetDate() {
        return time();
    }

    protected function _before_write(&$data) {
        parent::_before_write($data);
    }

}