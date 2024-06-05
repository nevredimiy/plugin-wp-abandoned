<div class="wrap abandoned-options">
    <h1>Abandoned Orders Log</h1>
    <?php
    global $wpdb;
    $table_name = $wpdb->get_blog_prefix() . 'abandoned';
    // Delete chose rows
    if($_POST["line"]) {
        $ids = implode( ',', array_map( 'absint', $_POST["line"] ) );
        $wpdb->query( "DELETE FROM $table_name WHERE id IN($ids)" );
    }

    $data_from_table = $wpdb->get_results( "SELECT * FROM {$table_name}" );
    ?>
    <form method="post">
        <table>
            <tr>
                <th></th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Product name</th>
                <th>Total price</th>
                <th>Time</th>
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
            <button type="submit">Удалить выбранные строки</button>
        </td>
    </tr>
    </table>
    </form>
</div>