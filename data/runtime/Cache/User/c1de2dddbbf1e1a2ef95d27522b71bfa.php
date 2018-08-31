<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
	    <meta name="viewport" content="maximum-scale=1.0,minimum-scale=1.0,user-scalable=0,width=device-width,initial-scale=1.0"/>
	    <meta name="format-detection" content="telephone=no,email=no,date=no,address=no">
		<title>任务详情</title>
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
	</head>
	<style>
		/*.weui-cells:after{ display: none;}*/
		.vedioBox{ width:100%; height:100%; background: url(/public/wx/img/stop.png) no-repeat; background-size: contain;}
		.videoContent{ position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 1000; background: rgba(0,0,0,1);}
		.videoContent .shipin{ width: 100%; height: 100%;}
		.shipinDel{ width: 25px; height: 25px; position: absolute; top: 10px; right: 10px; background: url(/public/wx/img/shanchu@2x.png) no-repeat; background-size: contain; z-index: 1001;}
	</style>
	<body>
		<section class="mainSec" id="app">
			<div class="weui-cells weui-cells_form xialaForm">
	            <div class="weui-cell">
	                <div class="weui-cell__bd">
	                    <p class="title" style="min-width: 90px;">记录状态</p>
	                </div>
	                <div class="weui-cell__ft stateText" :class="state==4?'yjs':(state==5?'ycl':'ypj')" style="display:flex;align-items:center">
	                	<span v-text="stateText"></span>
	                	<span v-text="jjText" class="stateBg" :class="jjState==1?'zc':(jjState==2?'jj':'fcjj')"></span>
	                </div>
	            </div>
	            <div class="weui-cell">
	                <div class="weui-cell__bd" style="align-self:self-start">
	                    <p class="title" style="min-width: 90px;">报修项目</p>
	                </div>
	                <div class="weui-cell__ft stateText" v-text="proName"></div>
	            </div>
	            <div class="weui-cell">
	                <div class="weui-cell__bd" style="align-self:self-start">
	                    <p class="title" style="min-width: 90px;">项目地址</p>
	                </div>
	                <a :href="proUrl" class="weui-cell__ft stateText iconAddress" v-text="proAddress"></a>
	            </div>
	            <div class="weui-cell">
	                <div class="weui-cell__bd">
	                    <p class="title" style="min-width: 90px;">故障类型</p>
	                </div>
	                <div class="weui-cell__ft stateText" v-text="lx"></div>
	            </div>
	            <div class="weui-cell">
	                <div class="weui-cell__bd">
	                    <p class="title" style="min-width: 90px;">报修人</p>
	                </div>
	                <a :href="mobile" class="weui-cell__ft stateText iconPerson" v-text="person"></a>
	            </div>
	            <div class="weui-cell">
	                <div class="weui-cell__bd">
	                    <p class="title" style="min-width: 90px;">报修时间</p>
	                </div>
	                <div class="weui-cell__ft stateText" v-text="times"></div>
	            </div>
	            <div class="weui-cell">
	                <div class="weui-cell__hd"><label class="weui-label stateText">报修信息</label></div>
	            </div>
	            <div>
                    <div style="padding: 0 15px 10px; font-size: .95rem;" v-text="info"></div>
                    <div class="urlBox clearfix" style="padding: 0 0 0 15px;">
                    	<div v-for="prop in picUrls1" class="picBox boxItem">
                    		<img :src="prop.imgs" style="width: 100%; object-fit: cover;" />
                    	</div>
                    	<div v-for="prop in videoUrls1" class="vedioBox boxItem">
                    		<!--<video :src="prop.urls" controls name="media" style="width:100%;height: 100%;"></video>-->
                    		<div class="vedioBox boxItem" @click='playFn($event,prop.imgs)'></div>
                    	</div>
                    </div>
               	</div>
	            <template v-if="state==5||state==6">
		            <div class="weui-cell">
		                <div class="weui-cell__hd"><label class="weui-label stateText">处理结果</label></div>
		            </div>
		            <div>
	                    <div style="padding: 0 15px 10px; font-size: .95rem;" v-text="resultInfo"></div>
	                    <div class="urlBox clearfix" style="padding: 0 0 0 15px;">
	                    	<div v-for="prop in picUrls2" class="picBox boxItem">
	                    		<img :src="prop.imgs" style="width: 100%; object-fit: cover;" />
	                    	</div>
	                    	<div v-for="prop in videoUrls2" class="vedioBox boxItem">
	                    		<!--<video :src="prop.urls" controls name="media" style="width:100%;height: 100%;"></video>-->
								<div class="vedioBox boxItem" @click='playFn($event,prop.imgs)'></div>
	                    	</div>
	                    </div>
	               </div>
                </template>
                <template v-if="state==6">
                	<div class="weui-cell">
		                <div class="weui-cell__hd"><label class="weui-label stateText">评价情况</label></div>
		            </div>
		            <div v-for="prop in pingjiaList" style="padding: 0 15px 10px; font-size: .95rem;">
	                    <div style="padding-bottom: 10px;" v-text="prop.title"></div>
	                    <div style="padding: 0 10px;" v-text="prop.answer"></div>
                   	</div>
                </template>
	        </div>
	        <div v-show="isaction" class="videoContent">
	        	<video class="shipin" controls id="shipin" src=""></video>
	        	<div class="shipinDel" @click="closeVideo()"></div>
	        </div>
	        <a v-if="state==3" class="BTN">立即接收</a>
	        <a v-if="state==4" class="BTN" href="<?php echo U('dealwith',array('id'=>$id));?>">进行处理</a>
		</section>
	</body>
