function isWechat() {
    var ua = navigator.userAgent.toLowerCase();

    if(ua.match(/MicroMessenger/i)=="micromessenger") {
        return true;
    }else{
        return false;
    }
}

if(isWechat()){
    $('.downBg').show();
}else{
    $('.downBg').hide();
}

var hostUrl = window.location.protocol + '//' + window.location.host + '/';

hostUrl = hostUrl.replace('wap/','');

var url = hostUrl+"index.php?con=api&act=index";


$.get(url,{method:'siteconf',sign:'U/QXGNGVqs4kz6WJcSQjR49N0OTJ/1x9'},function(data){

    if(data['status']=='succ'){
        var d = data['data'];
        $('#logo_img').attr('src',d.site_logo);
        $('.downTitle').text(d.site_name);
        $('.downTitle2').text(d.site_name+' App下载');

        var app_info = d.app_info;

        if(!app_info.ios_download_url){

        }else{
            $('#ios_download_url').show();
            $('#ios_down').attr('href',app_info.ios_download_url);
        }

        if(!app_info.android_download_url){

        }else{
            $('#android_download_url').show();
            $('#android_down').attr('href',app_info.android_download_url);
        }

    }

},'json');