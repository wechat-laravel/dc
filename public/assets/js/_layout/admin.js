var admin = avalon.define({
    $id : 'admin',
    one         : '',
    two         : '',
    three       : ''
});

var pathname = window.location.pathname;
var path = pathname.split('/');
path[1] ? admin.one   =  path[1] : '';
path[2] ? admin.two   =  path[2] : '';
path[3] ? admin.three =  admin.two+'_'+ path[3] : '';
