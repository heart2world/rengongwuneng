<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="maximum-scale=1.0,minimum-scale=1.0,user-scalable=0,width=device-width,initial-scale=1.0"/>
	<meta name="format-detection" content="telephone=no,email=no,date=no,address=no">
	<title>待接任务</title>
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
	.weui-cells:before{ display: none;}
</style>
<body>
<section class="mainSec pb3rem" id="app">
	<div class="searchBox disbox">
		<div class="disflex">
			<div class="search disbox">
				<input class="disflex searchkey" v-model="keywords" type="text" placeholder="请输入项目名称" />
				<div v-show="isshow" class="clearBtn" @click="delFn($event)"></div>
			</div>
		</div>
		<div class="sousuo" @click="searchFn($event)">搜索</div>
	</div>
	<div class="weui-cells">
		<a v-for="prop in list" class="weui-cell" :href="prop.url">
			<div class="weui-cell__bd baoxiu" style="width: 1%">
				<p style="align-items:center;display:flex">
					<span v-text="prop.fault_type"></span>
					<span :class="prop.emergency_degree==1?'zc':(prop.emergency_degree==2?'jj':'fcjj')" class="stateBg" v-text="prop.description"></span>
				</p>
				<p v-text="prop.project_name" class="ellipsis"></p>
				<p v-text="prop.create_time"></p>
			</div>
			<div class="weui-cell__ft djsText">待接收</div>
		</a>
	</div>
	<div class="nothing" v-text="notice"></div>
</section>
<footer class="mainFooter">
	<a class="djrw active">待接任务</a>
	<a class="wdrw" href="<?php echo U('Center/work');?>">我的任务</a>
</footer>
</body>
</html>
<script>
    var app = new Vue({
        el:'#app',
        data:{
            list:[],
            keywords:'',   //搜索关键字
			notice:'',
            isshow:false,    //是否显示清除按钮
        },
        mounted:function(){
            this.getList();
        },
        methods:{
            getList : function(){
                var self=this;
				$.showLoading('加载中');
                $.ajax({
                    url: "<?php echo U('User/Center/getTasks');?>",
                    type: 'POST',
                    dataType:"json",
                    success:function (res) {
						$.hideLoading();
                        if (res.status == 1) {
                            self.list = res.data;
							if(self.list.length<1){
								self.notice="暂无待接任务";
							}
                        }
                    }
                });
            },
            //删除按钮显示
            delFn:function(evt){
                var self=this;
                self.keywords='';
                self.isshow=false;
            },
            searchFn:function(evt){
                var self=this;
                //ajax
                $.ajax({
                    url: "<?php echo U('User/Center/getTasks');?>",
                    type: 'POST',
					data: {'project_name':self.keywords},
                    dataType:"json",
                    success:function (res) {
                        if (res.status == 1) {
                            self.list = res.data;
							if(self.list.length<1){
								self.notice='非常抱歉，暂无该项目~';
							}
                        }
                    }
                });
            }
        },
        watch : {
            keywords:function(val) {
                var self=this;
                if(val.length>0){
                    self.isshow=true;
                }
                else{
                    self.isshow=false;
                }
            }
        }
    })
</script>