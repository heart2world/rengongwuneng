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
	.laydate-icon {
		background: url('/public/jedate/skin/jedate.png') no-repeat right;
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
	.row-fluid2 {
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
	.time {
		width: 249px;
		height: 15px;
		line-height: 15px;
		padding: 6px 0 6px 10px;
		border: 1px solid #C1C1C1;
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
	 #bg2 {
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
			<li class="active"><a href="<?php echo U('Salesrecord/index');?>">售后记录</a></li>
			<li><a href="<?php echo U('Salesrecord/config');?>">超时设置</a></li>
		</ul>
        <form class="well form-search" method="post" action="<?php echo U('Salesrecord/index');?>">
            <div>
                <label>项目</label>
                <input type="text" value="<?php echo ($pname); ?>" placeholder="请输入项目名称" id="project_name" name="project_name">
                <label style="margin-left: 40px">状态</label>
                <select style="width: 150px" name="state">
                    <option value="" <?= $state == ''?'selected':'' ?>>全部</option>
                    <option value="1" <?= $state == 1?'selected':'' ?>>待指派</option>
                    <option value="2" <?= $state == 2?'selected':'' ?>>已驳回</option>
                    <option value="3" <?= $state == 3?'selected':'' ?>>指派中</option>
                    <option value="4" <?= $state == 4?'selected':'' ?>>已接收</option>
                    <option value="5" <?= $state == 5?'selected':'' ?>>已处理</option>
                    <option value="6" <?= $state == 6?'selected':'' ?>>已评价</option>
                </select>
                <label style="margin-left: 40px">申请时间</label>
                <input class="time laydate-icon" type="text" name="start_time" id="start_time" style="cursor: pointer" placeholder="请选择日期" value="<?php echo ($stime); ?>">
                <input class="time laydate-icon" type="text" name="end_time" id="end_time" style="cursor: pointer" placeholder="请选择日期" value="<?php echo ($etime); ?>">
                <input type="submit" class="btn btn-primary" style="margin-right: 30px;width: 70px;background-color: #00DDAA;" value="查询" />
                <a href="<?php echo U('Salesrecord/export');?>" class="btn btn-primary" style="background-color: #00DDAA">导出EXCEL</a>
            </div>
        </form>
		<form class="js-ajax-form">
			<table class="table table-hover table-bordered table-list" id="menus-table">
				<thead>
					<tr>
						<th width="50">ID</th>
						<th width="80">申请时间</th>
						<th width="80">问题来源</th>
						<th width="80">项目名称</th>
						<th width="80">项目区域</th>
						<th width="110">申请人</th>
						<th width="80">故障类型</th>
						<th width="80">紧急程度</th>
						<th width="110">售后人员</th>
						<th width="80">状态</th>
						<th width="180">操作</th>
					</tr>
				</thead>
				<tbody>
                    <?php if(is_array($records)): $i = 0; $__LIST__ = $records;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i; if($val["time_state"] == 1): ?><tr style="color: red"><?php endif; ?>
                            <td><?php echo ($val["id"]); ?></td>
                            <td><?= date('Y-m-d H:i:s',$val['create_time']) ?></td>
                            <td><a href="javascript:;" onclick="info('<?php echo ($val["avatar"]); ?>','<?php echo ($val["nick_name"]); ?>','<?php echo ($val["openid"]); ?>')">点击查看</a></td>
                            <td><?php echo ($val["project_name"]); ?></td>
							<td><?php echo ($val["project_area"]); ?></td>
							<td><?php echo ($val["user_name"]); ?>/<?php echo ($val["mobile"]); ?></td>
                            <td>
                                <?php if($val["fault_type"] == 1): ?>塔机故障<?php endif; ?>
                                <?php if($val["fault_type"] == 2): ?>升降机故障<?php endif; ?>
                                <?php if($val["fault_type"] == 3): ?>扬尘故障<?php endif; ?>
                                <?php if($val["fault_type"] == 4): ?>视频故障<?php endif; ?>
                                <?php if($val["fault_type"] == 5): ?>门禁故障<?php endif; ?>
                            </td>
							<?php if($val['s_info']['user_name'] != ''): ?><td onclick="showchangestatus('<?php echo ($val["id"]); ?>','<?php echo ($val["emergency_degree"]); ?>')">
							    <?php if($val["emergency_degree"] == 1): ?><span style="color:#1abc9c">正常</span><?php endif; ?>
                                <?php if($val["emergency_degree"] == 2): ?><span style="color:#1abc9c">紧急</span><?php endif; ?>
                                <?php if($val["emergency_degree"] == 3): ?><span style="color:#1abc9c">非常紧急</span><?php endif; ?>
                            </td>
							<?php else: ?>
							<td>
							    <?php if($val["emergency_degree"] == 1): ?>正常<?php endif; ?>
                                <?php if($val["emergency_degree"] == 2): ?>紧急<?php endif; ?>
                                <?php if($val["emergency_degree"] == 3): ?>非常紧急<?php endif; ?>
                            </td><?php endif; ?>
                               
							<td><?php echo ($val["s_info"]["user_name"]); ?>/<?php echo ($val["s_info"]["mobile"]); ?></td>
                            <td>
                                <?php if($val["state"] == 1): ?>待指派<?php endif; ?>
                                <?php if($val["state"] == 2): ?>已驳回<?php endif; ?>
                                <?php if($val["state"] == 3): ?>指派中<?php endif; ?>
                                <?php if($val["state"] == 4): ?>已接收<?php endif; ?>
                                <?php if($val["state"] == 5): ?>已处理<?php endif; ?>
                                <?php if($val["state"] == 6): ?>已评价<?php endif; ?>
                            </td>
                            <td>
                                <?php if($val["state"] == 1): ?><a href="javascript:;" class="btn btn-small" onclick="reject('<?php echo ($val["id"]); ?>')" style="background-color: #00DDAA">驳回</a>
                                    <a href="javascript:;" class="btn btn-small" onclick="designate('<?php echo ($val["id"]); ?>')" style="background-color: #00DDAA">指派</a>
                                    <a href="<?php echo U('Salesrecord/check',array('id'=>$val['id']));?>" class="btn btn-small" style="background-color: #00DDAA">查看详情</a>
                                    <a href="<?php echo U('Salesrecord/process',array('id'=>$val['id']));?>" class="btn btn-small" style="background-color: #00DDAA">进度处理</a><?php endif; ?>
                                <?php if($val["state"] == 2): ?><a href="<?php echo U('Salesrecord/check',array('id'=>$val['id']));?>" class="btn btn-small" style="background-color: #00DDAA">查看详情</a>
                                    <a href="<?php echo U('Salesrecord/process',array('id'=>$val['id']));?>" class="btn btn-small" style="background-color: #00DDAA">进度处理</a><?php endif; ?>
                                <?php if($val["state"] == 3): ?><a href="javascript:;" class="btn btn-small" onclick="reassign('<?php echo ($val["id"]); ?>','<?php echo ($val["emergency_degree"]); ?>','<?php echo ($val["sid"]); ?>')"style="background-color: #00DDAA">改派</a>
                                    <a href="<?php echo U('Salesrecord/check',array('id'=>$val['id']));?>" class="btn btn-small" style="background-color: #00DDAA">查看详情</a>
                                    <a href="<?php echo U('Salesrecord/process',array('id'=>$val['id']));?>" class="btn btn-small" style="background-color: #00DDAA">进度处理</a><?php endif; ?>
                                <?php if($val["state"] == 4): if($val["time_state"] == 1): ?><a href="javascript:;" class="btn btn-small" onclick="reminder('<?php echo ($val["id"]); ?>','<?php echo ($val["uid"]); ?>')" style="background-color: #00DDAA">催单</a><?php endif; ?>
                                    <a href="<?php echo U('Salesrecord/check',array('id'=>$val['id']));?>" class="btn btn-small" style="background-color: #00DDAA">查看详情</a>
                                    <a href="<?php echo U('Salesrecord/process',array('id'=>$val['id']));?>" class="btn btn-small" style="background-color: #00DDAA">进度处理</a><?php endif; ?>
                                <?php if($val["state"] == 5): ?><a href="<?php echo U('Salesrecord/check',array('id'=>$val['id']));?>" class="btn btn-small" style="background-color: #00DDAA">查看详情</a>
                                    <a href="<?php echo U('Salesrecord/process',array('id'=>$val['id']));?>" class="btn btn-small" style="background-color: #00DDAA">进度处理</a><?php endif; ?>
                                <?php if($val["state"] == 6): ?><a href="<?php echo U('Salesrecord/check',array('id'=>$val['id']));?>" class="btn btn-small" style="background-color: #00DDAA">查看详情</a>
                                    <a href="<?php echo U('Salesrecord/process',array('id'=>$val['id']));?>" class="btn btn-small" style="background-color: #00DDAA">进度处理</a><?php endif; ?>
								</td>
								</tr><?php endforeach; endif; else: echo "" ;endif; ?>
                <?php if(count($records) == 0): ?><tr><td id="nonumber" colspan="10" style="text-align: center;">暂无售后记录</td><?php endif; ?>
				</tbody>
			</table>
            <div class="pagination" style="margin-left: 90px;"><?php echo ($page); ?></div>
		</form>
	</div>
    <input type="hidden" id="id" value="">
    <div class="control-group" id="category-list">
        <div class="row-fluid" id="reassign" style="display: none">
            <div style="margin-top:70px;margin-left:40px;margin-bottom: 5px">
                <div style="margin-bottom: 30px">
                    <table style="width: 100%">
                        <tr>
                            <td style="width: 20%">紧急度：</td>
                            <td>
                                <select id="reassign_emergency_degree">
                                    <option value="3">非常紧急</option>
                                    <option value="2">紧急</option>
                                    <option value="1">正常</option>
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>
                <div style="margin-bottom: 30px">
                    <table style="width: 100%">
                        <tr>
                            <td style="width: 20%">售后员：</td>
                            <td>
                               <select style="overflow: hidden;text-overflow: ellipsis;white-space: nowrap;" name="sales_member" id="reassign_sales_member">
                                   <?php if(is_array($sales)): $i = 0; $__LIST__ = $sales;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>">
										<?php echo ($vo["user_name"]); ?>  <?php echo ($vo["mobile"]); ?>(
										<?php if(is_array($vo["area"])): $i = 0; $__LIST__ = $vo["area"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i; echo ($item["district"]); ?>&nbsp;<?php endforeach; endif; else: echo "" ;endif; ?>)
									   </option><?php endforeach; endif; else: echo "" ;endif; ?>
                               </select>
                            </td>
                        </tr>
                    </table>
                </div>
	<!--			<div style="margin-bottom: 10px">
                    <table style="width: 100%">
                        <tr>
                            <td style="width: 20%">负责区域：</td>
                            <td>
                               <select name="assigned_area" id="assigned_area">
                                   <?php if(is_array($sales["0"]["area"])): $i = 0; $__LIST__ = $sales["0"]["area"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value='<?php echo ($vo["id"]); ?>'><?php echo ($vo["province"]); ?> <?php echo ($vo["city"]); ?> <?php echo ($Vo["district"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                               </select>
                            </td>
                        </tr>
                    </table>
                </div>    -->
				<input type="hidden" id="step" value="">
            <div style="height: 30px;border-bottom: 1px solid #ccc;"></div>
            <div style="text-align: center;margin-top: 10px;">
                <a href="javascript:;" class="btn btn-primary" onclick="change_sales()">立即指派</a>
            </div>
        </div>
        </div>

        <div class="row-fluid" id="reject" style="display: none">
            <div style="margin-top:40px;margin-left:40px;margin-bottom: 5px">
                <label style="margin-left: 60px">请填写驳回原因</label>
                <div style="margin-bottom: 20px">
                    <textarea rows="6" style="margin-left: 0px" id="message"></textarea>
                </div>
                <div style="height: 30px;border-bottom: 1px solid #ccc;margin-left: -38px"></div>
                <div style="text-align: center;margin-top: 10px; margin-left: -50px">
                    <a href="javascript:;" class="btn btn-primary" onclick="reject_post()">驳回</a>
                </div>
            </div>
        </div>

        <div class="row-fluid" id="info" style="display: none">
            <div style="margin-top:40px;margin-left:40px;margin-bottom: 5px">
                <label style="margin-left: 150px;margin-bottom: 30px;font-size: large;">问题来源</label>
                <div style="margin-bottom: 20px">
                    <table style="width: 100%">
                        <tr>
                            <td style="width: 20%">微信头像</td>
                            <td>
                               <img id="photo" src="">
                            </td>
                        </tr>
                    </table>
                </div>
                <div style="margin-bottom: 20px">
                    <table style="width: 100%">
                        <tr>
                            <td style="width: 20%">微信昵称</td>
                            <td id="nick_name"></td>
                        </tr>
                    </table>
                </div>
                <div style="margin-bottom: 20px">
                    <table style="width: 100%">
                        <tr>
                            <td style="width: 20%">OPENID</td>
                            <td id="openid"></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="row-fluid2" id="reminder" style="display: none">
            <div style="margin-top:70px;margin-bottom: 5px">
                <div style="margin-bottom: 30px">
                    <table style="margin:0 auto;">
                        <tr>
                            <td>紧急度：</td>
                            <td>
                                <select id="reminder_emergency_degree">
                                    
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>
                <div style="height: 30px;border-bottom: 1px solid #ccc;"></div>
                <div style="text-align: center;margin-top: 10px;">
					<input type="hidden" id="idsstr" value="">
                    <a href="javascript:;" class="btn btn-primary" onclick="close_div2()">取消</a>&nbsp;&nbsp;&nbsp;
                    <a href="javascript:;" class="btn btn-primary" onclick="change_status()">确认</a>
                </div>
            </div>
        </div>
    </div>
<div id="bg" onclick="close_div()"></div>
<div id="bg2" onclick="close_div2()"></div>
<script src="/public/js/common.js"></script>
<script src="/public/js/layer/layer.js"></script>
<script src="/public/jedate/jedate.js"></script>
<script type="text/javascript">
    $(function () {
        jeDate("#start_time",{
            theme:{bgcolor:"#00A680",pnColor:"#00DDAA"},
            format: "YYYY-MM-DD hh:mm:ss",
        });
        jeDate("#end_time",{
            theme:{bgcolor:"#00A680",pnColor:"#00DDAA"},
            format: "YYYY-MM-DD hh:mm:ss",
        });
    });
	
	$("select[name='sales_member']").change(function(){
		var sales = <?php echo ($arr); ?>;
        var uid = $(this).val();
		for(var i=sales.length-1;i>=0;i--){
			if(uid == sales[i]['id'])
			{
				$('#assigned_area').empty();
				areas=sales[i]['area'];
				for(var j=areas.length-1;j>=0;j--)
				{
					$('#assigned_area').append('<option value="'+areas[j].id+'">'+areas[j].province+' '+areas[j].city+' '+areas[j].district+'</option>');
				}
			}
		}
    });

    function close_div() {
        $('.row-fluid').css('display', 'none');
        $('#bg').css('display', 'none');
    }
	function close_div2() {
        $('.row-fluid2').css('display', 'none');
        $('#bg2').css('display', 'none');
    }
	
	// 改变紧急程度
	function showchangestatus(id,status)
	{
		$('#bg2').css('display', 'block');
		$('.row-fluid2').css('display', 'block');
		if(status ==1)
		{
			var html='<option value="3">非常紧急</option>'+
				 '<option value="2">紧急</option>'+
				 '<option value="1" selected>正常</option>';
		}else if(status ==2)
		{
			var html='<option value="3">非常紧急</option>'+
				 '<option value="2" selected>紧急</option>'+
				 '<option value="1">正常</option>';
		}else{
			var html='<option value="3" selected>非常紧急</option>'+
				 '<option value="2">紧急</option>'+
				 '<option value="1">正常</option>';
		}
		$("#reminder_emergency_degree").html(html);
		$("#idsstr").val(id);
	}
    //改派
    function reassign(id,emergency,sid) {
        $('#bg').css('display', 'block');
        $('#reassign').css('display', 'block');
        document.getElementById('id').value = id;
        document.getElementById('reassign_emergency_degree').value=emergency;
        document.getElementById('reassign_sales_member').value=sid;
		document.getElementById('step').value = 2;
    }

    //指派
    function designate(id) {
        $('#bg').css('display', 'block');
        $('#reassign').css('display', 'block');
        document.getElementById('id').value = id;
		document.getElementById('step').value = 1;
    }

    function change_sales() {
        $('#bg').css('display', 'none');
        $('#reassign').css('display', 'none');
        var id = $('#id').val();
        var sid = $('#reassign_sales_member').val();
        var emergency_degree = $('#reassign_emergency_degree').val();
		var step = $('#step').val();
        $.ajax({
            url: "<?php echo U('Salesrecord/reassign');?>",
            type: 'POST',
            data: {
                id: id,
                sid: sid,
                emergency_degree: emergency_degree,
				state : 3,
				step : step
            },
            dataType: "json",
            success: function (res) {
                if (res.status == 0) {
                    $.dialog({id: 'popup', lock: true, icon: "succeed", content: res.msg, time: 2});
                    setInterval(function () {
                        location.href = '<?php echo U("Salesrecord/index");?>';
                    }, 2000)
                } else {
                    $.dialog({id: 'popup', lock: true, icon: "warning", content: res.msg, time: 2});
                }
            }
        });
    }

    //驳回
    function reject(id) {
        $('#bg').css('display', 'block');
        $('#reject').css('display', 'block');
        document.getElementById('id').value = id;
    }

    function reject_post() {
        $('#bg').css('display', 'none');
        $('#reject').css('display', 'none');
        var id = $('#id').val();
        var message = $('#message').val();
        if(message==""){
            alert('请填写驳回原因');
            return;
        }
        $.ajax({
            url: "<?php echo U('Salesrecord/reject');?>",
            type: 'POST',
            data: {
                id: id,
                reject_message: message,
                state:2
            },
            dataType: "json",
            success: function (res) {
                if (res.status == 0) {
                    $.dialog({id: 'popup', lock: true, icon: "succeed", content: res.msg, time: 2});
                    setInterval(function () {
                        location.href = '<?php echo U("Salesrecord/index");?>';
                    }, 2000)
                } else {
                    $.dialog({id: 'popup', lock: true, icon: "warning", content: res.msg, time: 2});
                }
            }
        });
    }

    function info(photo,name,openid) {
        $('#bg').css('display', 'block');
        $('#info').css('display', 'block');
        document.getElementById('photo').src = photo;
        document.getElementById('nick_name').innerText = name;
        document.getElementById('openid').innerText = openid;
    }

    function reminder(id,uid) {
        $.ajax({
            url: "<?php echo U('Salesrecord/reminder');?>",
            type: 'POST',
            data: {
                id: id,
                uid: uid,
            },
            dataType: "json",
            success: function (res) {
				console.log(res);
                if (res.status == 0) {
                    $.dialog({id: 'popup', lock: true, icon: "succeed", content: res.msg, time: 2});
                    setInterval(function () {
                        location.href = '<?php echo U("Salesrecord/index");?>';
                    }, 2000)
                } else {
                    $.dialog({id: 'popup', lock: true, icon: "warning", content: res.msg, time: 2});
                }
            }
        });
    }
	
	function change_box(){
		$('#bg').css('display', 'block');
        $('#reminder').css('display', 'block');
        document.getElementById('id').value = id;	
	}

    //更变紧急程度
    function change_status() {
        var id = $('#idsstr').val();
        var emergency_degree = $('#reminder_emergency_degree').val();
        $.ajax({
            url: "<?php echo U('Salesrecord/change_status2');?>",
            type: 'POST',
            data: {
                id: id,
                emergency_degree: emergency_degree,
            },
            success: function (res) {
				$('.row-fluid2').css('display', 'none');
				$('#bg2').css('display', 'none');
                if (res.status == 0) {
                    $.dialog({id: 'popup', lock: true, icon: "succeed", content: res.msg, time: 2});
                    setInterval(function () {
                        location.href = '<?php echo U("Salesrecord/index");?>';
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