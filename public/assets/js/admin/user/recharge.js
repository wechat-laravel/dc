var show = avalon.define({
    $id      : "show",
    money    : 0,
    onMoney    : function (e) {
        show.money = parseInt(e);
    },
    onRecharge : function() {
        if(!show.money){
            alert('请输入充值金额！');
        }else{

            $('#myModal').modal('show');
        }
    }
});