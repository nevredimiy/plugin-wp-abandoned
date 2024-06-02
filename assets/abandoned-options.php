<div class="wrap abandoned-options">
    <h1>Abandoned Orders Log</h1>
    <?php
    $file = plugin_dir_path(__FILE__) . '../client_data_log.txt';
    $fp = fopen($file, "r");
    $arrTemp = array();
    if ($fp) {
        while (($buffer = fgets($fp, 4096)) !== false) {
            array_unshift($arrTemp, $buffer);
        }
        if (!feof($fp)) {
            echo "Ошибка: fgets() неожиданно потерпел неудачу\n";
        }
        fclose($fp);
    }
    foreach ($arrTemp as $value) {
        echo '<p>' . $value . '</p>';
    }
    ?>
</div>