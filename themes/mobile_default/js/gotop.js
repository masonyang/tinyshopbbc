function a(x,y){
    $('#tbox').css('right',x + 'px');
    $('#tbox').css('bottom',y + 'px');
}
function b(){
    h = $(window).height();
    t = $(document).scrollTop()+300;
    if(t > h){
        $('#gotop').fadeIn('slow');
    }else{
        $('#gotop').fadeOut(100);
    }
}
$(document).ready(function(e) {
    a(10,70);//#tbox的div距浏览器底部和页面内容区域右侧的距离
    b();
    $('#gotop').click(function(){
        $(document).scrollTop(0);
    })

});
$(window).resize(function(){

});

$(window).scroll(function(e){
    b();
})
