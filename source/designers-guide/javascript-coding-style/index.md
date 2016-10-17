---
layout: default
title: JavaScript coding Style
github_link: designers-guide/javascript-coding-style/index.md
indexed: true
group: Frontend Guides
subgroup: General Resources
menu_title: Javascript Coding Style
menu_order: 10
---

This document explains the basic styles and patterns used in the Shopware JavaScript codebase. New code should try to conform to these standards so that it is as easy to maintain as existing code. Of course every rule has an exception, but it's important to know the rules nonetheless.

The coding styling is heavily inspired by [Mozilla's coding style](https://developer.mozilla.org/en-US/docs/Mozilla/Developer_guide/Coding_Style).

### Naming and formatting code

#### Whitespace
No tabs. No whitespace at the end of a line. Unix-style linebreaks ```\n```, not Windows-style ```\r\n```.

#### Indention
Four spaces per logic level. Keep in mind `switch case` labels comsume a logic level as well.

#### Control structures
Use K&R bracing style: left brace at end of first line, wrap else on both sides. Always brace controlled statements, even a single-line consequence of an `if` or `else`. This is typically redundant, but avoids dangling `else` bugs, so it's safer at scale than fine-tuning.

```
if (...) {
} else if (...) {
} else {
}

while (...) {
}

do {
} while (...);

for (...; ...; ...) {
}

switch (...) {
    case 1: {
        break;
    }
    case 2:
        ...
        break;
    default:
        break;
}
```

#### Function declaration
In JavaScript, functions should use camelCase but should not capitalize the first letter. Methods should not use the named function expression syntax, because our tools understand method names:

```
doSomething: function (foo, bar) {
	...
}
```

In-line functions should have spaces around braces, except before commas or semicolons:

```
function valueObject(value) { return { value: value }; }
```

#### Objects

```
var foo = { prop1: "value1" };

var bar = {
	prop1: "value1",
	prop2: "value2"
};
```

Constructors for objects should be capitalized and use CamelCase:

```
function ObjectConstructor() {
	this.foo = '';
}
```

#### Prefixes
Follow these naming prefix conventions:

* ```_ = member (variable or function)```
	* e.g. ```_length``` or ```_setType(type)```
* ```on = event handler```
	* e.g ```function onLoad()```
 
