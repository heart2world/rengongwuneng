
/**设置图标层的高度及添加图片层的背景图**/
/**
 obj  需设置图片层的class
 h    需设置图片层高的比例(比例的计算方式为UI图片的 高度/宽度)
 **/
function bgImg(obj, h) {
    var width = $('.' + obj).width();
    $('.' + obj).height(width * h);
    $('.' + obj).each(function() {
        var src = $(this).find('img').attr('src');
        $(this).css('background', 'url(' + src + ') center no-repeat');
        $(this).css('background-size', 'cover');
    });
}
function bgImg2(obj, h) {
    var width = $('.' + obj).width();
    $('.' + obj).height(width * h);
    $('.' + obj).each(function() {
        var src = $(this).find('img').attr('src');
        $(this).css('background', 'url(' + src + ') center no-repeat');
        $(this).css('background-size', 'contain');
    });
}

//获取form表单的值
function getFormVal(obj) {
    var a = $('#' + obj).serializeArray();
    var valus = {};
    $.each(a, function(i, n) {
        valus[n.name] = n.value;
    })
    return valus;
}

//返回上一页
function back(load){
	if(load==1){   //返回并刷新
//		window.location.go(-1);
		window.history.go(-1);
	}
	else{
		window.history.go(-1);
	}
}

//添加提示
function message(texts){
	$('body').append('<div class="message"><div class="messageText"><p>'+texts+'</p></div></div>');
	setTimeout(function() {
		$('.message').remove();
	}, 1000);
}

//获取验证码
var bj = 0;
var countdown = 60;
function settime(val) {
	if(countdown == 0) {
		$('.getCode').text('获取验证码').addClass('on');
		$('.getCode').removeClass("active");
		countdown = 60;
		bj = 0;
		return;
	} else {
		$('.getCode').text(countdown + 's后获取');
		$('.getCode').addClass("active");
		countdown--;
	}
	setTimeout(function() {
		settime(val)
	}, 1000)
}
function getCode(obj,phone){
	console.log(phone);
	if($(obj).hasClass("on")){
        var phone=$('#telInput').val();
        settime(countdown);
		$(obj).removeClass('on').addClass('active');
    }
}

$(function(){
	
	//监听输入框变化事件
	$('.inputBox').bind('input propertychange', function() { 
	    if($(this).val().length<1){
			$('.cancel').hide();
		}
		else{
			$('.cancel').show();
		}
	});  
	$('.cancel').click(function(){
		$('.inputBox').val('').focus();
		$(this).hide();
	})
	
	//点击头部返回
	$('.back').click(function(){
		back();
	})
})
