var red_bag = avalon.define({
    $id:'red_bag',
    url:'/admin/service/red_bag',
    data:[],
    getData:function(){
        $.ajax({
            url:red_bag.url,
            success:function(data){
                red_bag.data=data.data;
            }
        })
    },
    addConfig:function(){
        alert(123);
    }
});