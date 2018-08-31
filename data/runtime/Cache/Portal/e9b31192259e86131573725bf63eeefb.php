<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="maximum-scale=1.0,minimum-scale=1.0,user-scalable=0,width=device-width,initial-scale=1.0"/>
	<meta name="format-detection" content="telephone=no,email=no,date=no,address=no">
	<title>在线报修</title>
	<link rel="stylesheet" href="/public/wx/lib/weui/jquery-weui.css" />
	<link rel="stylesheet" href="/public/wx/lib/weui/weui.min.css" />
	<link rel="stylesheet" href="/public/wx/lib/swiper/swiper.min.css" />
	<link rel="stylesheet" href="/public/wx/css/public.css" />
	<link rel="stylesheet" href="/public/wx/css/style.css" />
	<script type="text/javascript" src="/public/wx/lib/jq/jquery-1.10.2.js" ></script>
	<script type="text/javascript" src="/public/wx/lib/weui/jquery-weui.js" ></script>
	<script type="text/javascript" src="/public/wx/js/v.min.js" ></script>
	<script type="text/javascript" src="http://jqweui.com/dist/js/swiper.js" ></script>
	<script type="text/javascript" src="/public/wx/js/common.js" ></script>
	<script src="/public/layui/dist/layui.js" charset="utf-8"></script>
</head>
<style>
		.vedioBox{ width:100%; height:100%; background: url(/public/wx/img/stop.png) no-repeat; background-size: contain;}
		.videoContent{ position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 1000; background: rgba(0,0,0,1);}
		.videoContent .shipin{ width: 100%; height: 100%;}
		.shipinDel{ width: 25px; height: 25px; position: absolute; top: 10px; right: 10px; background: url(/public/wx/img/shanchu@2x.png) no-repeat; background-size: contain; z-index: 1001;}
	body .layui-upload-file{ display:none}
</style>
<body>
<section class="mainSec pb3rem" id="app">
	<div class="weui-cells weui-cells_form xialaForm">
		<a class="weui-cell jt" href="<?php echo U('Index/chose_project');?>">
			<div class="weui-cell__hd"><label class="weui-label">项目名称</label></div>
			<div class="weui-cell__bd">
				<input class="weui-input ellipsis" type="text" v-model="proName" readonly placeholder="请选择报修项目" style="width:90%">
			</div>
		</a>
		<div class="weui-cell">
			<div class="weui-cell__hd"><label class="weui-label">项目地址</label></div>
			<div class="weui-cell__bd">
				<input class="weui-input ellipsis" type="text" v-model="proAddress" readonly placeholder="选择项目后可见">
			</div>
		</div>
		<div class="weui-cell jt" style="padding: 0 15px;">
			<div class="weui-cell__hd"><label class="weui-label">故障类型</label></div>
			<div class="weui-cell__bd">
				<select class="weui-select" v-model='lx' style="padding-left: 0;z-index: 0;">
					<option value="0">请选择故障类型</option>
					<option value="1">塔机故障</option>
					<option value="2">升降机故障</option>
					<option value="3">扬尘故障</option>
					<option value="4">视频故障</option>
					<option value="5">门禁故障</option>
				</select>
			</div>
		</div>
		<div class="weui-cell">
			<div class="weui-cell__hd"><label class="weui-label">联系人</label></div>
			<div class="weui-cell__bd">
				<input class="weui-input" type="text" v-model="person" placeholder="请输入联系人姓名">
			</div>
		</div>
		<div class="weui-cell">
			<div class="weui-cell__hd"><label class="weui-label">联系电话</label></div>
			<div class="weui-cell__bd">
				<input class="weui-input" type="tel" v-model="perTel" placeholder="请输入联系电话">
			</div>
		</div>
		<div class="weui-cell">
			<div class="weui-cell__hd"><label class="weui-label">报修信息</label></div>
		</div>
		<!--<div>
			<textarea style="padding: 0 15px 15px;min-height:4rem;width:100%" v-model="info" placeholder="您需要文字描述问题，并上传清晰的故障照片或视频，帮助售后人员定位问题..." rows="3"></textarea>
			-->
			<div style="padding:0 15px 10px">
                <div class="weui-cell__bd">
                    <textarea class="weui-textarea" v-model="info" placeholder="您需要文字描述问题，并上传清晰的故障照片或视频，帮助售后人员定位问题..." rows="2"></textarea>
                </div>
            </div>
			<div class="urlBox clearfix" style="padding: 0 0 0 15px;" id="box">
				<!--<div class="vedioBox boxItem">
					<img src="/public/wx/img/stop.png" style="width:100%" />
					<span class="del" onclick="delFn(this)"></span>
				</div>-->
			</div>
		</div>
		<div class="meitiBox">
			<div class="btnBox">
				<div class="picBtn" id="pic"></div>
				<div class="videoBtn" id="video">
					<!--<input type="file" accept="video/*" capture="camcorder">-->
				</div>
			</div>
		</div>
	</div>
    <div v-show="isaction" class="videoContent">
    	<video class="shipin" controls id="shipin" src=""></video>
    	<div class="shipinDel" @click="closeVideo()"></div>
    </div>
	<div class="BTN" @click="tijiaoFn($event)">立即提交</div>
	<div v-show="isshow" class="meng"></div>
	<div v-show="isshow" class="notice" v-text="notice"></div>
