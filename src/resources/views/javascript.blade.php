<script>
    (function(w){
        var defender = new Object();

        defender.roles = <?php echo $roles->lists('name')->toJson(); ?>;
        defender.permissions = <?php echo $permissions->lists('name')->toJson(); ?>;

        w.<?php echo config('defender.js_var_name', 'defender'); ?> = defender;
    })(window);
</script>