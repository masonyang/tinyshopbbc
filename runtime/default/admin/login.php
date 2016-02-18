<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7, IE=9" />
<style>
    body{background:#3f4657;border:0;margin:0; position: relative;font-size:16px;}
    #login{background:#F1F1F1;height:400px;margin-top:80px;color:#FFF;}
    #login ul li{line-height:30px;margin-top:10px;list-style: none;}
    #login ul li input{line-height:24px;height:24px;width:200px;}
    input,img,label{vertical-align:middle;}
    label.caption{width:100px;display:inline-block;text-align:right;}
    .message{margin-top:20px;color:red;height:20px;}
    a{color:#FFF;text-decoration:none;}
    
    .button{
        background-image: -webkit-gradient(linear,left top,left bottom,from(#f5f5f5),to(#f1f1f1));
        background-image: -webkit-linear-gradient(top,#f5f5f5,#f1f1f1);
        -webkit-border-radius: 2px;
        -webkit-user-select: none;
        background-color: #f5f5f5;
        background-image: linear-gradient(top,#f5f5f5,#f1f1f1);
        background-image: -o-linear-gradient(top,#f5f5f5,#f1f1f1);
        border: 1px solid #dcdcdc;
        border: 1px solid rgba(0, 0, 0, 0.1);
        border-radius: 2px;
        color: #666;
        font-size: 12px;
        font-weight: bold;
        height: 29px;
        line-height: 27px;
        margin: 11px 6px;
        min-width: 54px;
        padding: 0 8px;
        text-align: center;
        cursor:pointer;
}
    .button:hover{
    background-image: -webkit-gradient(linear,left top,left bottom,from(#f8f8f8),to(#f1f1f1));
    background-image: -webkit-linear-gradient(top,#f8f8f8,#f1f1f1);
    -webkit-box-shadow: 0 1px 1px rgba(0,0,0,0.1);
    background-color: #f8f8f8;
    background-image: linear-gradient(top,#f8f8f8,#f1f1f1);
    background-image: -o-linear-gradient(top,#f8f8f8,#f1f1f1);
    border: 1px solid #c6c6c6;
    box-shadow: 0 1px 1px rgba(0,0,0,0.1);
    color: #333;
    }
</style>
<title></title>
<meta name="Keywords" content="" />
<meta name="Description" content="" />
</head>
<body>

<div id="login" style="padding-top:100px;">
	<div style="text-align:center;margin: 0 auto;"><img src="<?php echo urldecode(Url::urlFormat("@static/images/logo_min.png"));?>"></div>
    <div style="border:10px solid #8f96A7; background:#3f4657; color:#FFF;height:240px; width:460px;text-align:center;margin: 0 auto;">
    <div  class="message"><?php echo isset($msg)?$msg:"";?></div>
    <form action="<?php echo urldecode(Url::urlFormat("/admin/check"));?>" method="post">
        <ul style="text-align:left">
            <li><label class='caption'>用户名：</label><input name="name" /></li>
            <li><label class='caption'>密&nbsp;&nbsp;码：</label><input type="password" name="password" /></li>
            <li><label class='caption'>验证码：</label><input name="verifyCode"  style="width:100px;"/><img id="captcha_img" src='<?php echo urldecode(Url::urlFormat("/admin/captcha/h/40/w/120/bc/3f4657/c/ffffff"));?>'/><label><a href="javascript:void(0)" class="red" onclick="document.getElementById('captcha_img').src='<?php echo urldecode(Url::urlFormat("/simple/captcha/h/40/w/120/bc/3f4657/c/ffffff/random/"));?>'+Math.random()">换一张</a></label></li>
            <li style="text-align:right;margin-right:4em;"><input class='button' type="submit" style="width:auto;" value='登 录'/> <input type="reset" class='button'  style="width:auto;" value='重 置'/></li>
        </ul>
        
    </form>
    </div>
</div>
<script type="text/javascript">
    if(top!=self){
        if(top.location!=self.location) top.location = self.location;
    }
</script>
</body>
</html>
<!--Powered by TinyRise-->