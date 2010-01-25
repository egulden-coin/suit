<?php
/**
**@This file is part of BBNode.
**@BBNode is free software: you can redistribute it and/or modify
**@it under the terms of the GNU Lesser General Public License as published by
**@the Free Software Foundation, either version 3 of the License, or
**@(at your option) any later version.
**@BBNode is distributed in the hope that it will be useful,
**@but WITHOUT ANY WARRANTY; without even the implied warranty of
**@MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
**@GNU Lesser General Public License for more details.
**@You should have received a copy of the GNU Lesser General Public License
**@along with BBNode.  If not, see <http://www.gnu.org/licenses/>.

Copyright (C) 2008-2010 The SUIT Group.
http://www.suitframework.com/
http://www.suitframework.com/docs/credits
**/
$suit->language = array
(
    'copyright' => 'Copyright &copy; 2008-2010 <a href="http://www.suitframework.com/docs/credits" target="_blank">The SUIT Group</a>. All Rights Reserved.',
    'default' => 'Default',
    'example' => 'Example',
    'message' => 'Message',
    'poweredby' => 'Powered by <a href="http://www.suitframework.com/" target="_blank">SUIT</a>',
    'slacks' => 'See this page built using SLACKS',
    'slogan' => 'BBCode Using SUIT Nodes',
    'submit' => 'Submit',
    'suit' => 'SUIT',
    'title' => 'BBNode',
    'update' => 'Update'
);
switch (strtolower($_GET['language']))
{
    case 'english':
        $suit->languagename = 'english';
        break;
    default:
        $suit->languagename = 'default';
        break;
}
if (array_key_exists('submit', $_POST) && $_POST['submit'])
{
    require '../bbnode.class.php';
    $bbnode = new BBNode();
    foreach ($bbnode->nodes as $key => $value)
    {
        if (array_key_exists('var', $value) && array_key_exists('label', $value['var']))
        {
            $bbnode->nodes[$key]['var']['template'] = file_get_contents('../templates/' . $value['var']['label'] . '.tpl');
        }
    }
    $config = array
    (
        'escape' => ''
    );
    $suit->message = $_POST['message'];
    $suit->executed = $suit->execute($bbnode->nodes, nl2br(htmlentities($_POST['message'])));
}
else
{
    $suit->message = '';
}
?>