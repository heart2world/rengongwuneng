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
<body>
	<div class="wrap js-check-wrap">
		<ul class="nav nav-tabs">
			<li class="active"><a href="<?php echo U('rbac/index');?>"><?php echo L('ADMIN_RBAC_INDEX');?></a></li>
			<li><a href="<?php echo U('rbac/roleadd');?>"><?php echo L('ADMIN_RBAC_ROLEADD');?></a></li>
		</ul>
		<form action="<?php echo U('Rbac/listorders');?>" method="post">
			<table class="table table-hover table-bordered">
				<thead>
					<tr>
						<th style="text-align:center;max-width:60px;">ID</th>
						<th style="text-align:center;max-width:80px;"><?php echo L('ROLE_NAME');?></th>
						<th  style="text-align:center;max-width:100px;"><?php echo L('ROLE_DESCRIPTION');?></th>
						<th  style="text-align:center;max-width:30px;"><?php echo L('STATUS');?></th>
						<th  style="text-align:center;max-width:120px;"><?php echo L('ACTIONS');?></th>
					</tr>
				</thead>
				<tbody>
					<?php if(is_array($roles)): foreach($roles as $key=>$vo): ?><tr>
						<td style="text-align:center"><?php echo ($vo["id"]); ?></td>
						<td style="text-align:center"><?php echo ($vo["name"]); ?></td>
						<td style="text-align:center"><?php echo ($vo["remark"]); ?></td>
						<td style="text-align:center">
							<?php if($vo['status'] == 1): ?><font color="red">√</font>
							<?php else: ?> 
								<font color="red">╳</font><?php endif; ?>
						</td>
						<td style="text-align:center">
							<?php if($vo['id'] == 1): ?><font style="padding: 2px 15px;color: white;background-color: #cccccc;" class="btn btn-primary"><?php echo L('ROLE_SETTING');?></font>
								<font style="padding: 2px 15px;color: white;background-color: #cccccc;" class="btn btn-primary"><?php echo L('EDIT');?></font> 
								<font style="padding: 2px 15px;color: white;background-color: #cccccc;" class="btn btn-primary"><?php echo L('DELETE');?></font>
							<?php else: ?>
								<a href="<?php echo U('Rbac/authorize',array('id'=>$vo['id']));?>" style="padding: 2px 15px;color: white;background-color: #1dccaa;" class="btn btn-primary"><?php echo L('ROLE_SETTING');?></a>
								<a href="<?php echo U('Rbac/roleedit',array('id'=>$vo['id']));?>" style="padding: 2px 15px;color: white;background-color: #1dccaa;" class="btn btn-primary"><?php echo L('EDIT');?></a>
								<a class="js-ajax-delete btn btn-pr" style="padding: 2px 15px;color: white;background-color: #1dccaa;" class="btn btn-primary" href="<?php echo U('Rbac/roledelete',array('id'=>$vo['id']));?>"><?php echo L('DELETE');?></a><?php endif; ?>
						</td>
					</tr><?php endforeach; endif; ?>
				</tbody>
			</table>
		</form>
	</div>
	<script src="/public/js/common.js"></script>
</body>
</html>