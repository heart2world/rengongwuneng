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
<link href="/public/jedate/skin/jedate.css" rel="stylesheet">
<style>
	input[type="text"], input[type="number"] {
		padding: 3.5px 6px;
	}

	.row-fluid-edit {
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
</style>
<body>
<div class="wrap js-check-wrap">
	<ul class="nav nav-tabs">
		<li class="active"><a href="<?php echo U('Salesmember/index');?>">售后人员</a></li>
		<li><a href="<?php echo U('Salesmember/qrcode');?>">注册码</a></li>
	</ul>
	<form class="well form-search" method="post" action="<?php echo U('Salesmember/index');?>">
		<div>
			<label>姓名/电话</label>
			<input type="text" value="<?php echo ($newkeyword); ?>" placeholder="请输入查询信息" name="keyword" style="margin-right: 30px">
			<input type="submit" class="btn btn-primary" style="margin-right: 30px;width: 70px;background-color: #00DDAA" value="查询" />
		</div>
	</form>
	<form class="js-ajax-form">
		<table class="table table-hover table-bordered table-list" id="menus-table">
			<thead>
			<tr>
				<th width="50">ID</th>
				<th width="80">姓名</th>
				<th width="80">电话</th>
				<th width="80">区域</th>
				<th width="110">状态</th>
				<th width="180">操作</th>
			</tr>
			</thead>
			<tbody>
			<?php if(is_array($members)): $i = 0; $__LIST__ = $members;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr>
					<td><?php echo ($val["id"]); ?></td>
					<td><?php echo ($val["user_name"]); ?></td>
					<td><?php echo ($val["mobile"]); ?></td>
					<td>
						<?php if(is_array($val["area"])): $i = 0; $__LIST__ = $val["area"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if(($mod) == "1"): endif; echo ($vo["province"]); echo ($vo["city"]); echo ($vo["district"]); ?>/<?php endforeach; endif; else: echo "" ;endif; ?>
					</td>
					<td>
						<?php if($val["user_status"] == 0): ?>已冻结
							<?php else: ?>
							正常<?php endif; ?>
					</td>
					<td>
						<?php if($val["user_status"] == 1): ?><a onclick="change_status('<?php echo ($val["id"]); ?>',0)" class="btn btn-primary"
							   style="margin-right:20px;padding: 2px 15px;color: white;background-color: #1dccaa;">冻结</a>
							<?php else: ?>
							<a onclick="change_status('<?php echo ($val["id"]); ?>',1)" class="btn btn-primary"
							   style="margin-right:20px;padding: 2px 15px;color: white;background-color: #1dccaa;">解冻</a><?php endif; ?>
						<a onclick="delete_post('<?php echo ($val["id"]); ?>')" class="btn btn-primary"
						   style="margin-right:20px;padding: 2px 15px;color: white;background-color: #1dccaa;">删除</a>
						<!--<a onclick="show_edit('<?php echo ($val["id"]); ?>')" class="btn btn-primary"-->
						<!--style="margin-right:20px;padding: 2px 15px;color: white;background-color: #1dccaa;">编辑</a>-->
						<a href="<?php echo U('Salesmember/shedit',array('id'=>$val['id']));?>" class="btn btn-primary"
						   style="margin-right:20px;padding: 2px 15px;color: white;background-color: #1dccaa;">编辑</a>
						<a href="<?php echo U('Salesmember/order',array('id'=>$val['id'],'user_name'=>$val['user_name'],'mobile'=>$val['mobile'],'area'=>$val['area']));?>" class="btn btn-primary"
						   style="margin-right:20px;padding: 2px 15px;color: white;background-color: #1dccaa;">详情</a>
					</td>
				</tr><?php endforeach; endif; else: echo "" ;endif; ?>
			<?php if(count($members) == 0): ?><tr><td id="nonumber" colspan="6" style="text-align: center;">暂无数据信息</td><?php endif; ?>
			</tbody>
		</table>
		<div class="pagination" style="margin-left: 90px;"><?php echo ($page); ?></div>
	</form>
</div>
<div class="row-fluid-edit" id="user_edit" style="display: none">
	<div style="margin-top:40px;margin-left:40px;margin-bottom: 5px">
		<div style="margin-bottom: 10px">
			<table style="width: 100%">
				<tr>
					<td style="width: 20%">姓名</td>
					<td>
						<input type="text" name="user_name" value="" id="edit_user_name"/>
					</td>
				</tr>
			</table>
		</div>
		<div style="margin-bottom: 10px">
			<table style="width: 100%">
				<tr>
					<td style="width: 20%">电话</td>
					<td>
						<input type="text" name="mobile" value="" id="edit_mobile"/>
					</td>
				</tr>
			</table>
		</div>
		<div style="margin-bottom: 10px">
			<table style="width: 100%" id="edit_area">
			</table>
		</div>
		<input type="hidden" id="id" value="">
	</div>
	<div style="height: 30px;border-bottom: 1px solid #ccc;"></div>
	<div style="text-align: center;margin-top: 10px;">
		<a href="javascript:;" class="btn btn-primary" onclick="close_div()">取消</a>&nbsp;&nbsp;&nbsp;
		<a href="javascript:;" class="btn btn-primary" onclick="edit_post()">保存</a>
	</div>
	<div class="row">
	</div>
</div>
<div id="bg" onclick="close_div()"></div>
<script src="/public/js/common.js"></script>
<script src="/public/js/layer/layer.js"></script>
<script src="/public/js/artDialog/artDialog.js"></script>
<script type="text/javascript">
    function close_div() {
        $('.row-fluid-edit').css('display', 'none');
        $('#bg').css('display', 'none');
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
                    document.getElementById('id').value = id;
                    console.debug(res.data);
                    var tr = '<?php if(is_array($res["data"]["area"])): foreach($res["data"]["area"] as $key=>$vo): ?>\n' +
                        '<tr>\n' +
                        '<td style="width: 20%">区域</td>\n' +
                        '<td>\n' +
                        '<select name="province">\n' +
                        '<?php if(is_array($row)): foreach($row as $key=>$vo): ?>\n' +
                        '<option value="<?php echo ($vo["province"]); ?>"><?php echo ($vo["province"]); ?></option>\n' +
                        '<?php endforeach; endif; ?>\n' +
                        '</select>\n' +
                        '</td>\n' +
                        '<td>\n' +
                        '<select name="city">\n' +
                        '<?php if(is_array($row)): foreach($row as $key=>$vo): ?>\n' +
                        '<option value="<?php echo ($vo["city"]); ?>"><?php echo ($vo["city"]); ?></option>\n' +
                        '<?php endforeach; endif; ?>\n' +
                        '</select>\n' +
                        '</td>\n' +
                        '<td>\n' +
                        '<select name="district">\n' +
                        '<?php if(is_array($row)): foreach($row as $key=>$vo): ?>\n' +
                        '<option value="<?php echo ($vo["district"]); ?>"><?php echo ($vo["district"]); ?></option>\n' +
                        '<?php endforeach; endif; ?>\n' +
                        '</select>\n' +
                        '</td>\n' +
                        '</tr>\n' +
                        '<?php endforeach; endif; ?>';
                    $('#edit_area').append(tr);
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
                    url: "<?php echo U('Salesmember/delete');?>",
                    type: 'POST',
                    data: {id: id},
                    dataType: "json",
                    success: function (res) {
                        if (res.status == 0) {
						$.dialog({id: 'popup', lock: true, icon: "succeed", content: res.msg, time: 2});
                            setInterval(function () {
							     
                                location.href = '<?php echo U("Salesmember/index");?>';
                            }, 2000)
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
            content = "恢复后该员工将能正常使用，确定恢复？"
        }else {
            content = "冻结后该员工将无法再登陆，确认冻结？"
        }
        $.dialog({
            id: 'popup', lock: true, icon: "question", content:content, cancel: true, ok: function () {
                $.ajax({
                    url: "<?php echo U('Salesmember/change_status');?>",
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
                                location.href = '<?php echo U("Salesmember/index");?>';
                            }, 2000)
                        } else {
                            $.dialog({id: 'popup', lock: true, icon: "warning", content: res.msg, time: 2});
                        }
                    }
                });
            }
        })
    }
</script>
</body>
</html>