</html>
<script>
var app = new Vue({
		el:'#app',
		data:{
            proName:'<?php echo ($data["project_name"]); ?>',     //项目名称
            proAddress:'<?php echo ($data["project_area"]); ?>',      //项目地址
            proUrl:'http://api.map.baidu.com/marker?location='+'<?php echo ($msgaddress); ?>'+'&title='+'<?php echo ($data["project_name"]); ?>'+'&content='+'<?php echo ($data["project_area"]); ?>'+'&output=html&src=能工科技',   //项目地址导航链接
            lx:'<?php echo ($data["fault_type"]); ?>',      //故障类型
            person:'<?php echo ($data["user_name"]); ?>',     //报修人
            mobile:"tel:"+'<?php echo ($data["mobile"]); ?>',   //报修人电话
            times:'<?php echo ($data["create_time"]); ?>',    //报修时间
            info:'<?php echo ($data["repairs_info"]); ?>',      //报修信息
            resultInfo:'<?php echo ($data["deal_message"]); ?>',   //处理结果
            jjState:'<?php echo ($data["emergency_degree"]); ?>',    //紧急程度（1.非常紧急，2.紧急，3.正常）
            jjText:'<?php echo ($data["degree"]); ?>',    //紧急程度文本
            stateText:'<?php echo ($data["stateText"]); ?>',    //当前状态名
            state:'<?php echo ($data["state"]); ?>',    //当前状态（1.待指派，2.已驳回，3.待接收，4.已接收，5.已处理，6.已评价）
			picUrls1:<?php echo ($pic); ?>,    //图片路径（报修信息）
			videoUrls1:<?php echo ($video); ?>,    //视频路径（报修信息）
			isaction:false,    //是否显示放大视频
			picUrls2:<?php echo ($pic_f); ?>,    //图片路径（处理结果）
			videoUrls2:<?php echo ($video_f); ?>,    //视频路径（处理结果）
			pingjiaList:[
				{
					title:'1.您对本次服务的总体评价是？',
					answer:'<?php echo ($data["total_ate"]); ?>'
				},
				{
					title:'2.您对本次服务及时性的满意程度是？',
					answer:'<?php echo ($data["timeliness_ate"]); ?>'
				},
				{
                    title:'3.您对本次服务态度的印象是？',
                    answer:'<?php echo ($data["attitude_ate"]); ?>'
				},
                {
                    title:'4.您对本次技术人员的专业性评价',
                    answer:'<?php echo ($data["proffesional_ate"]); ?>'
                },
			]      //评价列表
		},
		methods:{
			//关闭全屏视频
			closeVideo:function(){
				var self=this;
				self.isaction=false;
			},
			playFn:function(evt,urls){
				var self=this;
				var src=$(evt.target).find('input').attr('name');
				console.log(111);
				console.log(urls);
				console.log(src);
				app.isaction=true;
				$('#shipin').attr('src',urls);
			}
		}
	});

//放大播放视频
//playFn=function(obj){
//	console.log(111);
//	var src=$(obj).attr('data-src');
//	console.log(src);
//	app.isaction=true;
//	$('#shipin').attr('src',src);
//}
$(function(){
	var width=$(window).width()-60;
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
</script>