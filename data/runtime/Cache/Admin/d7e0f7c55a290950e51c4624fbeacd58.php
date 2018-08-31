<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<!-- Set render engine for 360 browser -->
	<meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- HTML5 shim for IE8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <![endif]-->

	<link href="/public/simpleboot/themes/<?php echo C('SP_ADMIN_STYLE');?>/theme.min.css" rel="stylesheet">
    <link href="/public/simpleboot/css/simplebootadmin.css" rel="stylesheet">
    <link href="/public/js/artDialog/skins/default.css" rel="stylesheet" />
    <link href="/public/simpleboot/font-awesome/4.4.0/css/font-awesome.min.css"  rel="stylesheet" type="text/css">
    <style>
		form .input-order{margin-bottom: 0px;padding:3px;width:40px;}
		.table-actions{margin-top: 5px; margin-bottom: 5px;padding:0px;}
		.table-list{margin-bottom: 0px;}
	</style>
	<!--[if IE 7]>
	<link rel="stylesheet" href="/public/simpleboot/font-awesome/4.4.0/css/font-awesome-ie7.min.css">
	<![endif]-->
	<script type="text/javascript">
	//全局变量
	var GV = {
	    ROOT: "/",
	    WEB_ROOT: "/",
	    JS_ROOT: "public/js/",
	    APP:'<?php echo (MODULE_NAME); ?>'/*当前应用名*/
	};
	</script>
    <script src="/public/js/jquery.js"></script>
    <script src="/public/js/wind.js"></script>
    <script src="/public/simpleboot/bootstrap/js/bootstrap.min.js"></script>
    <script>
    	$(function(){
    		$("[data-toggle='tooltip']").tooltip();
    	});
    </script>
<?php if(APP_DEBUG): ?><style>
		#think_page_trace_open{
			z-index:9999;
		}
	</style><?php endif; ?>
</head>
<style>
    input[type="text"], input[type="number"] {
        padding: 3.5px 6px;
    }

    .row-fluid {
        display: none;
        position: fixed;
        top: 30%;
        border-radius: 3px;
        left: 32%;
        width: 27%;
        overflow: hidden;
        overflow-y: auto;
        padding: 8px;
        border: 1px solid #E8E9F7;
        background-color: white;
        z-index: 10003;
    }

    .row-fluid-edit {
        display: none;
        position: relative;
        top: -120px;
        border-radius: 3px;
        left: 32%;
        width: 27%;
        overflow: hidden;
        overflow-y: auto;
        padding: 8px;
        border: 1px solid #E8E9F7;
        background-color: white;
        z-index: 10003;
    }

    .row-fluid-info {
        display: none;
        position: relative;
        top: -130px;
        border-radius: 3px;
        left: 32%;
        width: 27%;
        overflow: hidden;
        overflow-y: auto;
        padding: 8px;
        border: 1px solid #E8E9F7;
        background-color: white;
        z-index: 10003;
    }

    #bg {
        display: none;
        position: fixed;
        top: 0%;
        left: 0%;
        width: 100%;
        height: 100%;
        background-color: black;
        z-index: 1001;
        -moz-opacity: 0.7;
        opacity: .70;
        filter: alpha(opacity=70);
    }

    .table tr th, .table tr td {
        text-align: center;
    }

    .add-btn {
        margin-left: 22px;
    }

