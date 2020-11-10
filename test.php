<?php
$string = 'gunwo jebane';

$ex = explode(' ', $string);


echo $ex[0] . ' ' . $ex[1];

echo '<br><br><br><br>';
$inny = 'hwdp policji asdasd asddas';

$ja = explode(' ', $inny);

echo str_replace($ja[0] . ' ', '', $inny);
