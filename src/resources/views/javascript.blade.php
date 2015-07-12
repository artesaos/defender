<script>
    (function(w){
        var defender = {};

        defender.roles = <?php echo $roles; ?>;
        defender.permissions = <?php echo $permissions; ?>;

        w.<?php echo config('defender.js_var_name', 'defender'); ?> = defender;
    })(window);
</script>