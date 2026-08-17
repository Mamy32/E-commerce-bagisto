<?php
$d = "Line 1\r \r Line 2\r\n\r\nLine 3\nLine 4";
echo "Original:\n" . $d . "\n\n";

$d = str_replace(['\r', '\n', '\\r', '\\n'], "\n", $d);
$d = preg_replace("/\n\s*\n+/", "\n\n", $d);

echo "Result:\n";
echo nl2br($d);
