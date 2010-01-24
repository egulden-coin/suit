import os
import pickle

from pylons import config, c, url as _url
from pylons.i18n import gettext as _gettext

from pysuitlons.lib.suit import suit, nodes
from pysuitlons.lib.templating import render

from webhelpers.html import escape

def code(params):
    """Execute a code file"""
    try:
        module = os.path.join(config['suit.templates'], 'logic',
            '%s.py' % (params['case'].replace('.', '/'))
        )
        execfile(module)
    except ImportError:
        pass
    params['case'] = ''
    return params

def gettext(params):
    """Grabs a gettext string."""
    params['case'] = _gettext(params['var']['string'])
    return params

def filtering(params):
    """Escapes HTML; by default, all nodes follow this basic rule to simulate
    how Pylons configured Mako to run.
    """
    if (not params['var']['json'] or not params['var']['serialize']):
        params['case'] = escape(params['case'])
    return params

def tmpl_context(params):
    """Rip-off of SUIT's default [var] node. Reads variables from the
    tmpl_context.
    """
    split = suit.explodeunescape(
        params['var']['delimiter'],
        params['case'],
        params['config']['escape']
    )
    for key, value in enumerate(split): 
        if key == 0:
            params['case'] = getattr(c, value)
        else:
            try:
                params['case'] = params['case'][value] 
            except (AttributeError, TypeError):
                try:
                    params['case'] = params['case'][int(value)]
                except (AttributeError, TypeError, ValueError):
                    params['case'] = getattr(params['case'], value)
    if params['var']['json']:
        params['case'] = nodes.jsonencode(params['case'])
    if params['var']['serialize']:
        params['case'] = pickle.dumps(params['case'])
    return params

def sa(params):
    """Adds SQLAlchemy instances to the current session to allow access to its
    attributes.
    """
    for key, value in enumerate(params['var']['vars']):
        try:
            value.items()
        except AttributeError:
            try:
                params['var']['vars'][key] = elixir.session.merge(value)
            except UnmappedInstanceError:
                pass
    return params

def templates(params):
    """Grab a template from a file"""
    #If the template is not whitelisted or blacklisted
    try:
        filepath = os.path.join(config['suit.templates'], 
            os.path.normpath(params['case'])
        )
        params['case'] = open(filepath).read()
    except IOError:
        params['case'] = ''
    return params


def url(params):
    """Returns a URL for the given URL settings supplied as parameters."""
    try:
        params['case'] = u'%s' % (_url(**params['var']))
    except TypeError:
        params['case'] = ''
    return params

suitnodes = nodes.NODES
# Adjust the default nodes for Pylons convenience.
suitnodes['[assign]']['var']['var']['delimiter'] = '.'
suitnodes['[loopvar]']['var']['var']['delimiter'] = '.'
suitnodes['[try]']['var']['var']['delimiter'] = '.'
suitnodes['[var]']['var']['var']['delimiter'] = '.'
suitnodes['[template]']['stringfunctions'] = [templates]
suitnodes['[code]']['stringfunctions'] = [code]
#
suitnodes['[loopvar]']['stringfunctions'].append(filtering)
suitnodes['[var]']['stringfunctions'].append(filtering)

pylonsnodes = {
    '[c]':
    {
        'close': '[/c]',
        'stringfunctions': [nodes.attribute, nodes.jsondecode, tmpl_context,
            filtering
        ],
        'var':
        {
            'equal': '=',
            'list': ('json', 'serialize'),
            'quote': ('"', '\''),
            'var':
            {
                'decode': ('json', 'serialize'),
                'delimiter': '.',
                'json': 'false',
                'serialize': 'false'
            }
        }
    },
    '[c':
    {
        'close': ']',
        'create': '[c]',
        'skip': True
    },
    '[gettext': 
    { 
        'close': '/]',
        'stringfunctions': [nodes.attribute, gettext], 
        'skip': True,
        'var':
        {
            'equal': '=',
            'onesided': True,
            'quote': ('"', '\''),
            'var': {}
        }
    },
    '[url': 
    {
        'close': '/]',
        'stringfunctions': [nodes.attribute, url], 
        'skip': True,
        'var':
        {
            'equal': '=',
            'onesided': True,
            'quote': ('"', '\''),
            'var': {}
        }
    }
}

NODES = dict(suitnodes, **pylonsnodes)