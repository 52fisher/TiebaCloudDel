<?php
require './lib/cloudDel.class.php';
require './lib/config.php';
$cloudDel = new cloudDel($config);
if ($config['CLI']) {
    while (true) {
        $cloudDel->work();
        sleep($config['interval']);
    }
} else {
    $cloudDel->work();
}
