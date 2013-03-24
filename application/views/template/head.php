<!DOCTYPE html>
<html lang="en">
<head>
<?php
foreach($this->CSS as $css)
    echo "\t".'<link href="assets/css/'.$css.'.css" rel="stylesheet">'."\n";

echo "\t <title>".$this->TITLE."</title>\n";
?>
<link rel="icon" href="assets/img/favicon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="assets/img/favicon.ico" type="image/x-icon" />
</head>
