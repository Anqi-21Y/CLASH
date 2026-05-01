<!-- footer -->
<footer>
    <div class="footer-big">CLASH</div>

    <div class="footer-bottom">
        <span>© Clash 2026</span>
        <span class="footer-sep">|</span>
        <span>Anqi Yang & Jhoana Martínez</span>
        <span class="footer-sep">|</span>
        <span>Proyecto final FP - Desarrollo de Aplicaiones Web</span>
    </div>
</footer>

<!-- js principal — siempre se carga -->
<script src="/CLASH/assets/js/main.js"></script>

<?php if (isset($js_pagina)): ?>
    <?php foreach ((array)$js_pagina as $js): ?>
        <script src="/CLASH/assets/js/<?= htmlspecialchars($js) ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>

</body>
</html>