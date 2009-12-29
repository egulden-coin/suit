<?php
/**
**@This file is part of SUIT.
**@SUIT is free software: you can redistribute it and/or modify
**@it under the terms of the GNU Lesser General Public License as published by
**@the Free Software Foundation, either version 3 of the License, or
**@(at your option) any later version.
**@SUIT is distributed in the hope that it will be useful,
**@but WITHOUT ANY WARRANTY; without even the implied warranty of
**@MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
**@GNU Lesser General Public License for more details.
**@You should have received a copy of the GNU Lesser General Public License
**@along with SUIT.  If not, see <http://www.gnu.org/licenses/>.

Copyright (C) 2008-2009 The SUIT Group.
http://www.suitframework.com/
http://www.suitframework.com/docs/credits
**/
$nodes = new Nodes();
$suit->vars['files'] = array
(
    'code' => 'code',
    'templates' => 'templates'
);
$suit->vars['filetypes'] = array
(
    'code' => 'inc.php',
    'templates' => 'tpl'
);
$suit->vars['nodes'] = array
(
    '[' => array
    (
        'close' => ']'
    ),
    '[assign]' => array
    (
        'close' => '[/assign]',
        'function' => array
        (
            array
            (
                'function' => 'assign',
                'class' => $nodes
            )
        ),
        'var' => array
        (
            'var' => ''
        )
    ),
    '[assign' => array
    (
        'close' => ']',
        'function' => array
        (
            array
            (
                'function' => 'attribute',
                'class' => $nodes
            )
        ),
        'attribute' => '[assign]',
        'skip' => true,
        'var' => array
        (
            'equal' => '=',
            'quote' => '"'
        )
    ),
    '[comment]' => array
    (
        'close' => '[/comment]',
        'function' => array
        (
            array
            (
                'function' => 'comments',
                'class' => $nodes
            )
        ),
        'skip' => true
    ),
    '[escape]' => array
    (
        'close' => '[/escape]',
        'function' => array
        (
            array
            (
                'function' => 'escape',
                'class' => $nodes
            )
        ),
        'skip' => true,
        'skipescape' => true,
        'var' => "\r.\n.\t ."
    ),
    /*
    '[eval]' => array
    (
        'close' => '[/eval]',
        'function' => array
        (
            array
            (
                'function' => 'evaluation',
                'class' => $nodes
            )
        )
    ),
    */
    '[if]' => array
    (
        'close' => '[/if]',
        'function' => array
        (
            array
            (
                'function' => 'condition',
                'class' => $nodes
            )
        ),
        'skip' => true,
        'transform' => false,
        'var' => array
        (
            'condition' => false,
            'else' => false
        )
    ),
    '[if' => array
    (
        'close' => ']',
        'function' => array
        (
            array
            (
                'function' => 'attribute',
                'class' => $nodes
            ),
            array
            (
                'function' => 'conditionskip',
                'class' => $nodes
            )
        ),
        'attribute' => '[if]',
        'skip' => true,
        'var' => array
        (
            'equal' => '=',
            'quote' => '"'
        )
    ),
    '[loop]' => array
    (
        'close' => '[/loop]',
        'function' => array
        (
            array
            (
                'function' => 'loop',
                'class' => $nodes
            )
        ),
        'skip' => true,
        'var' => array
        (
            'vars' => serialize(array()),
            'delimiter' => '',
            'node' => '[loopvar]'
        )
    ),
    '[loop' => array
    (
        'close' => ']',
        'function' => array
        (
            array
            (
                'function' => 'attribute',
                'class' => $nodes
            )
        ),
        'attribute' => '[loop]',
        'skip' => true,
        'var' => array
        (
            'blacklist' => true,
            'equal' => '=',
            'list' => array('node'),
            'quote' => '"'
        )
    ),
    '[loopvar]' => array
    (
        'close' => '[/loopvar]',
        'function' => array
        (
            array
            (
                'function' => 'loopvariables',
                'class' => $nodes
            )
        ),
        'var' => array
        (
            'delimiter' => '=>',
            'ignore' => array(),
            'serialize' => false,
            'var' => array()
        )
    ),
    '[loopvar' => array
    (
        'close' => ']',
        'function' => array
        (
            array
            (
                'function' => 'attribute',
                'class' => $nodes
            )
        ),
        'attribute' => '[loopvar]',
        'skip' => true,
        'var' => array
        (
            'equal' => '=',
            'list' => array('serialize'),
            'quote' => '"'
        )
    ),
    '[replace]' => array
    (
        'close' => '[/replace]',
        'function' => array
        (
            array
            (
                'function' => 'replace',
                'class' => $nodes
            )
        ),
        'var' => array
        (
            'replace' => '',
            'search' => ''
        )
    ),
    '[replace' => array
    (
        'close' => ']',
        'function' => array
        (
            array
            (
                'function' => 'attribute',
                'class' => $nodes
            )
        ),
        'attribute' => '[replace]',
        'skip' => true,
        'var' => array
        (
            'equal' => '=',
            'quote' => '"'
        )
    ),
    '[return' => array
    (
        'close' => '/]',
        'function' => array
        (
            array
            (
                'function' => 'attribute',
                'class' => $nodes
            ),
            array
            (
                'function' => 'returning',
                'class' => $nodes
            )
        ),
        'skip' => true,
        'var' => array
        (
            'equal' => '=',
            'onesided' => true,
            'quote' => '"',
            'var' => array
            (
                'stack' => false
            )
        )
    ),
    '[template]' => array
    (
        'close' => '[/template]',
        'function' => array
        (
            array
            (
                'function' => 'templates',
                'class' => $nodes
            )
        ),
        'var' => array
        (
            'files' => $suit->vars['files'],
            'filetypes' => $suit->vars['filetypes'],
            'delimiter' => '=>'
        )
    ),
    '[template' => array
    (
        'close' => ']',
        'function' => array
        (
            array
            (
                'function' => 'attribute',
                'class' => $nodes
            )
        ),
        'attribute' => '[template]',
        'skip' => true,
        'var' => array
        (
            'equal' => '=',
            'list' => array('label'),
            'quote' => '"'
        )
    ),
    '[trim]' => array
    (
        'close' => '[/trim]',
        'function' => array
        (
            array
            (
                'function' => 'trim',
                'class' => $nodes
            )
        )
    ),
    '[try]' => array
    (
        'close' => '[/try]',
        'function' => array
        (
            array
            (
                'function' => 'trying',
                'class' => $nodes
            )
        ),
        'skip' => true,
        'var' => array
        (
            'var' => ''
        )
    ),
    '[try' => array
    (
        'close' => ']',
        'function' => array
        (
            array
            (
                'function' => 'attribute',
                'class' => $nodes
            )
        ),
        'attribute' => '[try]',
        'skip' => true,
        'var' => array
        (
            'equal' => '=',
            'quote' => '"'
        )
    ),
    '[var]' => array
    (
        'close' => '[/var]',
        'class' => $nodes,
        'function' => array
        (
            array
            (
                'function' => 'variables',
                'class' => $nodes
            )
        ),
        'var' => array
        (
            'delimiter' => '=>',
            'serialize' => false
        )
    ),
    '[var' => array
    (
        'close' => ']',
        'function' => array
        (
            array
            (
                'function' => 'attribute',
                'class' => $nodes
            )
        ),
        'attribute' => '[var]',
        'skip' => true,
        'var' => array
        (
            'equal' => '=',
            'list' => array('serialize'),
            'quote' => '"'
        )
    )
);
$suit->vars['condition'] = array();
$suit->vars['loop'] = array();
?>