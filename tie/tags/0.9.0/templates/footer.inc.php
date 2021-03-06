<?php
/**
**@This file is part of The SUIT Framework.

**@SUIT is free software: you can redistribute it and/or modify
**@it under the terms of the GNU General Public License as published by
**@the Free Software Foundation, either version 3 of the License, or
**@(at your option) any later version.

**@SUIT is distributed in the hope that it will be useful,
**@but WITHOUT ANY WARRANTY; without even the implied warranty of
**@MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
**@GNU General Public License for more details.
**@You should have received a copy of the GNU General Public License
**@along with SUIT.  If not, see <http://www.gnu.org/licenses/>.
**/
$tie = &$suit->templates->vars['tie'];
$output = $tie->parsePhrases($output);
$output = $tie->parseTemplates($output);
$form = $tie->languageForm($tie->language['realid']);
$output = str_replace('<languages>', $form, $output);
//Page load ending time.
$endtime = microtime();
$endtime = explode(' ', $endtime);
$endtime = $endtime[1] + $endtime[0];
$endtime = $endtime - $suit->templates->vars['start'];
//print round($endtime, 4);
//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{
	$suit->templates->vars[$var_name] = &$$var_name;
}
?>