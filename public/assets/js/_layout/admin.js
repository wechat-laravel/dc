var admin = avalon.define({
    $id         : 'admin',
    one         : '',
    two         : '',
    three       : ''
});

$('.btn-warning.btn-lg.mobile').popover();

var pathname = window.location.pathname;
var path = pathname.split('/');
path[1] ? admin.one   =  path[1] : '';
path[2] ? admin.two   =  path[2] : '';
path[3] ? admin.three =  admin.two+'_'+ path[3] : '';


history.pushState(null, null, document.URL);
window.addEventListener('popstate', function () {
    history.pushState(null, null, document.URL);
});

//半个小时执行一次
function check() {

    var now = new Date();
    var minutes = now.getMinutes();
    if(minutes == 30 || minutes === 0){
        $('.modal.fade.overdue').modal('show');
    }
}
//读取cookie
function getCookie(name)
{
    var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
    if(arr=document.cookie.match(reg))
        return arr[2];
    else
        return null;
}
//time单位为分
function setCookie(name,value,time)
{
    var exdate=new Date();
    exdate.setMinutes(exdate.getMinutes()+time);
    document.cookie=name+ "=" +value+
        ((time==null) ? "" : ";expires="+exdate.toGMTString())
}

setInterval(check,60000);

//上来。如果没有cookie 执行一次。
if(!getCookie('check')){
    $('.modal.fade.overdue').modal('show');
    setCookie('check','www.maidamaida.com',180);
}