</style>
<body>
<div class="wrap js-check-wrap" style="padding-bottom:0">
    <ul class="nav nav-tabs">
        <li class="active"><a href="<?php echo U('user/index');?>">后台用户管理</a></li>
    </ul>
    <form class="well form-search"  method="post" action="<?php echo U('User/index');?>">
            <label>姓名：</label>
            <input type="text" value="<?php echo ($sname); ?>" placeholder="请输入姓名" name="user_name" style="margin-right: 70px">
            <label>手机号：</label>
            <input type="text" value="<?php echo ($smobile); ?>" placeholder="请输入手机号" name="mobile" style="margin-right: 70px">
            <input type="submit" class="btn btn-primary" style="margin-right: 30px;width: 70px;background-color: #00DDAA" value="查询" />
            <a href="javascript:;" class="btn btn-primary" onclick="show_add()" style="margin-right: 30px;width: 70px;background-color: #00DDAA">新增</a>
        </div>
    </form>
    <form class="form-horizontal">
    <table class="table table-hover table-bordered">
        <thead>
        <tr>
            <th style="min-width: 50px;text-align: center;">ID</th>
            <th style="min-width: 50px;text-align: center;">姓名</th>
            <th style="min-width: 50px;text-align: center;">手机号(账号)</th>
            <th style="min-width: 50px;text-align: center;">所属角色</th>
            <th style="min-width: 50px;text-align: center;">操作</th>
        </tr>
        </thead>
        <tbody>
            <?php if(is_array($users)): foreach($users as $key=>$vo): ?><tr>
                    <td style="text-align: center;"><?php echo ($vo["id"]); ?></td>
                    <td style="text-align: center;"><?php echo ($vo["user_name"]); ?></td>
                    <td style="text-align: center;"><?php echo ($vo["mobile"]); ?></td>
                    <td style="text-align: center;"><?php echo ($vo["role_name"]); ?></td>
                    <td style="text-align: center;">
                        <a onclick="show_edit('<?php echo ($vo["id"]); ?>')" class="btn btn-primary"
                           style="margin-right:20px;padding: 2px 15px;color: white;background-color: #1dccaa;">编辑</a>
                        <a onclick="delete_post('<?php echo ($vo["id"]); ?>')" class="btn btn-primary"
                           style="margin-right:20px;padding: 2px 15px;color: white;background-color: #1dccaa;">删除</a>
                        <?php if($vo["user_status"] == 1): ?><a onclick="change_status('<?php echo ($vo["id"]); ?>',0)" class="btn btn-primary"
                               style="margin-right:20px;padding: 2px 15px;color: white;background-color: #1dccaa;">冻结</a>
                            <?php else: ?>
                            <a onclick="change_status('<?php echo ($vo["id"]); ?>',1)" class="btn btn-primary"
                               style="margin-right:20px;padding: 2px 15px;color: white;background-color: #1dccaa;">解冻</a><?php endif; ?>
                        <a onclick="user_info('<?php echo ($vo["mobile"]); ?>','<?php echo ($vo["role_name"]); ?>')" class="btn btn-primary"
                           style="margin-right:20px;padding: 2px 15px;color: white;background-color: #1dccaa;">详情</a>
                    </td>
                </tr><?php endforeach; endif; ?>
            <?php if(count($users) == 0): ?><tr><td id="nonumber" colspan="5" style="text-align: center;">暂无数据信息</td><?php endif; ?>
        </tbody>
        </table>
        </form>
    </div>
    <div class="pagination"><?php echo ($page); ?></div>
    <div class="control-group" id="category-list">
        <div class="row-fluid" id="company_add" style="display: none">
            <div style="margin-top:70px;margin-left:40px;margin-bottom: 5px">
                <div style="margin-bottom: 30px">
                    <table style="width: 100%">
                        <tr>
                            <td style="width: 20%">姓名：</td>
                            <td>
                                <input type="text" name="user_name" value="" id="user_name"/>
                            </td>
                        </tr>
                    </table>
                </div>
                <div style="margin-bottom: 30px">
                    <table style="width: 100%">
                        <tr>
                            <td style="width: 20%">手机号：</td>
                            <td>
                                <input type="text" name="mobile" value="" id="mobile"/>
                            </td>
                        </tr>
                    </table>
                </div>
                <div style="margin-bottom: 30px">
                    <table style="width: 100%">
                        <tr>
                            <td style="width: 20%">所属角色：</td>
                            <td>
                                <select name="role_id" id="role_id">
                                    <?php if(is_array($roles)): foreach($roles as $key=>$vo): ?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["name"]); ?></option><?php endforeach; endif; ?>
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div style="height: 30px;border-bottom: 1px solid #ccc;"></div>
            <div style="text-align: center;margin-top: 10px;">
                <a href="javascript:;" class="btn btn-primary" onclick="close_div()">取消</a>&nbsp;&nbsp;&nbsp;
                <a href="javascript:;" class="btn btn-primary" onclick="add_post()">确认</a>
            </div>
        <div class="row" id="page-info">
        </div>
    </div>
    <div class="row-fluid-edit" id="user_edit" style="display: none">
        <div style="margin-top:70px;margin-left:40px;margin-bottom: 5px">
                <div style="margin-bottom: 30px">
                    <table style="width: 100%">
                        <tr>
                            <td style="width: 20%">姓名：</td>
                            <td>
                                <input type="text" name="user_name" value="" id="edit_user_name"/>
                            </td>
                        </tr>
                    </table>
                </div>
                <div style="margin-bottom: 30px">
                    <table style="width: 100%">
                        <tr>
                            <td style="width: 20%">手机号：</td>
                            <td>
                                <input type="text" name="mobile" value="" id="edit_mobile"/>
                            </td>
                        </tr>
                    </table>
                </div>
                <div style="margin-bottom: 30px">
                    <table style="width: 100%">
                        <tr>
                            <td style="width: 20%">角色：</td>
                            <td>
                                <select name="role_id" id="edit_role_id">
                                    <?php if(is_array($roles)): foreach($roles as $key=>$vo): ?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["name"]); ?></option><?php endforeach; endif; ?>
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>
                <input type="hidden" id="id" value="">
            </div>
            <div style="height: 30px;border-bottom: 1px solid #ccc;"></div>
            <div style="text-align: center;margin-top: 10px;">
                <a href="javascript:;" class="btn btn-primary" onclick="close_div()">取消</a>&nbsp;&nbsp;&nbsp;
                <a href="javascript:;" class="btn btn-primary" onclick="edit_post()">确认</a>
            </div>
            <div class="row">
        </div>
    </div>
    <div class="row-fluid-info" id="user_info" style="display: none">
        <div style="margin-top:40px;margin-left:40px;margin-bottom: 5px">
            <div style="margin-bottom: 30px">手机号：<span id="info_mobile"></span></div>
            <div style="margin-bottom: 30px">所属角色：<span id="info_role"></span></div>
        </div>
    </div>