</section>
<footer class="mainFooter">
	<a class="zxbx active">在线报修</div></a>
	<a class="wdbx" href="<?php echo U('Index/service');?>">我的报修</div></a>
</footer>
</body>
</html>
<script>
    var app = new Vue({
        el:'#app',
        data:{
            proName:'<?php echo ($project_info["project_name"]); ?>',     //项目名称
            proAddress:'<?php echo ($project_info["address"]); ?>'?'<?php echo ($project_info["address"]); ?>':'项目暂无地址',      //项目地址
            lx:0,      //故障类型
            person:'',    //联系人
            perTel:'',     //联系电话
            info:'',      //报修信息
            picUrls:'',    //图片路径
            videoUrls:'',    //视频路径
            notice:'',      //提示语
            isshow:false,     //是否显示蒙层
			isaction:false     //是否显示放大视频
        },
        methods:{
            setTime:function(){
                var self=this;
                setTimeout(function() {
                    self.isshow=false;
                }, 2000)
            },
            //删除图片，视频
            delFn:function(evt){
                var self=this;
                $(evt.target).parents('.boxItem').remove();
            },
			//关闭全屏视频
			closeVideo:function(){
				var self=this;
				self.isaction=false;
			},
            //点击提交
            tijiaoFn:function(evt){
                var self=this;
                $('.boxItem').each(function(){
                    if($(this).hasClass('picBox')){
                        self.picUrls+=$(this).find("input").val()+",";
                    }
                    else{
                        self.videoUrls+=$(this).find("input").val()+",";
                    }
                });
                self.picUrls=self.picUrls.substr(0,self.picUrls.length-1);
                self.videoUrls=self.videoUrls.substr(0,self.videoUrls.length-1);
                if(self.proName.length<1){
                    self.notice="请选择报修项目";
                    self.isshow=true;
                    self.setTime();
                    return
                }
                if(self.lx==0){
                    self.notice="请选择故障类型";
                    self.isshow=true;
                    self.setTime();
                    return
                }
                if(self.person.length<1){
                    self.notice="请输入联系人姓名";
                    self.isshow=true;
                    self.setTime();
                    return
                }
                if(self.perTel.length!=11){
                    self.notice="请输入正确联系电话";
                    self.isshow=true;
                    self.setTime();
                    return
                }
                if(self.info.length<1){
                    self.notice="请输入报修描述";
                    self.isshow=true;
                    self.setTime();
                    return
                }
				if(self.picUrls.length<1&&self.videoUrls.length<1){
					self.notice="请上传故障照片或视频";
					self.isshow=true;
					self.setTime();
					return
				}
				console.log(self.picUrls,self.videoUrls);
                $.showLoading("正在提交");
                //ajax
                $.ajax({
                    url: "<?php echo U('Index/repairs_post');?>",
                    type: 'POST',
                    data: {project_name: self.proName,project_area:self.proAddress,fault_type:self.lx,mobile:self.perTel,repairs_info:self.info,user_name:self.person,pic:self.picUrls,video:self.videoUrls},
                    dataType: "json",
                    success: function (res) {
						$.hideLoading();
                        if (res.status == 1) {
                            self.notice="感谢您的反馈我们将尽快为您处理~";
                            self.isshow=true;
                            $('body').addClass('overflow');
							 setInterval(function () {
                                location.href = '<?php echo U("Index/service");?>';
                            }, 1000)
                        } else {
                            self.notice="网络信息信息繁忙，请稍后再试";
                            self.isshow=true;
                            $('body').addClass('overflow');
                        }
                    }
                });
            }
        }
    });

    $(function(){
        var width=$(window).width()-60;
        console.log(width)
        $('.urlBox>div').width(width/3);
        $('.urlBox>div').height(width/3);

    })
	
	//多图片上传
