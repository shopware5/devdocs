---
title: Currency formatting is easy, isn't it?
tags:
- javascript
- currency formatting
- native api

categories:
- dev

authors: [stp]
github_link: blog/_posts/2017-02-06-javascript-currency-formatting.md

---

I stumbled across a quite common problem in eCommerce projects I would like to cover in this blog post. According to a 
popular saying "Money is what makes the world go round" do we have to deal with currencies all the time especially in an application with heavy
client side processing and templating. You're always using a formatter or helper methods in some way or another to deal with currencies. All
of these solutions are not feeling right. 

### What's the problem anyways?

Let's take a look at one of those functions to get a feeling for the situation we're facing. One of the simplest ways
is to have a function like this in place:

```
function formatCurrency (val) {
    return val.toString().replace('.', ',') + ' €';
}
```
*Very simple example for a currency formatter function*

As you can tell already, it just covers one specific currency format. If we're dealing with prices in any other currency
like USD for example, the helper fails miserably. So what we can do is extend the method to provide us with the ability
to support multiple currencies?

```
function formatCurrency (val, currency) {
    var currencyFormat;
    
    val = (Math.round(val * 100) / 100).toFixed(2);
    
    switch (currency) {
        case 'USD':
            currencyFormat = '$ 0.00';
            val = currencyFormat.replace('0.00', val);
            break;
        case 'EUR':
            currencyFormat = '0,00 €';
            val = value.replace('.', ',');
            val = currencyFormat.replace('0,00', val);
            break;
        default:
            throw new Error('Unknown currency format');
            break;
    }
    return val;
}
```
*More enhanced formatter helper - still with issues* 

Now let us take a closer look on the example above. As you can see we're supporting two currency formats (EUR and USD) now,
which is an enhancement compared to the very simple formatter we saw before but it is still far away from being perfect. Why you may asking?
What happens when our customer wants a shop in the UK? We would have to implement another currency format
in our helper. The solution is very limited regarding to the supported formats & currencies. The code isn't tested in-depth
and we want a solution which can cover all available currency formats. So let us sum up the con's / problems:

**tl;dr**

- Dealing with currency formatting on your own is a pain and not recommended 
- You have to support a bunch of formats and currencies (~ 270 currencies) 
- Workarounds are necessary to get a somewhat working solution with limitations
- The code isn't tested in-depth in different browsers with all the available currency formats

What you really want is a Native Browser API which is defined in an ECMAScript specification.

### `Number.toLocaleString` Native Method

