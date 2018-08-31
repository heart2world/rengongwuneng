<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
	    <meta name="viewport" content="maximum-scale=1.0,minimum-scale=1.0,user-scalable=0,width=device-width,initial-scale=1.0"/>
	    <meta name="format-detection" content="telephone=no,email=no,date=no,address=no">
		<title>注册</title>
	    <link rel="stylesheet" href="/public/wx/lib/weui/jquery-weui.css" />
	    <link rel="stylesheet" href="/public/wx/lib/weui/weui.min.css" />
	    <link rel="stylesheet" href="/public/wx/lib/swiper/swiper.min.css" />
	    <link rel="stylesheet" href="/public/wx/css/public.css" />
	    <link rel="stylesheet" href="/public/wx/css/style.css" />
	    <script type="text/javascript" src="/public/wx/lib/jq/jquery-3.2.0.min.js" ></script>
	    <script type="text/javascript" src="/public/wx/lib/weui/jquery-weui.js" ></script>
	    <script type="text/javascript" src="/public/wx/js/v.min.js" ></script>
	    <script type="text/javascript" src="http://jqweui.com/dist/js/city-picker.js" ></script>
	    <script type="text/javascript" src="http://jqweui.com/dist/js/swiper.js" ></script>
	    <script type="text/javascript" src="/public/wx/js/common.js" ></script>
	</head>
	<style>
		.weui-cells, .weui-vcode-btn{ font-size: .95rem;}
		body .toolbar .picker-button{ color: #0a72e5;}
	</style>
	<body>
		<section class="mainSec pb3rem" id="app">
			<div class="weui-cells weui-cells_form">
	            <div class="weui-cell">
	                <div class="weui-cell__hd"><label class="weui-label">售后人员</label></div>
	                <div class="weui-cell__bd">
	                    <input class="weui-input" type="text" v-model="names" placeholder="请输入姓名">
	                </div>
	            </div>
	            <div class="weui-cell">
	                <div class="weui-cell__hd"><label class="weui-label">联系电话</label></div>
	                <div class="weui-cell__bd">
	                    <input class="weui-input" type="number" pattern="[0-9]*" v-model="mobile" placeholder="请输入联系电话">
	                </div>
	            </div>
	            <div class="weui-cell weui-cell_vcode cityBox" onclick="movefocus()">
	                <div class="weui-cell__hd">
	                    <label class="weui-label quyu">负责区域</label>
	                </div>
	                <div class="weui-cell__bd cityjt">
	                    <input class="weui-input ellipsis area" type="text" placeholder="请选择负责区域" id="showCityPicker1">
	                </div>
	                <div class="weui-cell__ft">
	                    <button class="weui-vcode-btn iconBtn addBtn" onclick="btnFn(this)"></button>
	                </div>
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
		names:'',    //姓名
		mobile:'',    //电话
		idcount:1,
		notice:'',      //提示语
		areas:[],    //选择的区域
		isshow:false
	},
	methods:{
		setTime:function(){
			var self=this;
			setTimeout(function() {
				self.isshow=false;
			}, 2000)
		},
		//点击提交
		tijiaoFn:function(evt){
			var self=this;
			var areas='',type=1;
			self.areas=[];
			if(self.names.length<1){
				self.notice="姓名未填写，请填写~";
				self.isshow=true;
				self.setTime();
				return
			}
			if(self.mobile.length!=11){
				self.notice="联系电话错误，请重新填写~";
				self.isshow=true;
				self.setTime();
				return
			}
			$('.area').each(function(){
				if($(this).val().length<1){
					type=0;
					return
				}
				self.areas.push({
					"areas":$(this).val()
				})
			})
			if(type==0){
				self.notice="区域未填写，请填写";
				self.isshow=true;
				self.setTime();
				return
			}
			//ajax
            $.ajax({
                url: "<?php echo U('User/Register/save_info');?>",
                type: 'POST',
                data: {user_name:self.names,mobile:self.mobile,area:self.areas,openid:'<?php echo ($info["openid"]); ?>',avatar:'<?php echo ($info["headimgurl"]); ?>'},
                dataType:"json",
                success:function (res) {
                    if (res.status == 1) {
                        self.notice="恭喜你，注册成功~";
                        self.isshow=true;
                        $('body').addClass('overflow');
                        setTimeout(function () {
                            location.href = '<?php echo U("User/Center/index");?>';
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

//点击添加或删除按钮
btnFn=function(obj){
	if($(obj).hasClass('addBtn')){    //点击的是添加按钮
		app.idcount++;
		var len=$('.cityBox').length;
		$('.cityBox').each(function(){
			$(this).find('.addBtn').removeClass('addBtn').addClass('delBtn');
		})
		var ids="showCityPicker"+app.idcount;
		$(obj).parents('.cityBox').after('<div class="weui-cell weui-cell_vcode cityBox" onclick="movefocus()">'+
            '<div class="weui-cell__hd">'+
                '<label class="weui-label quyu">负责区域<span>'+(len+1)+'<span></label>'+
            '</div>'+
            '<div class="weui-cell__bd cityjt">'+
                '<input class="weui-input ellipsis area" type="text" placeholder="请选择负责区域" id="'+ids+'">'+
            '</div>'+
            '<div class="weui-cell__ft">'+
                '<button class="weui-vcode-btn iconBtn addBtn" onclick="btnFn(this)"></button>'+
            '</div>'+
        '</div>');
	}
	else{    //点击的是删除按钮
		var len=$('.cityBox').length-1;
		var list=$(obj).parents('.cityBox').nextAll('.cityBox');
		list.each(function(){
			var num=parseInt($(this).find('.quyu span').text())-1;
			$(this).find('.quyu span').text(num);
		})
		$(obj).parents('.cityBox').remove();
	}
	cityFn();
};

movefocus=function(){
	$('input').blur();
}

cityFn=function(){
	$('.area').each(function(){
		var ids="#"+$(this).attr('id');
		console.log(ids);
		$(ids).cityPicker({
			title:"请选择所在负责区域"
		});
	})
}

$(function(){
	cityFn();
})

</script>