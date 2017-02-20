---
layout: default
title: JavaScript Coding Style
github_link: designers-guide/javascript-coding-style/index.md
indexed: true
group: Frontend Guides
subgroup: General Resources
menu_title: Javascript Coding Style
menu_order: 10
---

This document explains the basic styles and patterns used in the Shopware JavaScript codebase. New code should try to conform to these standards so that it is as easy to maintain as existing code. Of course every rule has an exception, but it's important to know the rules nonetheless.

The coding style is using the sharable eslint configuration "[standard](http://standardjs.com/)" with modifications.

## The rules

### 4 spaces for indention

**Rule:** [`indent`](http://eslint.org/docs/rules/indent)<br>
**Level:** error

We're always using 4 spaces and no tab characters. No whitespace at the end of a line. Unix-style linebreaks `\n`, not Windows-style `\r\n`.

```
// ✓ ok 
function sayHello (name) {
    console.log('hey', name);
}

// ✗ avoid
function sayHello (name) {
 console.log('hey', name);
}
```

### Single quotes for strings

**Rule:** [`quotes`](http://eslint.org/docs/rules/quotes)<br>
**Level:** error

Always use single quotes for string except to avoid escaping.

```
// ✓ ok 
console.log('hello there');

// ✗ avoid
console.log("hello there");
```

### Spacing before and after keywords

**Rule:** [`keyword-spacing`](http://eslint.org/docs/rules/keyword-spacing)<br>
**Level:** warn

```
// ✓ ok
if (condition) { ... }
 
// ✗ avoid
if(condition) { ... }
```

### No padding within blocks

**Rule:** [`padded-blocks`](http://eslint.org/docs/rules/padded-blocks)<br>
**Level:** warn

```
// ✓ ok
if (condition) {
    console.log('...');
} else {
    console.log('...');
}

// ✗ avoid
if (condition) {
    console.log('...');
    
} else {

    console.log('...');
}
```

### End statement with semicolon

**Rule:** [`semi`](http://eslint.org/docs/rules/semi)<br>
**Level:** error

```
// ✓ ok 
console.log('hello there');

// ✗ avoid
console.log('hello there')
```

### No unused variables

**Rule:** [`no-unused-vars`](http://eslint.org/docs/rules/no-unused-vars)<br>
**Level:** error

```
// ✗ avoid
function sayHello (name) {
    var foo = 'bar';
    
    console.log('hey', name);
}
```

### Infix operators must be spaced

**Rule:** [`space-infix-ops`](http://eslint.org/docs/rules/space-infix-ops)<br>
**Level:** error

```
// ✓ ok 
var x = 2;
var message = 'hello, ' + name + '!';

// ✗ avoid 
var x=2;
var message = 'hello, '+name+'!';
```

### Commas should have a space after them

**Rule:** [`comma-spacing`](http://eslint.org/docs/rules/comma-spacing)<br>
**Level:** error

```
// ✓ ok 
var list = [1, 2, 3, 4];
function greet (name, options) { ... }

// ✗ avoid 
var list = [1,2,3,4];
function greet (name,options) { ... }
```

### Keep else statements on the same line as their curly braces

**Rule:** [`brace-style`](http://eslint.org/docs/rules/brace-style)<br>
**Level:** error

```
// ✓ ok 
if (options.quiet !== true) console.log('done');

// ✗ avoid 
if (condition) {
  // ... 
}
else {
  // ... 
}
```

### For multi-line if statements, use curly braces

**Rule:** [`curly`](http://eslint.org/docs/rules/curly)<br>
**Level:** error

```
// ✓ ok 
if (options.quiet !== true) console.log('done');

// ✓ ok 
if (options.quiet !== true) {
    console.log('done');
}

// ✗ avoid 
if (options.quiet !== true)
    console.log('done');
```

### Always prefix browser globals

**Rule:** [`no-undef`](http://eslint.org/docs/rules/no-undef)<br>
**Level:** error

Exceptions are: `document`, `console` and `navigator`.

```
// ✓ ok
window.alert('hi');

// ✗ avoid
alert('hi');
```

### Multiple blank lines not allowed

**Rule:** [`no-multiple-empty-lines`](http://eslint.org/docs/rules/no-multiple-empty-lines)<br>
**Level:** error

```
// ✓ ok 
var value = 'hello world';
console.log(value);

// ✗ avoid 
var value = 'hello world';
 
 
console.log(value);
```

### For the ternary operator in a multi-line setting, place `?` and `:` on their own lines.

**Rule:** [`operator-linebreak`](http://eslint.org/docs/rules/operator-linebreak)<br>
**Level:** error

```
// ✓ ok 
var location = env.development ? 'localhost' : 'www.api.com';
 
// ✓ ok 
var location = env.development
    ? 'localhost'
    : 'www.api.com';
 
// ✗ avoid 
var location = env.development ?
    'localhost' :
    'www.api.com';
```

### Wrap conditional assignments with additional parentheses

**Rule:** [`no-cond-assign`](http://eslint.org/docs/rules/no-cond-assign)<br>
**Level:** error

This makes it clear that the expression is intentionally an assignment (=) rather than a typo for equality (===).

```
// ✓ ok 
while ((m = text.match(expr))) {
    // ... 
}
 
// ✗ avoid 
while (m = text.match(expr)) {
    // ... 
}
```
