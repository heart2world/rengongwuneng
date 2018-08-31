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
	<div class="wrap js-check-wrap" id="app">
		<ul class="nav nav-tabs">
			<li class="active"><a href="<?php echo U('Salesmember/index');?>">售后人员</a></li>
			<li><a href="<?php echo U('Salesmember/register_code');?>">注册码</a></li>
		</ul>
		
		<form class="form-horizontal" id="tagforms" method="post" enctype="multipart/form-data">	
			<fieldset>
				<div class="control-group" style="margin-bottom: 0px;">
					<label class="control-label">姓名：</label>
					<div class="controls" style="margin-top: 5px;">
						<input type="text" name="user_name" value="<?php echo ($sh["user_name"]); ?>">
					</div>
				</div>
				<div class="control-group" style="margin-bottom: 0px;margin-top:20px">
					<label class="control-label">电话：</label>
					<div class="controls" style="margin-top: 5px;">
						<input type="text" name="mobile" value="<?php echo ($sh["mobile"]); ?>">
					</div>
				</div>
				<div class="control-group" style="margin-bottom: 0px;margin-top:20px;">
					<label class="control-label">区域：</label>
						
						<div class="controls" style="margin-top: 5px;">						
						<div id="distpicker_1" class='area' style="margin-bottom:10px;">
							<select name="shen[]"></select><!-- 省 -->
							<select name="shi[]"></select><!-- 市 -->
							<select name="qu[]"></select><!-- 区 -->							
						</div>
						
						<?php if($area[1]['uid'] != ''): ?><div id="distpicker_2" class='area' style="margin-bottom:10px;">
						<?php else: ?>
						<div id="distpicker_2" class='area' style="margin-bottom:10px;display:none"><?php endif; ?>
							<select name="shen[]"></select><!-- 省 -->
							<select name="shi[]"></select><!-- 市 -->
							<select name="qu[]"></select><!-- 区 -->	
							<a herf="javascript:;" class="btn" onclick="hidearea(2)" style="background:#1abc9c;">删除</a>
						</div>
						
						
						<?php if($area[2]['uid'] != ''): ?><div id="distpicker_3" class='area' style="margin-bottom:10px;">
						<?php else: ?>
						<div id="distpicker_3" class='area' style="margin-bottom:10px;display:none"><?php endif; ?>
							<select name="shen[]"></select><!-- 省 -->
							<select name="shi[]"></select><!-- 市 -->
							<select name="qu[]"></select><!-- 区 -->		
							<a herf="javascript:;" class="btn" onclick="hidearea(3)" style="background:#1abc9c;">删除</a>							
						</div>
						
						
						<?php if($area[3]['uid'] != ''): ?><div id="distpicker_4" class='area' style="margin-bottom:10px;">
						<?php else: ?>
						<div id="distpicker_4" class='area' style="margin-bottom:10px;display:none"><?php endif; ?>
							<select name="shen[]"></select><!-- 省 -->
							<select name="shi[]"></select><!-- 市 -->
							<select name="qu[]"></select><!-- 区 -->
							<a herf="javascript:;" class="btn" onclick="hidearea(4)" style="background:#1abc9c;">删除</a>							
						</div>
						
						<?php if($area[4]['uid'] != ''): ?><div id="distpicker_5" class='area' style="margin-bottom:10px;">
						<?php else: ?>
						<div id="distpicker_5" class='area' style="margin-bottom:10px;display:none;"><?php endif; ?>
							<select name="shen[]"></select><!-- 省 -->
							<select name="shi[]"></select><!-- 市 -->
							<select name="qu[]"></select><!-- 区 -->
							<a herf="javascript:;" class="btn" onclick="hidearea(5)" style="background:#1abc9c;">删除</a>
						</div>
						
						<a class="btn btn-primary addarea"  style="background:#1abc9c;" onclick="addarea()">添加</a>
						</div>	
				</div>	
				<div style="color:red;margin-left: 115px;margin-top: 50px;">注：重复添加的区域视为一个区域</div>
			</fieldset>
			<div class="form-actions">
				<input type="hidden" name="userid" value="<?php echo ($sh["id"]); ?>">
				<input type="button" @click="add()" class="btn btn-primary" value="保存"/>				
			</div>            
		</form>
	</div>
<script src="/public/js/common.js"></script>
<script src="/public/js/distpicker.data.js"></script>
<script src="/public/js/distpicker.js"></script>
<script src="/public/js/layer/layer.js"></script>
<script src="/public/js/vue.js"></script>
<script src="/public/js/content_addtop.js"></script>
<script src="/public/js/define_my.js"></script>
<script src="/public/js/artDialog/artDialog.js"></script>
<script type="text/javascript">
	//添加
	var i=<?php echo ($count); ?>;
	var j=i+1;	
	function addarea(){		
		if(i<=5){
			$("#distpicker_"+j).css('display','block');
			i=i+1;
			j=j+1;
		}		
	}
	function hidearea(val)
	{
		$("#distpicker_"+val).remove();
	}
</script>
<script>
	var app = new Vue({
		el:"#app",
		data:{
			info:{},				
		},
		created:function () {
		},
		methods:{
			add:function () {	
				 var tagvals=$('#tagforms').serialize();				
				$.ajax({
					url:'<?php echo U("Salesmember/edit_post");?>',
					data:tagvals,
					type:"POST",
					dataType:"json",
					success:function (res) {							
						if(res.status==0){
							$.dialog({id: 'popup', lock: true,icon:"succeed", content: res.msg, time: 2});
							setInterval(function(){
								location.href='<?php echo U("Salesmember/index");?>';
							},3000)
						}
						else {
							$.dialog({id: 'popup', lock: true,icon:"warning", content: res.msg, time: 2});
						}
					}

				})
			}
		}
	});	

</script>
<script type="text/javascript">
    $(function () {
        'use strict';
        var $distpicker = $('#distpicker_1');
        $distpicker.distpicker({
            province: '<?php echo ($area["0"]["province"]); ?>',
            city: '<?php echo ($area["0"]["city"]); ?>',
            district: '<?php echo ($area["0"]["district"]); ?>'
        });
    });
</script>
<script type="text/javascript">
    $(function () {
        'use strict';
        var $distpicker = $('#distpicker_2');
        $distpicker.distpicker({
            province: '<?php echo ($area["1"]["province"]); ?>',
            city: '<?php echo ($area["1"]["city"]); ?>',
            district: '<?php echo ($area["1"]["district"]); ?>'
        });
    });
</script>
<script type="text/javascript">
    $(function () {
        'use strict';
        var $distpicker = $('#distpicker_3');
        $distpicker.distpicker({
            province: '<?php echo ($area["2"]["province"]); ?>',
            city: '<?php echo ($area["2"]["city"]); ?>',
            district: '<?php echo ($area["2"]["district"]); ?>'
        });
    });
</script>
<script type="text/javascript">
    $(function () {
        'use strict';
        var $distpicker = $('#distpicker_4');
        $distpicker.distpicker({
            province: '<?php echo ($area["3"]["province"]); ?>',
            city: '<?php echo ($area["3"]["city"]); ?>',
            district: '<?php echo ($area["3"]["district"]); ?>'
        });
    });
</script>
<script type="text/javascript">
    $(function () {
        'use strict';
        var $distpicker = $('#distpicker_5');
        $distpicker.distpicker({
            province: '<?php echo ($area["4"]["province"]); ?>',
            city: '<?php echo ($area["4"]["city"]); ?>',
            district: '<?php echo ($area["4"]["district"]); ?>'
        });
    });
</script>

</body>
</html>