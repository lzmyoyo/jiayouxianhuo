<!-- </div> -->

<script>

    //tap为mui封装的单击事件，可参考手势事件章节
    var addWorkItem = document.getElementsByClassName('add-workItem');
    for(var i=0;i<addWorkItem.length;i++){
        addWorkItem[i].addEventListener('tap',function () {
            mui.openWindow({
                url: '/mobile/index/add',
                id:'info'
            });
        });
    }


    //tap为mui封装的单击事件，可参考手势事件章节
    var addWorkItem = document.getElementsByClassName('all-cart');
    for(var i=0;i<addWorkItem.length;i++){
        addWorkItem[i].addEventListener('tap',function () {
            mui.openWindow({
                url: '/mobile/index/cart',
                id:'cart'
            });
        });
    }

    //tap为mui封装的单击事件，可参考手势事件章节
    var jWorkItemListItem = document.getElementsByClassName('j-work-list');
    for(var i=0;i<jWorkItemListItem.length;i++){
        jWorkItemListItem[i].addEventListener('tap',function () {
            mui.openWindow({
                url: '/mobile',
                id:'info'
            });
        });
    }

    //tap为mui封装的单击事件，可参考手势事件章节
    var jUserMe = document.getElementsByClassName('j-user-me');
    for(var i=0;i<jUserMe.length;i++){
        jUserMe[i].addEventListener('tap',function () {
            mui.openWindow({
                url: '/mobile/user',
                id:'info'
            });
        });
    }
</script>
<script>
    var _hmt = _hmt || [];
    (function() {
        var hm = document.createElement("script");
        hm.src = "https://hm.baidu.com/hm.js?4b7343fd2ad34040b92c443b642c6a40";
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(hm, s);
    })();
</script>

</body>
</html>