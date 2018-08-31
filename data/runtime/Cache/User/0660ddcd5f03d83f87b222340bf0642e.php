<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
	    <meta name="viewport" content="maximum-scale=1.0,minimum-scale=1.0,user-scalable=0,width=device-width,initial-scale=1.0"/>
	    <meta name="format-detection" content="telephone=no,email=no,date=no,address=no">
		<title>填写处理信息</title>
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
		.meitiBox:before{ background:none}
	</style>
	<body>
		<section class="mainSec pb3rem" id="app">
			<div class="weui-cells weui-cells_form xialaForm">
	            <div class="weui-cell">
	                <div class="weui-cell__hd"><label class="weui-label stateText">报修信息</label></div>
	            </div>
	            <div>
                    <textarea class="weui-textarea" style="padding: 0 15px 10px;" v-model="info" placeholder="请描述处理信息并上传图片或视频..." rows="2"></textarea>
                    <div class="urlBox clearfix" style="padding: 0 0 0 15px;" id="box">
                    	<!--<div class="picBox boxItem">
                    		<img src="../../img/tupian@2x.png" style="width: 100%; object-fit: cover;" />
                    		<span class="del" @click="delFn($event)"></span>
                    		<input type="hidden" class="urls" />
                    	</div>
                    	<div class="vedioBox boxItem">
                    		<video src="../../img/video.mp4" controls name="media" style="width:100%;height: 100%;"></video>
                    		<span class="del" @click="delFn($event)"></span>
                    		<input type="hidden" class="urls" />
                    	</div>
                    	<div class="vedioBox"></div>
						<div class="vedioBox boxItem">
							<img src="/public/wx/img/stop.png" style="width:100%" />
							<span class="del" onclick="delFn(this)"></span>
						</div>-->
                    </div>
                </div>
                <div class="meitiBox weui-cell">
                	<div class="btnBox">
                		<div class="picBtn" id="pic"></div>
                		<div class="videoBtn" id="video"></div>
                	</div>
                </div>
	        </div>
	        <div v-show="isaction" class="videoContent">
	        	<video class="shipin" controls id="shipin" src=""></video>
	        	<div class="shipinDel" @click="closeVideo()"></div>
	        </div>
	        <div class="BTN" @click="tijiaoFn($event)">完成提交</div>
			<div v-show="isshow" class="meng"></div>
			<div v-show="isshow" class="notice" v-text="notice"></div>
		</section>
	</body>
</html>
<script>
var app = new Vue({
		el:'#app',
		data:{
			id:'<?php echo ($id); ?>',
			info:'',      //报修信息
			picUrls:'',    //图片路径
			videoUrls:'',    //视频路径
			notice:'',      //提示语
			isshow:false,     //是否显示蒙层
			isaction:false    //是否显示放大视频
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
				})
				self.picUrls=self.picUrls.substr(0,self.picUrls.length-1);
				self.videoUrls=self.videoUrls.substr(0,self.videoUrls.length-1);
					console.log(self.picUrls);
					console.log(self.videoUrls);
				if(self.info.length<1){
					self.notice="你还未填写任何处理信息~";
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
				console.log(self.picUrls);
				console.log(self.videoUrls);
				console.log(self.id);
				//ajax
                $.ajax({
                    url: "<?php echo U('User/Center/deal');?>",
                    type: 'POST',
                    data: {id:self.id,deal_message:self.info,pic:self.picUrls,video:self.videoUrls},
                    dataType:"json",
                    success:function (res) {
					console.log(res);
                        if (res.status == 1) {
                            self.notice="任务处理完成，辛苦了~";
                            self.isshow=true;
                            $('body').addClass('overflow');
                            setTimeout(function () {
                                location.href = '<?php echo U("work");?>';
                            }, 1000)
                        }else {
                            self.notice="当前网络繁忙，请稍后再试~";
                            self.isshow=true;
                            $('body').addClass('overflow');
                        }
                    }
                });
			}
		}
	})

$(function(){
	var width=$(window).width()-60;
	console.log(width)
	$('.urlBox>div').width(width/3);
	$('.urlBox>div').height(width/3);
	
	//图片预览
	$(".picBox").bind('click','img',function(evt){
		var html1=evt.currentTarget.innerHTML;
		var urls=$(evt.target).attr("src")
		var preview = $.photoBrowser({
		  items: [
			urls
		  ],
		  onClose:function(){
		  	preview.close();
		  }
		});
		preview.open();
	})
	
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
		console.log(file);
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

//放大播放视频
playFn=function(obj,src){
	console.log(111);
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
</script>