document.write("<div id='ThinkAjaxResult' class='ThinkAjax'  style='display:none;'><div class='ThinkMsg'><span></span></div></div>");

function msg(msg) {
    if(!msg) {
        return false;
    }
    $('#ThinkAjaxResult > .ThinkMsg > span').text(msg);
    $('#ThinkAjaxResult').css({'display':'block', 'visibility':'visible', 'opacity':'0.8'});

    setTimeout("showHide()", 3000);
}

function showHide() {
    $('#ThinkAjaxResult').css({'display':'none', 'visibility':'hidden', 'opacity':'0'});
}