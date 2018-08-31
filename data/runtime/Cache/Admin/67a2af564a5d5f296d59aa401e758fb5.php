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

	.table tr th, .table tr td {
		text-align: center;
	}
</style>
<body>
	<div class="wrap js-check-wrap">
		<ul class="nav nav-tabs">
			<li class="active"><a href="<?php echo U('Salesmember/index');?>">售后人员</a></li>
			<li><a href="<?php echo U('Salesmember/register_code');?>">注册码</a></li>
		</ul>
		<form class="form-horizontal">
			<fieldset>
				<div class="control-group" style="margin-bottom: 0px;">
					<label class="control-label">姓名：</label>
					<div class="controls" style="margin-top: 5px;">
						<label><?php echo ($member["user_name"]); ?></label>
					</div>
				</div>
				<div class="control-group" style="margin-bottom: 0px;">
					<label class="control-label">电话：</label>
					<div class="controls" style="margin-top: 5px;">
						<label><?php echo ($member["mobile"]); ?></label>
					</div>
				</div>
				<div class="control-group" style="margin-bottom: 0px;">
					<label class="control-label">区域：</label>
					<div class="controls" style="margin-top: 5px;">
						<label>
							<?php if(is_array($member["area"])): $i = 0; $__LIST__ = $member["area"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if(($mod) == "1"): ?>/<?php endif; echo ($vo["province"]); echo ($vo["city"]); echo ($vo["district"]); endforeach; endif; else: echo "" ;endif; ?>
						</label>
					</div>
				</div>
				<table class="table table-hover table-bordered" style="width: 800px;margin-left:90px;">
					<thead>
					<tr>
						<th width="50">ID</th>
						<th width="80">申请时间</th>
						<th width="80">项目名称</th>
						<th width="80">申请人</th>
						<th width="80">申请人电话</th>
						<th width="80">紧急程度</th>
						<th width="80">状态</th>
						<th width="80">操作</th>
					</tr>
					</thead>
					<tbody>
					<?php if(is_array($order)): $i = 0; $__LIST__ = $order;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr>
							<td><?php echo ($val["id"]); ?></td>
							<td><?php echo ($val["create_time"]); ?></td>
							<td><?php echo ($val["project_name"]); ?></td>
							<td><?php echo ($val["user_name"]); ?></td>
							<td><?php echo ($val["mobile"]); ?></td>
							<td>
								<?php if($val["emergency_degree"] == 1): ?>正常<?php endif; ?>
								<?php if($val["emergency_degree"] == 2): ?>紧急<?php endif; ?>
								<?php if($val["emergency_degree"] == 3): ?>非常紧急<?php endif; ?>
							</td>
							<td>
								<?php if($val["state"] == 1): ?>待指派<?php endif; ?>
								<?php if($val["state"] == 2): ?>已驳回<?php endif; ?>
								<?php if($val["state"] == 3): ?>指派中<?php endif; ?>
								<?php if($val["state"] == 4): ?>已接收<?php endif; ?>
								<?php if($val["state"] == 5): ?>已处理<?php endif; ?>
								<?php if($val["state"] == 6): ?>已评价<?php endif; ?>
							</td>
							<td>
								<a href="<?php echo U('Salesrecord/check',array('id'=>$val['id']));?>" class="btn btn-primary"
								   style="margin-right:20px;padding: 2px 15px;color: white;background-color: #1dccaa;">查看详情</a>
							</td>
						</tr><?php endforeach; endif; else: echo "" ;endif; ?>
					<?php if(count($order) == 0): ?><tr><td id="nonumber" colspan="8" style="text-align: center;">暂无数据信息</td><?php endif; ?>
					</tbody>
				</table>
			</fieldset>
            <div class="pagination" style="margin-left: 90px;"><?php echo ($page); ?></div>
		</form>
	</div>
<script src="/public/js/common.js"></script>
<script src="/public/js/layer/layer.js"></script>
<script type="text/javascript">

</script>
</body>
</html>