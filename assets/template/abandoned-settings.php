<div class="ao-admin-menu_options">
    <?php settings_errors(); ?>
    <form action="options.php" method="post">
        <?php settings_fields( 'abandoned_general_group' ); ?>
        <?php do_settings_sections( 'abandoned-orders' ); ?>
        <?php submit_button(); ?>
    <?php var_dump( get_option('trigger_element') ); ?>
    <?php var_dump( get_option('event_el') ); ?>
    </form>
</div>