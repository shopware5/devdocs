---
layout: default
title: Basic conventions
github_link: shopware-enterprise/b2b-suite/technical/basic-conventions.md
indexed: true
menu_title: Basic conventions
menu_order: 2
menu_style: numeric
menu_chapter: true
group: Shopware Enterprise
subgroup: B2B-Suite
subsubgroup: Technical Documentation
---

This is the list of easy - mostly naming - conventions that the B2B-Suite complies to:

Group | Practice
---|---
DI Container | All container ids look like `b2b_*.*`
 | The first asterisk is the component name
 | The second asterisk is a class name abbreviation
Database | All table names start with `b2b_`
 | All table names are in **singular**
 | All field and table names are in snake case
Attributes | All attribute names start with `swag_b2b_`
Subscriber | All subscriber methods are named in accordance to their function, not to the event.
Tests | All test methods are in snake case
 | All test methods start with `test_`
Controller | All controller names start with `B2b`
 | View assignment should be done through the assign method eg `$this->View()->assign('foo', 'bar');`
Templates | All new layout modules are wrapped in `b2b--*` class containers
 | modules reuse the template style of shopware
 | CSS: 3 levels of selector depth as max
 | `{block name="swag_b2b_*"}{/block}` empty blocks are in one line
JavaScript | jQuery plugins are prefixed with `b2b`
 | jQuery plugins are written for the `StateManager`
Snippets | Namespace in CamelCase
 | First line of every template: `{namespace name="frontend/plugins/b2b_debtor_plugin"}`
 | Use snippets with english defaults `{s name="TestSnippet"} Test snippet {/s}`
