<?php
/**
**@This file is part of TIE.
**@TIE is free software: you can redistribute it and/or modify
**@it under the terms of the GNU General Public License as published by
**@the Free Software Foundation, either version 3 of the License, or
**@(at your option) any later version.
**@TIE is distributed in the hope that it will be useful,
**@but WITHOUT ANY WARRANTY; without even the implied warranty of
**@MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
**@GNU General Public License for more details.
**@You should have received a copy of the GNU General Public License
**@along with TIE.  If not, see <http://www.gnu.org/licenses/>.

Copyright (C) 2008-2009 The SUIT Group.
http://www.suitframework.com/
http://www.suitframework.com/docs/credits
**/
if (isset($_POST['languages_update']) && isset($_POST['languages_entry']))
{
	$language = intval($_POST['languages_entry']);
	//Grab the array of existant languages.
	$suit->getTemplate('languages');
	$languages = $suit->tie->vars['languages'];
	//Assuring ourselves that the language exists and that a negative value was not provided, then we may proceed.
	if (isset($languages[$language]) || $language == -1)
	{
		//Change your current language to the chosen one, and then redirect.
		setcookie($suit->tie->config['cookie']['prefix'] . 'language', $language, time() + $suit->tie->config['cookie']['length'], $suit->tie->config['cookie']['path'], $suit->tie->config['cookie']['domain']);
		$suit->tie->navigation->redirect($suit->tie->config['redirect']['interval'], $_SERVER['HTTP_REFERER'], $suit->tie->language['updatedsuccessfully']);
	}
}
//Parse the output.
$output = $suit->tie->parseLanguages($output);
$output = $suit->tie->parseVariables($output);
$output = $suit->tie->parseTemplates($output);
//Move on to the selection of languages.
$languages = array();
//Grab the array of existant languages.
$suit->getTemplate('languages');
$languagesarray = $suit->tie->vars['languages'];
if (is_array($languagesarray))
{
	foreach ($languagesarray as $key => $value)
	{
		//Store the two values in an array, for looping afterwards.
		$languages[] = array
		(
			array('<id>', $key),
			array('<title>', htmlentities($value[0]))
		);
	}
}
//Create the drop-down list for language selection by parsing the loop.
$output = $suit->tie->parseLoop('languages', $languages, $output);
if (is_array($languagesarray))
{
	foreach ($languagesarray as $key => $value)
	{
		//Allow us to pinpoint the current language in the menu, if it is set.
		$output = $suit->tie->parseConditional($key, (intval($suit->tie->languageid) == $key), $output);
	}
}
?>