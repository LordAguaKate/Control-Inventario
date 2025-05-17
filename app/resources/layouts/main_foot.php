<?php

    function setFooter($args){
        /** */
?>
    <script src="<?=JS?>jquery.js"></script>
    <script src="<?=JS?>bootstrap.js"></script>
    <script src="<?=JS?>sweetalert2.js"></script>
    <script src="<?=JS?>app.js"></script>

    <script>//Script de main_foot
        $( function(){
            app.user.sv = <?=$ua->sv?'true' : 'false'?>;
            app.user.id = "<?=$ua->id??''?>"
            app.user.username = "<?=$ua->username??''?>"
            app.user.tipo = "<?=$ua->tipo??''?>"
        })
    </script>
<?php
    }
    function closeFooter(){?>
        </body>
        </html>
    <?php }