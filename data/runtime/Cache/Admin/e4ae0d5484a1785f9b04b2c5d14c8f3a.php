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
	.table tr th{
		text-align: center;
	}
</style>
<body>
	<div class="wrap js-check-wrap">
		<ul class="nav nav-tabs">
			<li><a href="<?php echo U('Salesrecord/index');?>">售后记录</a></li>
			<li class="active"><a href="<?php echo U('Salesrecord/config');?>">超时设置</a></li>
		</ul>
        <form class="form-horizontal">
            <fieldset>
                <div class="control-group" style="margin-bottom: 50px;">
                    <label class="control-label" style="margin-top: 10px">超时设置：</label>
                    <div class="controls" style="margin-top: 5px;">
                       <input id="day" type="text" name="param" placeholder="请输入天数" value="<?php echo ($config["param"]); ?>">
                    </div>
                </div>
                <div class="control-group" style="margin-left: 180px;">
                    <a href="javascript:;" class="btn btn-primary" onclick="save('<?php echo ($config["id"]); ?>')">保存</a>
                </div>
            </fieldset>
        </form>
	</div>
	<script src="/public/js/common.js"></script>
    <script src="/public/js/artDialog/artDialog.js"></script>
	<script type="text/javascript">
        function save(id) {
            var param = $('#day').val();
            $.ajax({
                url: "<?php echo U('Salesrecord/save_config');?>",
                type: 'POST',
                data: {
                    id: id,
                    param: param,
                },
                dataType: "json",
                success: function (res) {
                    if (res.status == 0) {
                        $.dialog({id: 'popup', lock: true, icon: "succeed", content: res.msg, time: 2});
                        setInterval(function () {
                            location.href = '<?php echo U("Salesrecord/config");?>';
                        }, 2000)
                    } else {
                        $.dialog({id: 'popup', lock: true, icon: "warning", content: res.msg, time: 2});
                    }
                }
            });
        }
	</script>
</body>
</html>