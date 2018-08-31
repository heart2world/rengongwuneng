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
    .table tr th, .table tr td {
        text-align: center;
    }
</style>
<body>
	<div class="wrap js-check-wrap">
		<ul class="nav nav-tabs">
			<li class="active"><a href="<?php echo U('Salesrecord/index');?>">售后记录</a></li>
			<li><a href="<?php echo U('Salesrecord/config');?>">超时设置</a></li>
		</ul>
        <form class="form-horizontal">
            <fieldset>
                <div class="control-group" style="margin-bottom: 0px;">
                    <label class="control-label" style="margin-right: 20px">报修项目</label>
                    <div class="controls" style="margin-top: 5px;">
                        <label style="color: grey"><?php echo ($record["project_name"]); ?></label>
                    </div>
                </div>
                <div class="control-group" style="margin-bottom: 0px;">
                    <label class="control-label" style="margin-right: 20px">故障类型</label>
                    <div class="controls" style="margin-top: 5px;">
                        <label style="color: grey">
                            <?php if($record["fault_type"] == 1): ?>塔机故障<?php endif; ?>
                            <?php if($record["fault_type"] == 2): ?>升降机故障<?php endif; ?>
                            <?php if($record["fault_type"] == 3): ?>扬尘故障<?php endif; ?>
                            <?php if($record["fault_type"] == 4): ?>视频故障<?php endif; ?>
                            <?php if($record["fault_type"] == 5): ?>门禁故障<?php endif; ?>
                        </label>
                    </div>
                </div>
                <div class="control-group" style="margin-bottom: 0px;">
                    <label class="control-label" style="margin-right: 20px">申请人</label>
                    <div class="controls" style="margin-top: 5px;">
                        <label style="color: grey"><?php echo ($record["user_name"]); ?></label>
                    </div>
                </div>
                <div class="control-group" style="margin-bottom: 0px;">
                    <label class="control-label" style="margin-right: 20px">联系电话</label>
                    <div class="controls" style="margin-top: 5px;">
                        <label style="color: grey"><?php echo ($record["mobile"]); ?></label>
                    </div>
                </div>
                <div class="control-group" style="margin-bottom: 0px;">
                    <label class="control-label" style="margin-right: 20px">报修时间</label>
                    <div class="controls" style="margin-top: 5px;">
                        <label style="color: grey"><?php echo (date("Y-m-d H:i:s",$record["create_time"])); ?></label>
                    </div>
                </div>
                <table class="table table-hover table-bordered table-list" id="menus-table" style="width: 700px;margin-left: 80px">
                    <thead>
                    <tr>
                        <th width="50">序号</th>
                        <th width="80">时间</th>
                        <th width="80">操作</th>
                        <th width="80">处理人</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php if(is_array($logs)): $i = 0; $__LIST__ = $logs;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr>
                                <td><?php echo ($val["id"]); ?></td>
                                <td><?= date('Y-m-d H:i:s',$val['create_time'])?></td>
                                <td><?php echo ($val["action"]); ?></td>
                                <td>
                                    <?php if($val["uid"] == 1): echo ($val["user_info"]["user_name"]); ?>
                                        <?php else: ?>
										<?php if($val["action"] == '申请售后' || $val["action"] == '评价'): echo ($val["record"]["user_name"]); ?>/<?php echo ($val["record"]["mobile"]); ?>
											<?php else: ?>
											<?php echo ($val["user_info"]["user_name"]); ?>/<?php echo ($val["user_info"]["mobile"]); endif; endif; ?>
                                </td>
                            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                    </tbody>
                </table>
                <div class="pagination" style="margin-left: 90px;"><?php echo ($page); ?></div>
            </fieldset>
        </form>
	</div>
<script src="/public/js/common.js"></script>
</body>
</html>