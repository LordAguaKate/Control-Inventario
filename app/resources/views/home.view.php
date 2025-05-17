<?php
    include_once LAYOUTS . 'main_head.php';

    setHeader($d);
   
?>

<div class="row mx-auto">
    <div class="col-2">
        <div id="prev-posts" class="list-group small-font">
            <!-- Publicaciones anteriores -->
        </div>
    </div>
    <div class="col-8">
        <div id="content" class="content">
            <!-- Última publicación / publicación seleccionada -->
        </div>
    </div>
    <div class="col">
        <div id="dates" class="list-group">
            <!-- Calendario de publicaciones -->
        </div>
    </div>
</div>

<?php
    include_once LAYOUTS . 'main_foot.php';

    setFooter($d);

?>
    <script>//Script de la vusta Home
        $( function (){
            app.previousPosts()
            app.lastPost()
        })
    </script>

<?php

    closefooter();
