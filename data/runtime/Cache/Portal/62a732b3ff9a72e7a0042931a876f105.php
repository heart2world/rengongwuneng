<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
	    <meta name="viewport" content="maximum-scale=1.0,minimum-scale=1.0,user-scalable=0,width=device-width,initial-scale=1.0"/>
	    <meta name="format-detection" content="telephone=no,email=no,date=no,address=no">
		<title>选择报修项目</title>
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
						<input class="disflex searchkey" v-model="keywords" type="text" placeholder="请输项目名称" />
						<div v-show="isshow" class="clearBtn" @click="delFn($event)"></div>
					</div>
				</div>
				<div class="sousuo" @click="searchFn($event)">搜索</div>
			</div>
			<div class="weui-cells">
	            <a v-for="prop in list" class="weui-cell weui-cell_access" :href="prop.url">
	                <div class="weui-cell__bd baoxiu">
	                    <p v-text="prop.projectName"></p>
	                    <p v-text="prop.address"></p>
	                </div>
	            </a>
	        </div>
	        <div v-show="list.length<1" class="nothing" v-text="notice"></div>
		</section>
	</body>
</html>
<script>

var app = new Vue({
		el:'#app',
		data:{
			list:[
			],
			keywords:'',   //搜索关键字
			isshow:false,    //是否显示清除按钮
			notice:'请输项目名称进行项目搜索选择~'
		},
		methods:{
			//删除按钮显示
			delFn:function(evt){
				var self=this;
				self.keywords='';
				self.isshow=false;
			},
			searchFn:function(evt){
				var self=this;
				self.list=[];
				//ajax
                if (self.keywords != '') {
                    $.ajax({
                        url: "<?php echo U('Portal/Index/getProject');?>",
                        type: 'POST',
                        data: {keyword:self.keywords},
                        dataType:"json",
                        success:function (res) {
                            if (res.status == 1) {
                                self.list = res.data.data.records;
								if(self.list.length<1){
									self.notice="非常抱歉，暂无该项目~";
								}
                            }
                        }
                    });
                }
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

	function jump(project_name,address) {
		location.href = "<?php echo U('Portal/Index/index');?>"+"&project_name="+project_name+"&address="+address;
    }
</script>