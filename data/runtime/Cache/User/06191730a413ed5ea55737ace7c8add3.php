<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
	    <meta name="viewport" content="maximum-scale=1.0,minimum-scale=1.0,user-scalable=0,width=device-width,initial-scale=1.0"/>
	    <meta name="format-detection" content="telephone=no,email=no,date=no,address=no">
		<title>我的任务</title>
	    <link rel="stylesheet" href="/public/wx/lib/weui/jquery-weui.css" />
	    <link rel="stylesheet" href="/public/wx/lib/weui/weui.min.css" />
	    <link rel="stylesheet" href="/public/wx/lib/swiper/swiper.min.css" />
	    <link rel="stylesheet" href="/public/wx/css/public.css" />
	    <link rel="stylesheet" href="/public/wx/css/style.css" />
	    <script type="text/javascript" src="/public/wx/lib/jq/jquery-1.10.2.js" ></script>
	    <script type="text/javascript" src="/public/wx/lib/weui/jquery-weui.js" ></script>
	    <script type="text/javascript" src="/public/wx/js/v.min.js" ></script>
	    <script type="text/javascript" src="/public/wx/js/common.js" ></script>
	</head>
	<style>
		.choseTime{ background:none; padding-left: 15px;}
		.choseType{ border-left: 0; border-right: 1px solid #d9d9d9; background-position: 85% center;padding: 0 30px 0 0;}
		.weui-cells:before{ display: none;}
		.choseType .choseState{ top: 2rem; left: -15px;}
	</style>
	<body>
		<section class="mainSec pb3rem" id="app">
			<div class="choseTime disbox searchBox">
				<div class="choseType" style="height:100%;">
					<p @click="choseState($event)" style="min-width:3rem" v-text="zhuangtai"></p>
					<div v-show="isshow" class="choseState">
						<div @click="chose($event,4)">已接收</div>
						<div @click="chose($event,5)">已处理</div>
						<div @click="chose($event,6)">已评价</div>
					</div>
				</div>
				<div class="disflex">
					<div class="search disbox" style="height:100%;line-height:auto">
						<input class="disflex searchkey" v-model="keywords" type="text" placeholder="请输入项目名称" style="line-height:1.4rem;height:1.4rem" />
						<div v-show="clearbtn" class="clearBtn" @click="delFn($event)"></div>
					</div>
				</div>
				<div class="sousuo" @click="searchFn($event)">搜索</div>
			</div>
			<div class="weui-cells wdbxList">
	            <a :href="prop.url" class="weui-cell" v-for="prop in list">
	                <div class="weui-cell__bd" style="width: 1%">
	                    <p class="title" v-text="prop.fault_type"></p>
	                    <p class="ellipsis" v-text="prop.project_name"></p>
	                    <p v-text="prop.create_time"></p>
	                </div>
	                <div class="weui-cell__ft stateText" :class="prop.state==4?'yjs':(prop.state==5?'ycl':'ypj')" v-text="prop.state==4?'已接收':(prop.state==5?'已处理':'已评价')"></div>
	            </a>
	        </div>
			<div v-show="isshow" class="meng" @click="isshow=false"></div>
		</section>
		<footer class="mainFooter">
			<a class="djrw" href="<?php echo U('index');?>">待接任务</a>
			<a class="wdrw active">我的任务</a>
		</footer>
	</body>
</html>
<script>
var app = new Vue({
	el:'#app',
	data:{
		keywords:'',
		zhuangtai:'状态',
		isshow:false,//是否显示状态蒙层
		types:0,
		clearbtn:false,   //删除图标
		list:[]
	},
    mounted:function(){
        this.getList();
    },
	methods:{
        getList : function(){
            var self=this;
			$.showLoading("加载中");
            $.ajax({
                url: "<?php echo U('User/Center/getRecord');?>",
                type: 'POST',
                dataType:"json",
                success:function (res) {
					$.hideLoading();
                    if (res.status == 1) {
                        self.list = res.data;
                    }
                }
            });
        },
		//删除关键字
		delFn:function(){
			var self=this;
			self.keywords='';
		},
		//显示状态
		choseState:function(evt){
			var self=this;
			self.isshow=true;
		},
		//选择状态
		chose:function(evt,type){
			var self=this;
			self.zhuangtai=$(evt.target).text();
            self.types=type;
			this.isshow=false;
		},
        searchFn:function(evt){
            var self=this;
            //ajax
            $.ajax({
                url: "<?php echo U('User/Center/getRecord');?>",
                type: 'POST',
                data: {'project_name':self.keywords,'state':self.types},
                dataType:"json",
                success:function (res) {
                    if (res.status == 1) {
                        self.list = res.data;
                    }
                }
            });
        }
	},
	watch : {
        keywords:function(val) {
        	var self=this;
        	if(val.length>0){
				self.clearbtn=true;
			}
			else{
				self.clearbtn=false;
			}
        }
    }
});
	
</script>