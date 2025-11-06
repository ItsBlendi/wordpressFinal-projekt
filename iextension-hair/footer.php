</main>
<footer class="container footer">
<div class="grid">
<div>
<strong><?php bloginfo('name'); ?></strong>
<p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?> — All rights reserved.</p>
</div>
<div>
<?php if(is_active_sidebar('sidebar-1')) dynamic_sidebar('sidebar-1'); ?>
</div>
</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>