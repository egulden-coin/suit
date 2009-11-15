<?php
/**
**@This program is free software: you can redistribute it and/or modify
**@it under the terms of the GNU Lesser General Public License as published by
**@the Free Software Foundation, either version 3 of the License, or
**@(at your option) any later version.
**@This program is distributed in the hope that it will be useful,
**@but WITHOUT ANY WARRANTY; without even the implied warranty of
**@MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
**@GNU Lesser General Public License for more details.
**@You should have received a copy of the GNU Lesser General Public License
**@along with this program.  If not, see <http://www.gnu.org/licenses/>.

Copyright (C) 2008-2009 The SUIT Group.
http://www.suitframework.com/
http://www.suitframework.com/docs/credits
**/
class Section
{
    public $sections = array();

    public function __construct($owner)
    {
        $this->owner = $owner;
    }

    /**
    http://www.suitframework.com/docs/condition
    **/
    public function condition($if, $boolean, $else = NULL, $config = array())
    {
        $return = array();
        if (!array_key_exists('open', $config))
        {
            $config['open'] = $this->owner->config['parse']['section']['open'];
        }
        if (!array_key_exists('close', $config))
        {
            $config['close'] = $this->owner->config['parse']['section']['close'];
        }
        if (!array_key_exists('end', $config))
        {
            $config['end'] = $this->owner->config['parse']['section']['end'];
        }
        if (!array_key_exists('trim', $config))
        {
            $config['trim'] = $this->owner->config['parse']['section']['trim'];
        }
        //Add the if node
        $return[$config['open'] . $if . $config['close']] = array
        (
            'class' => $this->owner->nodes,
            'close' => $config['open'] . $config['end'] . $if . $config['close'],
            'function' => 'condition',
            'skip' => !$boolean, //If the string will be removed, there is no reason to parse in between the opening and closing strings
            'strip' => true, //If this boolean is true, the node strips the opening and closing string
            'var' => array
            (
                'bool' => $boolean,
                'trim' => $config['trim']
            ) //The string will be used by the function
        );
        //If an else statement is provided
        if (isset($else))
        {
            //Add the else node
            $return[$config['open'] . $else . $config['close']] = array
            (
                'class' => $this->owner->nodes,
                'close' => $config['open'] . $config['end'] . $else . $config['close'],
                'function' => 'condition',
                'skip' => $boolean, //If the string will be removed, there is no reason to parse in between the opening and closing strings
                'strip' => true, //If this boolean is false, the node strips the opening and closing string
                'var' => array
                (
                    'bool' => !$boolean,
                    'trim' => $config['trim']
                ) //The string will be used by the function
            );
        }
        return $return;
    }

    /**
    http://www.suitframework.com/docs/get
    **/
    public function get($string, $content, $config = array())
    {
        if (!array_key_exists('open', $config))
        {
            $config['open'] = $this->owner->config['parse']['section']['open'];
        }
        if (!array_key_exists('close', $config))
        {
            $config['close'] = $this->owner->config['parse']['section']['close'];
        }
        if (!array_key_exists('end', $config))
        {
            $config['end'] = $this->owner->config['parse']['section']['end'];
        }
        if (!array_key_exists('escape', $config))
        {
            $config['escape'] = $this->owner->config['parse']['section']['escape'];
        }
        $nodes = array
        (
            $config['open'] . $string . $config['close'] => array
            (
                'class' => $this->owner->nodes,
                'close' => $config['open'] . $config['end'] . $string . $config['close'],
                'function' => 'getsection',
                'var' => array
                (
                    'open' => $config['open'] . $string . $config['close'],
                    'close' => $config['open'] . $config['end'] . $string . $config['close']
                ) //The string will be used by the function
            )
        );
        $this->sections = array();
        //Unescape when applicable, and populate sections with the inside of each section
        $content = $this->owner->parse($nodes, $content, $config);
        return $this->sections;
    }

    /**
    http://www.suitframework.com/docs/loop
    **/
    public function loop($string, $array, $implode = '', $config = array())
    {
        $return = array();
        if (!array_key_exists('open', $config))
        {
            $config['open'] = $this->owner->config['parse']['section']['open'];
        }
        if (!array_key_exists('close', $config))
        {
            $config['close'] = $this->owner->config['parse']['section']['close'];
        }
        if (!array_key_exists('end', $config))
        {
            $config['end'] = $this->owner->config['parse']['section']['end'];
        }
        if (!array_key_exists('loopopen', $config))
        {
            $config['loopopen'] = $this->owner->config['parse']['loop']['open'];
        }
        if (!array_key_exists('loopclose', $config))
        {
            $config['loopclose'] = $this->owner->config['parse']['loop']['close'];
        }
        if (!array_key_exists('separator', $config))
        {
            $config['separator'] = $this->owner->config['parse']['separator'];
        }
        if (!array_key_exists('trim', $config))
        {
            $config['trim'] = $this->owner->config['parse']['section']['trim'];
        }
        $return = array
        (
            $config['open'] . $string . $config['close'] => array
            (
                'class' => $this->owner->nodes,
                'close' => $config['open'] . $config['end'] . $string . $config['close'],
                'function' => 'loop',
                'skip' => true, //We want the function to run the parse, so there is no reason to parse in between the opening and closing strings
                'var' => array
                (
                    'array' => $array,
                    'config' => $config,
                    'implode' => $implode,
                ) //This will be used by the function
            )
        );
        return $return;
    }
}
?>