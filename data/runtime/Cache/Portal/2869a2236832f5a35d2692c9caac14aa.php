<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
	    <meta name="viewport" content="maximum-scale=1.0,minimum-scale=1.0,user-scalable=0,width=device-width,initial-scale=1.0"/>
	    <meta name="format-detection" content="telephone=no,email=no,date=no,address=no">
		<title>评价此次服务</title>
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
		.weui-cells:after, .weui-cells:before,body .weui-cell:before{display: none;}
		.weui-cells{ font-size: .95rem;}
		.weui-cell{padding: 0 10px 10px;}
	</style>
	<body>
		<section class="mainSec pb3rem" id="app">
			<div v-for="prop in pingjiaList" style="padding: 10px 15px 0; font-size: .95rem;">
                <div style="padding-bottom: 10px;"v-text="prop.title"></div>
				<div class="weui-cells weui-cells_radio testBox">
		            <label class="weui-cell weui-check__label" v-for="item in prop.answer">
		                <div class="weui-cell__hd">
		                    <input type="radio" @change="choseFn($event,prop.title,item.biao,item.chose)" class="weui-check" :name="prop.names">
		                    <i class="weui-icon-checked"></i>
		                </div>
		                <div class="weui-cell__bd">
		                    <p v-text="item.chose"></p>
		                </div>
		            </label>
		            <input type="hidden" class="vals" data-title="" data-val="" data-biao="" />
		        </div>
           	</div>
	        <div class="BTN" @click="tijiaoFn($event)">立即提交</div>
			<div v-show="isshow" class="meng"></div>
			<div v-show="isshow" class="notice" v-text="notice"></div>
		</section>
	</body>
</html>
<script>
var app = new Vue({
		el:'#app',
		data:{
			isshow:false,    //是否显示蒙层
			choseVal:[],
			notice:'',
			pingjiaList:[
				{
					title:'1.您对本次服务的总体评价是？',
					names:'radio1',
					answer:[
						{ 	biao:'A',
							chose:'A.非常满意'
						},
						{	biao:'B',
							chose:'B.满意'
						},
						{	biao:'c',
							chose:'C.比较满意'
						},
						{	biao:'D',
							chose:'D.不满意'
						}
					]
				},
				{
					title:'2.您对本次服务及时性的满意程度是？',
					names:'radio2',
					answer:[
						{ 	biao:'A',
							chose:'A.非常满意，立即进行了处理'
						},
						{ 	biao:'B',
							chose:'B.满意，很快进行了处理'
						},
						{ 	biao:'C',
							chose:'C.比较满意，处理速度还可以'
						},
						{ 	biao:'D',
							chose:'D.不满意，很久了都没人处理'
						},
					]
				},
				{
					title:'3.您对本次服务态度的印象是？',
					names:'radio3',
					answer:[
						{ 	biao:'A',
							chose:'A.非常好，服务耐心，解答疑问效果好'
						},
						{ 	biao:'B',
							chose:'B.满意，服务过程细致'
						},
						{ 	biao:'C',
							chose:'C.比较满意，服务时态度友好'
						},
						{ 	biao:'D',
							chose:'D.不满意，服务时不好沟通，态度恶劣'
						},
					]
				},
				{
					title:'4.您对本次技术人员的专业性评价',
					names:'radio4',
					answer:[
						{ 	biao:'A',
							chose:'A.技术水平很高，能解决遇到的所有问题'
						},
						{ 	biao:'B',
							chose:'B.技术水平好，能解决遇到的大部分问题'
						},
						{ 	biao:'C',
							chose:'C.技术水平待加强，能解决遇到的简单问题'
						},
						{ 	biao:'D',
							chose:'D.技术水平较差，不能解决问题'
						},
					]
				}
			]
		},
		methods:{
			setTime:function(){
				var self=this;
				setTimeout(function() {
					self.isshow=false;
				}, 2000)
			},
			choseFn:function(evt,title,biao,val){
				var self=this;
				$(evt.target).parents('.testBox').find(".vals").attr('data-title',title);
				$(evt.target).parents('.testBox').find(".vals").attr('data-biao',biao);
				$(evt.target).parents('.testBox').find(".vals").attr('data-val',val);
			},
			//点击提交
			tijiaoFn:function(evt){
				var self=this;
				self.choseVal=[];
				var titles='',biaos='',vals='',type=1;
				$('.vals').each(function(){
					if($(this).attr('data-title').length<1){   //没有选择数据
						type=0;
					}
					titles=$(this).attr('data-title');
					biaos=$(this).attr('data-biao');
					vals=$(this).attr('data-val');
					self.choseVal.push({
						"title":titles,
						"biao":biaos,
						"val":vals
					});
				});
				if(type==0){
					self.notice="您还未完成评价~";
					self.isshow=true;
					self.setTime();
					return
				}
				//ajax
                $.ajax({
                    url: "<?php echo U('Portal/Index/save_evaluate');?>",
                    type: 'POST',
                    data: {data:self.choseVal,id:'<?php echo ($id); ?>'},
                    dataType:"json",
                    success:function (res) {
                        if (res.status == 1) {
                            self.notice="评价完成，感谢您的支持~";
                            self.isshow=true;
                            $('body').addClass('overflow');
							setInterval(function () {
                                location.href = '<?php echo U("Index/service");?>';
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

</script>