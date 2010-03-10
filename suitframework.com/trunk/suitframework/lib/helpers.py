"""Helper functions

Consists of functions to typically be used within templates, but also
available to Controllers. This module is available to templates as 'h'.
"""
# Import helpers as desired, or define your own, ie:
#from webhelpers.html.tags import checkbox, password
try:
    import simplejson as json
except ImportError:
    import json
import os
from glob import glob
from pylons import config, request, response, tmpl_context as c, url
from pylons.controllers.util import redirect
from pylons.i18n import get_lang, set_lang, ugettext as _gettext
import suit
import urllib
from webhelpers.html import escape
from webhelpers.html.builder import literal
from pygments import highlight
from pygments.lexers import get_lexer_by_name
from pygments.formatters import HtmlFormatter

def docs():
    c.loop.articles = [
        {
            'articles': 
            [
                {
                    'title': 'FAQ',
                    'jump': '',
                    'url': 'faq'
                },
                {
                    'title': 'Getting Started',
                    'jump': '',
                    'url': 'gettingstarted'
                }
            ],
            'title': 'General',
            'url': 'general'
        },
        {
            'articles': 
            [
                {
                    'title': 'execute',
                    'jump': '',
                    'url': 'execute'
                },
                {
                    'title': 'What!? That\'s it?',
                    'jump': 'whatthatsit',
                    'url': 'faq'
                }
            ],
            'title': 'SUIT Functions',
            'url': 'suitfunctions'
        }
    ]
    c.condition.notfound = False
    c.condition.article = False
    c.condition.index = False
    c.condition.matches = False
    if not c.parameter1:
        c.condition.index = True
    elif os.path.isfile(
        os.path.join(
            config['pylons.paths']['templates'],
            'docs',
            os.path.normpath(c.parameter1.lower()) + '.tpl'
        )
    ):
        for category in c.loop.articles:
            for article in category['articles']:
                if c.parameter1 == article['url'] and not article['jump']:
                    c.article = article['title']
        c.condition.article = True
    else:
        c.loop.search = []
        for category in c.loop.articles:
            for article in category['articles']:
                if os.path.basename(
                    article['url']
                ).find(c.parameter1.lower()) != -1:
                    c.loop.search.append(article)
        c.condition.notfound = True
        c.condition.matches = (c.loop.search)
    return ''

def header():
    if ('submit' in request.POST and
    request.POST['submit'] == _gettext('Search')):
        redirect(
            url(
                controller = 'root',
                action = 'template',
                templatefile = 'docs',
                parameter1 = request.POST['search'].replace(' ', '').lower()
            ),
            code = 303
        )
    #if ('submit' in request.POST and
    #request.POST['submit'] == _gettext('Update')):
    c.loop.languages = [
        {
            'id': 'en',
            'title': 'English',
            'selected': (get_lang() == 'en')
        }
    ]
    return ''

def pygments(lexer, string):
    return highlight(string, get_lexer_by_name(lexer), HtmlFormatter())
    
def slacks():
    if ('submit' in request.POST and
    request.POST['submit'] == _gettext('Submit')):
        redirect(
            ''.join
            ((
                url(
                    controller = 'root',
                    action = 'template',
                    templatefile = 'slacks'
                ),
                '?url=',
                urllib.quote_plus(request.POST['url'])
            )),
            code = 303
        )
    c.referrer = request.headers["referer"]
    c.condition.tree = False
    c.condition.referrer = ('referrer' in request.GET and
    request.GET['referrer'])
    c.loop.tree = []
    try:
        tree = ''
        if 'url' in request.GET:
            tree = urllib.urlopen(
                request.GET['url'],
                urllib.urlencode({
                    'slacks': 'true'
                })
            ).read()
        elif ('submit' in request.POST and
        request.POST['submit'] == _gettext('Upload')):
            tree = request.POST['file'].file.read()
        tree = json.loads(tree)
        tree.sort(key = lambda item: item['id'])
        tree = slacksrecurse(
            tree,
            _gettext('No Wrapper'),
            _gettext('Wrapper')
        )
        c.condition.tree = True
        c.loop.tree = tree
    except (
        AttributeError,
        EOFError,
        IOError,
        NameError,
        TypeError,
        ValueError
    ):
        pass
    return ''

def slacksrecurse(tree, nowrapper, wrapper):
    for key, value in enumerate(tree):
        tree[key] = {
            'array': False,
            'contents': value,
            'recursed': True
        }
        if isinstance(value, dict):
            tree[key] = value
            tree[key]['array'] = True
            tree[key]['recursed'] = (not 'original' in tree[key])
            tree[key]['created'] = ('create' in tree[key])
            tree[key]['contents'] = slacksrecurse(
                value['contents'],
                nowrapper,
                wrapper
            )
            tree[key]['recursed'] = (not 'original' in value);
            if not 'rule' in value:
                tree[key]['rule'] = nowrapper
            elif not value['rule']:
                tree[key]['rule'] = wrapper
            for key2, value2 in enumerate(value['parallel']):
                tree[key]['parallel'][key2] = {
                    'parallel': value2
                }
    return tree

def tryit():
    if 'submit' in request.POST and request.POST['submit']:
        c.template = request.POST['template']
    else:
        try:
            c.template = open(
                os.path.join(
                    config['suit.templates'],
                    'tryit',
                    c.parameter2 + '.tpl'
                )
            ).read()
        except TypeError:
            c.template = ''
    c.rule = ''
    c.condition.rule = True
    rules = {}
    if c.parameter1 == 'templating':
        from rulebox import templating
        rules = templating.rules.copy()
        rules['[template]']['var']['list'] = []
        c.rule = 'Templating'
    elif c.parameter1 == 'suitlons':
        from rulebox import suitlons
        rules = suitlons.rules
        c.rule = 'SUITlons'
    elif c.parameter1 == 'bbcode':
        from rulebox import bbcode
        rules = bbcode.rules
        for value in rules.items():
            if 'var' in value[1] and 'label' in value[1]['var']:
                rules[value[0]]['var']['template'] = open(
                        os.path.join(
                            config['suit.templates'],
                            'bbcode',
                            value[1]['var']['label'] + '.tpl'
                        )
                    ).read()
        c.template = escape(
            c.template,
            True
        ).replace('\n','<br />\n')
        c.rule = 'BBCode'
    elif c.parameter1 == None:
        c.condition.rule = False
    c.php = ''
    c.python = ''
    c.condition.php = False
    c.condition.python = False
    try:
        python = os.path.join(
            config['suit.templates'],
            'tryit',
            'code',
            c.parameter2 + '.py'
        )
        execfile(python)
        c.python = open(python).read()
        c.condition.python = True
        c.php = open(
            os.path.join(
                config['suit.templates'],
                'tryit',
                'code',
                c.parameter2 + '.php'
            )
        ).read()
        c.condition.php = True
    except (IOError, TypeError):
        pass
    c.executed = suit.execute(rules, c.template)
    return ''