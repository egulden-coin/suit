<?php
$output = $suit->tie->parseVariables($output);
$output = $suit->tie->parsePhrases($output);
$output = $suit->tie->parseTemplates($output);
$output = str_replace('<name>', $suit->tie->language['error'], $output);
print $output;
exit;
?>