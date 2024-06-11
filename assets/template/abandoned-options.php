<div class="wrap abandoned-options">
    <h1><?php echo esc_html__('Abandoned Orders Log', 'abandoned') ?></h1>
    <?php
    global $wpdb;
    $table_name = $wpdb->get_blog_prefix() . 'abandoned';
    // Delete chose rows
    if(isset($_POST["line"])) {
        $ids = implode( ',', array_map( 'absint', $_POST["line"] ) );
        $wpdb->query( "DELETE FROM $table_name WHERE id IN($ids)" );
    }
    $data_from_table = $wpdb->get_results( "SELECT * FROM {$table_name}" );
    // $data_from_table = [];
    // if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
    //     $data_from_table = $wpdb->get_results( "SELECT * FROM {$table_name}" );
	// }

    ?>
    <form method="post">
        <table>
            <tr>
                <th></th>
                <th><?php echo esc_html__('First Name', 'abandoned') ?></th>
                <th><?php echo esc_html__('Last Name', 'abandoned') ?></th>
                <th><?php echo esc_html__('Phone', 'abandoned') ?></th>
                <th><?php echo esc_html__('Email', 'abandoned') ?></th>
                <th><?php echo esc_html__('Product name', 'abandoned') ?></th>
                <th><?php echo esc_html__('Total price', 'abandoned') ?></th>
                <th><?php echo esc_html__('Time', 'abandoned') ?></th>
            </tr>
    <?php

    foreach ($data_from_table as $value) {
        echo '<tr>';
        echo '<td><input type="checkbox" name="line[]" value="' . $value->id . '" /></td>';
        echo '<td>' . $value->first_name . '</td>';
        echo '<td>' . $value->last_name . '</td>';
        echo '<td>' . $value->phone . '</td>';
        echo '<td>' . $value->email . '</td>';
        echo '<td>' . $value->product_name . '</td>';
        echo '<td>' . $value->price . '</td>';
        echo '<td>' . $value->time . '</td>';
        echo '</tr>';
    }

    ?>
    <tr>
        <td colspan="8">
            <button type="submit"><?php echo esc_html__('Delete selected lines', 'abandoned') ?></button>
        </td>
    </tr>
    </table>
    </form>
</div>