<?php
$p = 'xlinukvsigvqnjgr';
echo "Password: $p\n";
echo "Length: " . strlen($p) . "\n";
echo "Is alnum: " . (ctype_alnum($p) ? 'Yes' : 'No') . "\n";
echo "Characters: ";
for ($i = 0; $i < strlen($p); $i++) {
    echo $p[$i] . ' ';
}
echo "\n";
?>
