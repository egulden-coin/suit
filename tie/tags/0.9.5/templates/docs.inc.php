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
$suit->templates->getTemplate('tie');
$tie = &$suit->templates->vars['tie'];
$output = $tie->parsePhrases($output);

if (isset($_GET['title']))
{
	$title = $_GET['title'];
	$query = 'SELECT template FROM docs WHERE title = \'' . $title . '\'';
	$check = $suit->db->query($query); 
	
	if ($check && (mysql_num_rows($check)))
	{
		while ($row = $suit->db->fetch($check))
		{
			$list = $suit->templates->getTemplate($row['template']);
		}
	}
	else
	{
		$list = $suit->templates->getTemplate('docs_404');
	}
}
else
{
	$list = $suit->templates->getTemplate('docs_index');
}

$output = str_replace('<docs>', $list, $output);
$output = $tie->parseTemplates($output);

//Get the keys for the defined variables inside of the file.
$vars = array_keys(get_defined_vars());
foreach ($vars as $var_name)
{	
	$suit->templates->vars[$var_name] = &$$var_name;
}
?>
