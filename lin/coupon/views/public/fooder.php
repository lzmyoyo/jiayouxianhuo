
	<?php foreach ($style['js'] as $jsInfo) { ?>
    <script type="text/javascript" src="<?php echo  $jsInfo; ?>"></script>
	<?php } ?>
    <script type="text/javascript" src="/Public/Js/clipboard.min.js"></script>

    <script>
        $(document).ready(function(){


            $(".js-offer-item-details-link").click(function(){
                var detailId = $(this).data('id');
                $('#details-' + detailId).toggle(500);
            });

            $('.js-input-toggle-label').click(function(){
                var dataUrl = $(this).data('url');
                window.location.href = dataUrl;
            });


            $('.js-close').click(function(){
                $('#modal').addClass('hidden');
                $('#modalbg').addClass('hidden');
            });


            var clipboard = new Clipboard('.js-copy');

            clipboard.on('success', function(e) {
                $('.js-copy').text('Copy Success');
            });

            clipboard.on('error', function(e) {
                $('.js-copy').text('Copy Error').css({'background':'#ee9026'});

            });


        });


        function copyToClipboard(maintext){
            if (window.clipboardData){
                window.clipboardData.setData("Text", maintext);
            }else if (window.netscape){
                try{
                    netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
                }catch(e){
                    alert("该浏览器不支持一键复制！n请手工复制文本框链接地址～");
                }
                var clip = Components.classes['@mozilla.org/widget/clipboard;1'].createInstance(Components.interfaces.nsIClipboard);
                if (!clip) return;
                var trans = Components.classes['@mozilla.org/widget/transferable;1'].createInstance(Components.interfaces.nsITransferable);
                if (!trans) return;
                trans.addDataFlavor('text/unicode');
                var str = new Object();
                var len = new Object();
                var str = Components.classes["@mozilla.org/supports-string;1"].createInstance(Components.interfaces.nsISupportsString);
                var copytext=maintext;
                str.data=copytext;
                trans.setTransferData("text/unicode",str,copytext.length*2);
                var clipid=Components.interfaces.nsIClipboard;
                if (!clip) return false;
                clip.setData(trans,null,clipid.kGlobalClipboard);
            }
            alert('222');
        }

    </script>



    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-109698493-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-109698493-1');
    </script>
    </body>
</html>