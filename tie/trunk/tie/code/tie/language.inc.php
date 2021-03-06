<?php
/**
**@This file is part of TIE.
**@TIE is free software: you can redistribute it and/or modify
**@it under the terms of the GNU Lesser General Public License as published by
**@the Free Software Foundation, either version 3 of the License, or
**@(at your option) any later version.
**@TIE is distributed in the hope that it will be useful,
**@but WITHOUT ANY WARRANTY; without even the implied warranty of
**@MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
**@GNU Lesser General Public License for more details.
**@You should have received a copy of the GNU Lesser General Public License
**@along with TIE.  If not, see <http://www.gnu.org/licenses/>.

Copyright (C) 2008-2010 Brandon Evans and Chris Santiago.
http://www.suitframework.com/
http://www.suitframework.com/docs/credits
**/
if (isset($_POST['languages_update']) && isset($_POST['languages_entry']))
{
    $language = intval($_POST['languages_entry']);
    require 'code/languages/main.inc.php';
    if (isset($languages[$language]) || $language == -1)
    {
        setcookie($suit->tie->config['cookie']['prefix'] . 'language', $language, time() + $suit->tie->config['cookie']['length'], $suit->tie->config['cookie']['path'], $suit->tie->config['cookie']['domain']);
        $suit->tie->redirect($_SERVER['HTTP_REFERER'], $suit->language['updatedsuccessfully']);
    }
}
$languagesloop = array();
require 'code/languages/main.inc.php';
asort($languages);
foreach ($languages as $key => $value)
{
    $languagesloop[] = array
    (
        'id' => $key,
        'selected' => (intval($suit->tie->language) == $key),
        'title' => htmlspecialchars($value[0])
    );
}
$suit->loop['languages'] = $languagesloop;
?>