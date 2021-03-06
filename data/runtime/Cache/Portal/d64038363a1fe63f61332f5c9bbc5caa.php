<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
	    <meta name="viewport" content="maximum-scale=1.0,minimum-scale=1.0,user-scalable=0,width=device-width,initial-scale=1.0"/>
	    <meta name="format-detection" content="telephone=no,email=no,date=no,address=no">
		<title>报修详情</title>
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
	                <div v-if="state==1" class="weui-cell__ft stateText dzp">待指派</div>
	                <div v-if="state==2" class="weui-cell__ft stateText ybh">已驳回</div>
	                <div v-if="state==3" class="weui-cell__ft stateText zpz">指派中</div>
	                <div v-if="state==4" class="weui-cell__ft stateText yjs">已接收</div>
	                <div v-if="state==5" class="weui-cell__ft stateText ycl">已处理</div>
	                <div v-if="state==6" class="weui-cell__ft stateText ypj">已评价</div>
	            </div>
	            <div class="weui-cell">
	                <div class="weui-cell__bd" style="align-self:self-start">
	                    <p class="title" style="min-width: 90px;">报修项目</p>
	                </div>
	                <div class="weui-cell__ft stateText"><?php echo ($data["project_name"]); ?></div>
	            </div>
	            <div class="weui-cell">
	                <div class="weui-cell__bd" style="align-self:self-start">
	                    <p class="title" style="min-width: 90px;">项目地址</p>
	                </div>
	                <div class="weui-cell__ft stateText"><?php echo ($data["project_area"]); ?></div>
	            </div>
	            <div class="weui-cell">
	                <div class="weui-cell__bd">
	                    <p class="title" style="min-width: 90px;">故障类型</p>
	                </div>
	                <div class="weui-cell__ft stateText"><?php echo ($data["fault_type"]); ?></div>
	            </div>
	            <div class="weui-cell">
	                <div class="weui-cell__bd">
	                    <p class="title" style="min-width: 90px;">报修时间</p>
	                </div>
	                <div class="weui-cell__ft stateText"><?= date('Y-m-d H:i:s',$data['create_time']) ?></div>
	            </div>
	            <div class="weui-cell">
	                <div class="weui-cell__hd"><label class="weui-label stateText">报修信息</label></div>
	            </div>
	            <div>
                    <div style="padding: 0 15px 10px; font-size: .95rem;"><?php echo ($data["repairs_info"]); ?></div>
                    <div class="urlBox clearfix" style="padding: 0 0 0 15px;">
                    	<div v-for="prop in picUrls1" class="picBox boxItem">
                    		<img :src="prop.imgs" style="width: 100%; object-fit: cover;" />
                    	</div>
                    	<div v-for="prop in videoUrls1" class="vedioBox boxItem">
                    		<!--<video :src="prop.url" controls name="media" style="width:100%;height: 100%;"></video>-->
							<div class="vedioBox boxItem" @click='playFn($event,prop.imgs)'></div>
                    	</div>
                    </div>
                </div>
	            <div v-if="state==2" class="weui-cell">
	                <div class="weui-cell__bd" style="align-self:self-start">
	                    <p class="title" style="min-width: 90px;">驳回原因</p>
	                </div>
	                <div class="weui-cell__ft stateText ybh"><?php echo ($data["reject_message"]); ?></div>
	            </div>
	            <template v-if="state==3||state==4||state==5|| state==6">
		            <div class="weui-cell">
		                <div class="weui-cell__bd">
		                    <p class="title" style="min-width: 90px;">紧急程度</p>
		                </div>
		                <div class="weui-cell__ft jinjiText fcjj">
							<?php if($data["emergency_degree"] == 1): ?>正常<?php endif; ?>
							<?php if($data["emergency_degree"] == 2): ?>紧急<?php endif; ?>
							<?php if($data["emergency_degree"] == 3): ?>非常紧急<?php endif; ?>
						</div>
		            </div>
		            <div class="weui-cell">
		                <div class="weui-cell__bd">
		                    <p class="title" style="min-width: 90px;">处理人员</p>
		                </div>
		                <a :href="mobile" class="weui-cell__ft stateText callName"><?php echo ($info["user_name"]); ?></a>
		            </div>
	            </template>
	            <template v-if="state==5 || state==6">
		            <div class="weui-cell">
		                <div class="weui-cell__hd"><label class="weui-label stateText">处理结果</label></div>
		            </div>
		            <div>
	                    <div style="padding: 0 15px 10px; font-size: .95rem;"><?php echo ($data["repairs_info"]); ?></div>
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
	        <a v-if="state==5" class="BTN" href="<?php echo U('Portal/Index/evaluate',array('id'=>$data['id']));?>">前往评价</a>
		</section>
	</body>
</html>
<script>
var app = new Vue({
		el:'#app',
		data:{
			proName:'<?php echo ($data["project_name"]); ?>',     //项目名称
			proAddress:'<?php echo ($data["project_area"]); ?>',      //项目地址
			lx:'<?php echo ($data["fault_type"]); ?>',      //故障类型
			times:'',    //报修时间
			info:'',      //报修信息
			state:'<?php echo ($data["state"]); ?>',    //当前状态（1.待指派，2.已驳回，3.指派中，4.已接收，5.已处理，6.已评价）
			mobile:"tel:"+'<?php echo ($info["mobile"]); ?>',     //处理人员电话
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
					title:'4.您对本次技术人员的专业性评价？',
					answer:'<?php echo ($data["proffesional_ate"]); ?>'
				}
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
				app.isaction=true;
				$('#shipin').attr('src',urls);
			},
		}
	})

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