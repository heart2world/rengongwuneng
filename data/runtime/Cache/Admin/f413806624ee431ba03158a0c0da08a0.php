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
    .bigBox .meitiBox{ float: left; display: inline-block; width: 200px; height: 200px; margin: 0 20px 20px 0; overflow: hidden;}
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
                <label class="control-label" style="margin-right: 20px">故障类型</label>
                <div class="controls" style="margin-top: 5px;">
                    <label style="color: grey">
                        <?php if($check["state"] == 1): ?>待指派<?php endif; ?>
                        <?php if($check["state"] == 2): ?>已驳回<?php endif; ?>
                        <?php if($check["state"] == 3): ?>指派中<?php endif; ?>
                        <?php if($check["state"] == 4): ?>已接收<?php endif; ?>
                        <?php if($check["state"] == 5): ?>已处理<?php endif; ?>
                        <?php if($check["state"] == 6): ?>已评价<?php endif; ?>
                        &emsp;
                        <?php if($check["emergency_degree"] == 1): ?><text style="color: grey">正常</text><?php endif; ?>
                        <?php if($check["emergency_degree"] == 2): ?><text style="color:orange">紧急</text><?php endif; ?>
                        <?php if($check["emergency_degree"] == 3): ?><text style="color: red">非常紧急</text><?php endif; ?>
                    </label>
                </div>

            </div>
            <div class="control-group" style="margin-bottom: 0px;">
                <label class="control-label" style="margin-right: 20px">申请人</label>
                <div class="controls" style="margin-top: 5px;">
                    <label style="color: grey"><?php echo ($check["user_name"]); ?></label>
                </div>
            </div>
            <div class="control-group" style="margin-bottom: 0px;">
                <label class="control-label" style="margin-right: 20px">联系电话</label>
                <div class="controls" style="margin-top: 5px;">
                    <label style="color: grey"><?php echo ($check["mobile"]); ?></label>
                </div>
            </div>
            <div class="control-group" style="margin-bottom: 0px;">
                <label class="control-label" style="margin-right: 20px">报修项目</label>
                <div class="controls" style="margin-top: 5px;">
                    <label style="color: grey"><?php echo ($check["project_name"]); ?></label>
                </div>
            </div>
            <div class="control-group" style="margin-bottom: 0px;">
                <label class="control-label" style="margin-right: 20px">故障类型</label>
                <div class="controls" style="margin-top: 5px;">
                    <label style="color: grey">
                        <?php if($check["fault_type"] == 1): ?>塔机故障<?php endif; ?>
                        <?php if($check["fault_type"] == 2): ?>升降机故障<?php endif; ?>
                        <?php if($check["fault_type"] == 3): ?>扬尘故障<?php endif; ?>
                        <?php if($check["fault_type"] == 4): ?>视频故障<?php endif; ?>
                        <?php if($check["fault_type"] == 5): ?>门禁故障<?php endif; ?>
                    </label>
                </div>
            </div>
            <div class="control-group" style="margin-bottom: 0px;">
                <label class="control-label" style="margin-right: 20px">报修时间</label>
                <div class="controls" style="margin-top: 5px;">
                    <label style="color: grey"><?php echo ($check["create_time"]); ?></label>
                </div>
            </div>
            <div class="control-group" style="margin-bottom: 0px;">
                <label class="control-label" style="margin-right: 20px">报修信息</label>
				<div class="controls" style="margin-top: 5px;margin-left:100px;">
					<label style="color: grey"><?php echo ($check["repairs_info"]); ?></label>
				</div>
            </div>
            <!--报修信息图片-->
            <div class="control-group" style="margin-bottom: 0px;">
                <label class="control-label" style="margin-right: 20px"></label>
				<div class="controls bigBox" style="overflow: hidden;">
	                <?php if(is_array($repaird_info)): $i = 0; $__LIST__ = $repaird_info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i; if($val["url"] != ''): if($val["format"] == 1): ?><div class="meitiBox">
								<a href="<?php echo ($val["url"]); ?>" target="_blank" style="display: inline-block; width: 100%; height: 100%;">
									<img style="width: 100%; height: 100%; object-fit: contain;" src="<?php echo ($val["url"]); ?>"/>
								</a>
							</div>
							<?php else: ?>
							<div class="meitiBox">
								<video controls="controls" style="width: 100%;height: 100%;">
									<source src="<?php echo ($val["url"]); ?>"/>
								</video>
							</div><?php endif; endif; endforeach; endif; else: echo "" ;endif; ?>
				</div>
            </div>
            <?php if($check['state'] ==2): ?><div class="control-group" style="margin-bottom: 0px;">
                <label class="control-label" style="margin-right: 20px">驳回原因</label>
                <div class="controls" style="margin-top: 5px;">
                    <label style="color: grey"><?php echo ($check["reject_message"]); ?></label>
                </div>
            </div><?php endif; ?>
			<?php if($check['state'] !=2 && $check['state'] > 1): ?><div class="control-group" style="margin-bottom: 0px;">
                <label class="control-label" style="margin-right: 20px">处理人</label>
                <div class="controls" style="margin-top: 5px;">
                    <label style="color: grey"><?php echo ($check["cluser_name"]); ?></label>
                </div>
            </div>
            <div class="control-group" style="margin-bottom: 0px;">
                <label class="control-label" style="margin-right: 20px">联系电话</label>
                <div class="controls" style="margin-top: 5px;">
                    <label style="color: grey"><?php echo ($check["clmobile"]); ?></label>
                </div>
            </div>
			<?php if($check['state'] > 4): ?><div class="control-group" style="margin-bottom: 0px;">
                <label class="control-label" style="margin-right: 20px">处理结果</label>
                <div class="controls" style="margin-top: 5px;">
                    <label style="color: grey"><?php echo ($check["deal_message"]); ?></label>
                </div>
            </div>			
            <!--处理结果图片信息-->
            <div class="control-group" style="margin-bottom: 0px;">
                <label class="control-label" style="margin-right: 20px"></label>
                <div class="controls bigBox" style="overflow: hidden;">
	                <?php if(is_array($cljg_info)): $i = 0; $__LIST__ = $cljg_info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i; if($val["url"] != ''): if($val["format"] == 1): ?><div class="meitiBox">
								<a href="<?php echo ($val["url"]); ?>" target="_blank" style="display: inline-block; width: 100%; height: 100%;">
									<img style="width: 100%; height: 100%; object-fit: contain;" src="<?php echo ($val["url"]); ?>"/>
								</a>
							</div>
							<?php else: ?>
							<div class="meitiBox">
								<video controls="controls" style="width: 100%;height: 100%;">
									<source src="<?php echo ($val["url"]); ?>"/>
								</video>
							</div><?php endif; endif; endforeach; endif; else: echo "" ;endif; ?>
                </div>
            </div><?php endif; endif; ?>
			<?php if($check['state'] == 6 && $check['state'] !=2): ?><div class="control-group" style="margin-bottom: 0px;">
                <label class="control-label" style="margin-right: 20px">评价情况</label>
                <div class="controls">
                    <div class="control-group" style="margin-bottom: 0px; margin-top:5px">
                <label class="control-label" style="margin-right: 0px; padding-top: 0;width:201px">1.您对本次服务的总体评价是?&emsp;</label>
                <div class="controls">
                    <label style="color: grey">
                        <!--<?php if($check["total_ate"] == A): ?>A.非常满意<?php endif; ?>-->
                        <!--<?php if($check["total_ate"] == B): ?>B.满意<?php endif; ?>-->
                        <!--<?php if($check["total_ate"] == C): ?>C.比较满意<?php endif; ?>-->
                        <!--<?php if($check["total_ate"] == D): ?>D.不满意<?php endif; ?>-->
                        <?php echo ($check["total_ate"]); ?>
                    </label>
                </div>
            </div>
            <div class="control-group" style="margin-bottom: 0px;">
                <label class="control-label" style="margin-right: 0px; padding-top: 0;width:243px">2.您对本次服务及及时性的满意程度?&emsp;</label>
                <div class="controls">
                    <label style="color: grey">
                        <!--<?php if($check["timeliness_ate"] == A): ?>A.非常满意，立即进行了处理<?php endif; ?>-->
                        <!--<?php if($check["timeliness_ate"] == B): ?>B.满意，很快进行了处理<?php endif; ?>-->
                        <!--<?php if($check["timeliness_ate"] == C): ?>C.比较满意，处理速度还可以<?php endif; ?>-->
                        <!--<?php if($check["timeliness_ate"] == D): ?>D.不满意，很久了都没人处理<?php endif; ?>-->
                        <?php echo ($check["timeliness_ate"]); ?>
                    </label>
                </div>
            </div>
            <div class="control-group" style="margin-bottom: 0px;">
                <label class="control-label" style="margin-right: 0px; padding-top: 0;width:187px">3.您对本次服务的态度印象?&emsp;</label>
                <div class="controls">
                    <label style="color: grey">
                        <!--<?php if($check["attitude_ate"] == A): ?>A.非常好，服务耐心，解答疑问效果好<?php endif; ?>-->
                        <!--<?php if($check["attitude_ate"] == B): ?>B.满意，服务过程细致<?php endif; ?>-->
                        <!--<?php if($check["attitude_ate"] == C): ?>C.比较满意，服务时态度友好<?php endif; ?>-->
                        <!--<?php if($check["attitude_ate"] == D): ?>D.不满意，服务时不好沟通，态度恶劣<?php endif; ?>-->
                        <?php echo ($check["attitude_ate"]); ?>
                    </label>
                </div>
            </div>
            <div class="control-group" style="margin-bottom: 0px;">
                <label class="control-label" style="margin-right: 0px;width:229px; padding-top: 0;">4.您对本次技术人员的专业性评价?&emsp;</label>
                <div class="controls">
                    <label style="color: grey">
                        <!--<?php if($check["proffesional_ate"] == A): ?>A.技术水平很高，能解决遇到的所有问题<?php endif; ?>-->
                        <!--<?php if($check["proffesional_ate"] == B): ?>B.技术水平好，能解决遇到的大部分问题<?php endif; ?>-->
                        <!--<?php if($check["proffesional_ate"] == C): ?>C.技术水平待加强，能解决遇到的简单问题<?php endif; ?>-->
                        <!--<?php if($check["proffesional_ate"] == D): ?>D.技术水平较差，不能解决问题<?php endif; ?>-->
                        <?php echo ($check["proffesional_ate"]); ?>
                    </label>
                </div>
            </div>
                </div>
            </div><?php endif; ?>            
        </fieldset>
    </form>
</div>
<script src="/public/js/common.js"></script>
<script type="text/javascript">
	
</script>
</body>
</html>