layui.use('upload', function() {
    var $ = layui.jquery
        , upload = layui.upload;
    upload.render({
        elem: '#pic'
        , url: '<?php echo U("Portal/Index/save_upload");?>'
        , multiple: true
		, accept: 'images'
		, method: 'post'
		, field: 'image'
        , done: function (file) {
			src="./data/upload/"+file.image.savepath+ file.image.savename;
			$('#box').append('<div class="picBox boxItem">'+
								'<div style="width:100%;height:100%;overflow:hidden">'+
								'<img src="'+src+'" alt="' + file.image.name + '" style="width: 100%; object-fit: cover;" onclick=\'showBig("'+src+'")\'>'+
								'</div>'+
								'<span class="del" onclick="delFn(this)"></span>\n' +
								'\t\t<input type="hidden" class="urls" value="'+src+'" /></div>');
            var width=$(window).width()-60;
            $('.urlBox>div').width(width/3);
            $('.urlBox>div').height(width/3);
        }
        , error: function (res) {
            //上传完毕
			alert('照片不能大于10M，请重新上传图片')
        }
    });
	upload.render({
		elem: '#video'
		,url: '<?php echo U("Portal/Index/save_upload");?>'
		,accept: 'video' //视频
		,done: function(file){
			console.log(file);
			src="./data/upload/"+file.file.savepath+ file.file.savename;
			$('#box').append('<div class="vedioBox boxItem" onclick=\'playFn(this,"'+src+'")\'>'+
							'<span class="del" onclick="delFn(this)"></span>'+
							'<input type="hidden" class="urls" value="'+src+'" />'+
						'</div>');
            var width=$(window).width()-60;
            $('.urlBox>div').width(width/3);
            $('.urlBox>div').height(width/3);
		}
  });
});

//删除图片，视频
delFn=function(obj){
    $(obj).parents('.boxItem').remove();
}
playFn=function(obj,ids){
	app.isaction=true;
	$('#shipin').attr('src',src);
}

showBig=function(src){
console.log(src)
	var preview = $.photoBrowser({
		items: [
			src
		],
		onClose:function(){
			preview.close();
		}
	});
	preview.open();
}
        //图片预览
        /*$("img").bind('click',function(evt){
		console.log(111);
            var html1=evt.currentTarget.innerHTML;
            var urls=$(this).attr("src")
            var preview = $.photoBrowser({
                items: [
                    urls
                ],
                onClose:function(){
                    preview.close();
                }
            });
            preview.open();
        })*/
	
</script>