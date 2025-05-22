<?php
function setFooter($args = []) {  // Asegurar parámetro por defecto
    // Convertir $args a array si es objeto
    $args = is_object($args) ? (array)$args : $args;
    
    // Extraer y asegurar $ua como objeto estándar
    $ua = isset($args['ua']) ? (object)(is_array($args['ua']) ? $args['ua'] : (array)$args['ua']) : (object)[
        'sv' => false,
        'id' => '',
        'username' => '',
        'tipo' => ''
    ];
?>
    <script src="<?=JS?>jquery.js"></script>
    <script src="<?=JS?>bootstrap.js"></script>
    <script src="<?=JS?>sweetalert2.js"></script>
    <script src="<?=JS?>app.js"></script>

    <script>//Script de main_foot
        $(function(){
            <?php
            // Verificación más robusta
            $footer_ua = $args['ua'] ?? null;
            $ua = is_object($footer_ua) ? $footer_ua : (object)[
                'sv' => false,
                'id' => '',
                'username' => '',
                'tipo' => ''
            ];
            ?>
            app.user.sv = <?= json_encode($ua->sv) ?>;
            app.user.id = <?= json_encode($ua->id ?? '') ?>;
            app.user.username = <?= json_encode($ua->username ?? '') ?>;
            app.user.tipo = <?= json_encode($ua->tipo ?? '') ?>;
        })
    </script>
<?php
    }
    function closeFooter(){?>
        </body>
        </html>
    <?php }
    