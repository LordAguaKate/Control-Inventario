<?php
?>
<footer class="bg-light text-center text-lg-start mt-5">
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
        © 2025 Copyright:
        <a class="text-dark" href="https://ejercicio4e.com">Ejercicio4E.com</a>
    </div>
</footer>

<!-- jQuery primero, luego Popper.js, luego Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Script para inicializar tooltips y popovers -->
<script>
// Inicializar tooltips
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
});

// Inicializar popovers
var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
    return new bootstrap.Popover(popoverTriggerEl);
});

// Inicializar modales
var myModal = document.getElementById('myModal');
var myInput = document.getElementById('myInput');

if (myModal) {
    myModal.addEventListener('shown.bs.modal', function () {
        myInput.focus();
    });
}
</script>
</body>
</html>