</div>
<div id="bg" onclick="close_div()"></div>
<script src="/public/js/common.js"></script>
<script src="/public/js/artDialog/artDialog.js"></script>
<script src="/public/js/layer/layer.js"></script>
<script type="text/javascript">
    function close_div() {
        $('.row-fluid').css('display', 'none');
        $('.row-fluid-edit').css('display', 'none');
        $('.row-fluid-info').css('display', 'none');
        $('#bg').css('display', 'none');
    }

    function show_add() {
        $("#bg").css('display', 'block');
        $('#company_add').css('display', 'block');
    }

    function add_post() {
        $('.row-fluid').css('display', 'none');
        $('#bg').css('display', 'none');
        var mobile = $('#mobile').val();
        var user_name = $('#user_name').val();
        var role_id = $('#role_id option:selected').val();
        $.ajax({
            url: "<?php echo U('User/add_post');?>",
            type: 'POST',
            data: {
                mobile: mobile,
                role_id: role_id,
                user_name: user_name,
            },
            dataType: "json",
            success: function (res) {
                if (res.status == 0) {
                    $.dialog({id: 'popup', lock: true, icon: "succeed", content: res.msg, time: 2});
                    setInterval(function () {
                        location.href = '<?php echo U("User/index");?>';
                    }, 2000)
                } else {
                    $.dialog({id: 'popup', lock: true, icon: "warning", content: res.msg, time: 2});
                }
            }
        });
    }

    function show_edit(id) {
        $.ajax({
            url: "<?php echo U('User/edit');?>",
            type: 'POST',
            data: {id: id},
            dataType: "json",
            success: function (res) {
                if (res.status == 0) {
                    $("#bg").css('display', 'block');
                    $('#user_edit').css('display', 'block');
                    document.getElementById('edit_mobile').value = res.data.mobile;
                    document.getElementById('edit_user_name').value = res.data.user_name;
                    document.getElementById('edit_role_id').value = res.data.role_id;
                    document.getElementById('id').value = id;
                } else {
                    $.dialog({id: 'popup', lock: true, icon: "warning", content: res.msg, time: 2});
                }
            }
        });
    }

    function edit_post() {
        $('#user_edit').css('display', 'none');
        $('#bg').css('display', 'none');
        var mobile = $('#edit_mobile').val();
        var role_id = $('#edit_role_id option:selected').val();
        var user_name = $('#edit_user_name').val();
        var id = $('#id').val();
        $.ajax({
            url: "<?php echo U('User/edit_post');?>",
            type: 'POST',
            data: {
                mobile: mobile,
                role_id: role_id,
                user_name: user_name,
                id: id
            },
            dataType: "json",
            success: function (res) {
                if (res.status == 0) {
                    $.dialog({id: 'popup', lock: true, icon: "succeed", content: res.msg, time: 2});
                    setInterval(function () {
                        location.href = '<?php echo U("User/index");?>';
                    }, 2000)
                } else {
                    $.dialog({id: 'popup', lock: true, icon: "warning", content: res.msg, time: 2});
                }
            }
        });
    }

    function delete_post(id) {
        $.dialog({
            id: 'popup', lock: true, icon: "question", content: "是否删除？", cancel: true, ok: function () {
                $.ajax({
                    url: "<?php echo U('User/delete');?>",
                    type: 'POST',
                    data: {id: id},
                    dataType: "json",
                    success: function (res) {
                        if (res.status == 0) {
                            setInterval(function () {
                                location.href = '<?php echo U("User/index");?>';
                            }, 1000)
                        } else {
                            $.dialog({id: 'popup', lock: true, icon: "warning", content: res.msg, time: 2});
                        }
                    }
                });
            }
        })
    }

    function change_status(id,status) {
        if (status == 1) {
            content = "确定解冻？"
        }else {
            content = "确定冻结？"
        }
        $.dialog({
            id: 'popup', lock: true, icon: "question", content:content, cancel: true, ok: function () {
                $.ajax({
                    url: "<?php echo U('User/change_status');?>",
                    type: 'POST',
                    data: {
                        id: id,
                        user_status: status,
                    },
                    dataType: "json",
                    success: function (res) {
                        if (res.status == 0) {
                            $.dialog({id: 'popup', lock: true, icon: "succeed", content: res.msg, time: 2});
                            setInterval(function () {
                                location.href = '<?php echo U("User/index");?>';
                            }, 2000)
                        } else {
                            $.dialog({id: 'popup', lock: true, icon: "warning", content: res.msg, time: 2});
                        }
                    }
                });
            }
        })
    }

    function user_info(mobile,role_name) {
        $("#bg").css('display', 'block');
        $('#user_info').css('display', 'block');
        console.log(mobile,role_name);
        document.getElementById('info_mobile').innerText = mobile;
        document.getElementById('info_role').innerText = role_name;
    }
</script>
</body>
</html>