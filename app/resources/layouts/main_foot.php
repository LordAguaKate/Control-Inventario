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
    <!-- Modal de Éxito -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">¡Éxito!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <p id="successMessage"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

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

            // Mostrar modal de éxito si hay mensaje
            <?php if (isset($_SESSION['success'])): ?>
                $('#successMessage').text('<?= addslashes($_SESSION['success']) ?>');
                new bootstrap.Modal(document.getElementById('successModal')).show();
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
        })
    </script>
<?php
    }
    function closeFooter(){?>
        </body>
        </html>
    <?php }
    