In December 2012, ECMA International published the first edition of ECMA-402, better known as the [ECMA 
Internationalization API Specification 1.0](http://www.ecma-international.org/ecma-402/1.0/). This specification
describes an API to bring long overdue localization methods to ECMAScript implementations.

First I stumbled across `Number.toLocaleString()` in the [Mozilla Developer Network](https://developer.mozilla.org). It
provides you with the ability to format numbers language sensitive.

```
var num = 3500.99;
num.toLocaleString();
```

<iframe src="https://jsfiddle.net/klarstil/rkttsyyn/embedded/result/" frameborder="0" width="100%" height="150px"></iframe>

If we're taking the example a little bit further and using the `language` & `options` arguments, we can format the number
as a currency and format the number the way we want.

```
var num = 3500.99;
num.toLocaleString('de-DE', {
   style: 'currency',
   currency: 'EUR',
   currencyDisplay: 'name',
   useGrouping: true
});
```

<iframe src="https://jsfiddle.net/klarstil/mvL6eu7c/embedded/result/" frameborder="0" width="100%" height="150px"></iframe>


### `Intl.NumberFormat` Native API
The `Intl.NumberFormat` object is a constructor for objects that enables language sensitive number formatting. This is a 
great solution for the day-by-day problem we're facing with currency formatting. Especially the `options` properties `currency` and 
`currencyDisplay` are very interesting for our use case. Before we're deep diving into the functionality and
ability of the API I would like to take a closer look on the compatibility:

<iframe src="//caniuse.bitsofco.de/embed/index.html?feat=internationalization&amp;periods=future_1,current,past_1,past_2" frameborder="0" width="100%" height="390px"></iframe>

If your target browser doesn't support the International API specification, there's always a polyfill available on github. 
This is the case for the `Intl` Native API as well: [andyearnshaw/Intl.js/](https://github.com/andyearnshaw/Intl.js/).

Let us take a closer look on how to use the Native API, shall we?

```
new Intl.NumberFormat([locales[, options]]);
```
*Syntax for `Intl.NumberFormat`*

- `locales` - Optional argument. A string or an array with a [BCP 47](https://tools.ietf.org/html/rfc5646) language tag. 
If you omit the parameter the browser's locale will be used.
- `options` - Optional argument. An object with the following properties:
    - `localeMatcher`, `style`, `currency`, `currencyDisplay`, `useGrouping`, `minimumIntegerDigits`, `minimumFractionDigits`, `maximumFractionDigits`, `minimumSignificantDigits` & `maximumSignificantDigits`

For our use case the 3 most important properties inside the `options` object are: `style`, `currency`, `currencyDisplay`.

- `style` - Defines the style to use for the number formatting. `decimal` for plain number formatting, `currency` for 
currency formatting & `percent` for percent formatting.
- `currency` - The currency to use for currency formatting. Possible values are the [ISO 4217](http://www.iso.org/iso/home/standards/currency_codes.htm) 
currency codes, such as "USD" for the US dollar, "EUR" for the euro or "GBP" for british pound.
- `currencyDisplay` - How to display the currency. Possible values are `symbol` for a localized symbol of the currency, 
`code` to use the ISO currency code or `name` to use for a localized string of the currency.

```
var number = 123456.789;

// request a currency format
console.log(new Intl.NumberFormat('de-DE', { style: 'currency', currency: 'EUR' }).format(number));
// 23.456,79 €

// the Japanese yen doesn't use a minor unit
console.log(new Intl.NumberFormat('ja-JP', { style: 'currency', currency: 'JPY' }).format(number));
// ￥123,457
```
*Examples on how to use `Intl.NumberFormat`*

#### Feature detection

It always comes in handy to know how to detect a certain feature you want to use.

```
var toLocaleStringSupportsOptions = function() {
    return (typeof Intl == 'object' && Intl && typeof Intl.NumberFormat == 'function');
};
```

### Performance matters

After playing around with both approaches, I noticed a quite heavy downside of `Number.toLocaleString()`. It became a 
performance bottleneck with huge data sets. To back up my assumption I've created a benchmark to compare both approaches 
and get a number about the operations per second.

Before we dive into the statistics I would like to show off the two approaches we're comparing here:
 
**Number.toLocaleString()**

```
var opts = { style: 'currency', currency: 'EUR' };
12.49.toLocaleString('de-DE', opts);
```

**Intl.NumberFormat.format()**

```
var opts = { style: 'currency', currency: 'EUR' },
    numberFormat = new Intl.NumberFormat('de-DE', opts);

numberFormat.format(12.49)
```

Without further ado here are the results:

![Comparison Number.toLocaleString vs. Intl.NumberFormat.format()](/blog/img/stats-currency-formatting.png)

I highlighted the results of Chrome 58.0.2991, so we can take a closer look on the results.
 
**Chrome 58.0.2991:**
- `Number.toLocaleString()` - 7,421 operations per second
- `Intl.NumberFormat.format()` - 1,659,696 operations per second

The other results which I like to point out are the results from Mobile Safari. The benchmark was performed on an iPhone 7 Plus.

**Mobile Safari 10.0:**

- `Number.toLocaleString()` - 12,683 operations per second
- `Intl.NumberFormat.format()` - 2,125,766 operations per second

Hands down, Mobile Safari is by far the fastest browser regarding the `Intl` Native API which I found pretty impressive.
On the other hand we tested Chrome Mobile on a Samsung Galaxy S7 with very poor results:

**Chrome Mobile 55.0.1882**

- `Number.toLocaleString()` - 3,230 operations per second
- `Intl.NumberFormat.format()` - 265,300 operations per second

## Summary

The Native API `Intl.NumberFormat` is perfect for heavy client side applications which are dealing with huge data sets
and it is convenient to use. A helper function which uses the API can look like the following code snippet:

```
function NumberFormatter(locale, opts) {
    var formatNumber,
        defaults = {
            style: 'currency',
            currency: 'EUR'
        };
    opts = opts || {};
    opts = Object.assign({}, defaults, opts);
    
    formatNumber = new Intl.NumberFormat(locale, opts);
    return formatNumber.format;
};

var formatter = new NumberFormatter('de-DE');
console.log(formatter(12.49));
```

The `Intl` API comes with a bunch of other methods for internationalization purposes such as language specific date 
formatting.
