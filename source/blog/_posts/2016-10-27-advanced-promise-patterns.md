---
title: Promises Part II - Advanced promise patterns
tags:
- javascript
- promises
- theme

categories:
- dev

authors: [stp]
github_link: blog/_posts/2016-10-27-advanced-promise-patterns.md

---

In the last blog post, we took a brief look on promises in JavaScript. They are a great way to deal with asynchronous operations and providing you with a great flow control. In this blog post we're taking a closer look on the [Promise API](http://www.ecma-international.org/ecma-262/6.0/#sec-promise-constructor) and more sophisticated patterns. If you haven't read the [last blog post](https://developers.shopware.com/blog/2016/10/12/promises-asynchronous-processes-made-easy/), consider reading it first, before you're continuing with this one.

## Convert jQuery promises into standard compatible promises

I gave you a brief introduction to the `Deferred` object in jQuery and pointed out that a lot of jQuery's functions are working with promises already. The prime example is the `$.ajax()` method.

Let's assume we have a function named `fetchPost` which fetches data from an API endpoint:

```
function fetchPost(id) {
	id = id || 1;

	return $.ajax({
		'url': 'https://jsonplaceholder.typicode.com/posts/' + id
	});
}

// Call the function and output the response
fetchPost(1).done(function(response) {
	alert(JSON.stringfy(response));
});
```

<iframe src="https://jsfiddle.net/klarstil/u3sb8785/embedded/result/" frameborder="0" width="100%" height="250px"></iframe>

*Using the jQuery promise implementation*

We're using [jQuery's Promise](http://api.jquery.com/promise/) object to fulfill the promise. Now assume you're doing the transition from jQuery promises to standard compatible promises in your application. It would be very irritating for you and any third party developer to have a mix of both promise implementations in your application to deal with.

You can overcome this issue using `Promise.resolve()`, which lets you transform a jQuery promise to a standard compatible promise for example:

```
function fetchPost(id) {
	id = id || 1;
    
	var jQueryPromise = $.ajax({
    	'url': 'https://jsonplaceholder.typicode.com/posts/' + id
	});
    
    return Promise.resolve(jQueryPromise);
}

fetchPost(1).then(function(response) {
	alert(JSON.stringify(response));
});
```

<iframe src="https://jsfiddle.net/klarstil/pa0pg92a/3/embedded/result/" frameborder="0" width="100%" height="250px"></iframe>

*Transforming jQuery promises into standard promises*

The only difference in the above code is that we're not returing the jQuery promise right away. We're wrapping it in a call of `Promise.resolve()`. The call returning a promise object that is resolved with the given value which is the AJAX response in this example.

Sometimes you're finding yourself in the position that you don't know if you're dealing with a promise or not. In this case `Promise.resolve()` can come in handy too.

Let's assume we're working with a lot of numbers, our application uses promises but a third-party library does not. If you don't know what you're dealing with, always use `Promise.resolve()`:


```
function getNumber(num) {
	// We don't know if num is a promise or not, so we're calling Promise.resolve() to always return a new promise object.
	return Promise.resolve(num);
}

// Our given value IS NOT a promise
getNumber(10).then(function(num) {
	alert('The number is: ' + num);
});

// Our given value IS a promise
getNumber(Promise.resolve(10)).then(function() {
	alert('The number is: ' + num);
});
```

<iframe src="https://jsfiddle.net/klarstil/xz18dj0u/embedded/result/" frameborder="0" width="100%" height="250px"></iframe>

*Working with a mixed environment*

This pattern enables you to use promises throughout your application and use its advantages for a better flow control.

If you want a little more control and want to terminate if a value is a promise you can use this little code snippet:

```
function isPromise(obj) {
	return !!obj && (typeof obj === 'object' || typeof obj === 'function') && typeof obj.then === 'function';
}
```

Check out the [github repository](https://github.com/then/is-promise) with the full library from [@then](https://github.com/then).

## Parallel & sequential operations

In the one of the previous example we created a function called `fetchPost` which fetches a blog post from an API endpoint. Now imagine you want to fetch multiple posts in parallel. We're continuing using this function for the sake of simplicity.

Let's take a look on a parallel operation first. In the following example we're having an array with post IDs we want to fetch:

```
var postIds = [ 2, 4, 7, 42 ];

Promise.all(items.map(fetchPost)).then(function(results) {
	console.log(results);
});
```

<iframe src="https://jsfiddle.net/klarstil/o2h20qhq/embedded/result/" frameborder="0" width="100%" height="250px"></iframe>

*Parallel operations using promises*

In the above example we're using `Promise.all()` which returns a promise that will be fulfilled when all the returned promises in the given argument have been resolved or rejected. The argument has to be an iterable object such as an array though.

Running a collection of asynchronous operations in sequence takes a little more effort to come by. You have to chain the promises, so each operation doesn't starts until the previous operation has been fulfilled. We're taking the same example as above but this time we're putting it in sequence:

```
var postIds = [ 2, 4, 7, 42 ];

var sequencePromise = postIds.reduce(function(promise, item) {
	return promise.then(function(results) {
    	return fetchPost(item)
        	.then(results.push.bind(results))
        	.then(function() { return results; });
    });
}, Promise.resolve([]));
    
sequencePromise.then(function(results) {
	console.log(results);
});
```

<iframe src="https://jsfiddle.net/klarstil/6wtsps6m/embedded/result/" frameborder="0" width="100%" height="250px"></iframe>

*Sequential operations using promises*

There's a lot of buzz going on in the above example, so let's break it down. `Array.reduce()` applies a method against an accumulator and each value of the array to reduce it to a single value. The first argument is the callback method, the second one is the initial value. In our case, we're using a new promise object which is fulfilled with an empty array as a value. We're using the initial promise to call the `fetchPost()`, using the returned promise from the `fetchPost` method to write back the response of the call to our `results` array and returning the filled up `results` array which we defined as the argument of the initial value.

## Error handling
Sadly we're not living in a perfect world where everything goes well. Sometimes it's like a rollercoaster, you have good days but sometimes everything that can go wrong goes wrong ([Murphy's law](https://en.wikipedia.org/wiki/Murphy%27s_law)) - it's the same when you're coding. Errors can always occur along the way, a server can be offline temporarily or the user simply inputs something wrong.

Exceptions in JavaScript are synchronous which doesn't go along well in an asychronous operation. We're bringing back the [workflow diagram](https://developers.shopware.com/blog/2016/10/12/promises-asynchronous-processes-made-easy/#getting-started-with-promises) in our mind. A promise can have multiple states. It can be pending, fulfilled or rejected. In our case we want to take a closer look on the rejected state - it provides us with the ability to reject a promise when an error occurred.

One of the most useful features of promises is the automatic propagation of errors. However this feature is only useful if errors are correctly propagated up the call stack. If you're writing a promise chain and ignore the `reject()` method, errors in the chain will be silently ignored which can hide serious bugs in your application:

```
function random() {
	return new Promise(function(fulfill, reject) {
    	if (Math.random() > 0.5) {
        	fulfill('Yeah');
        } else {
        	new Error('Oh no something went wrong');
        }
    });
}

random().then(function(results) {
    alert('Success');
}).catch(function(err) {
	// ...never called
	alert('Error');
});
```

<iframe src="https://jsfiddle.net/klarstil/rn62zntv/embedded/result/" frameborder="0" width="100%" height="250px"></iframe>

*Error handling with promises - the wrong way*

As you can see in the above code and playing around with the example, the error will never be reported and silently ignored, which is a worst case scenario. This is the place where the `reject()` method comes in handy. The modification is very simple but has a huge impact on your application and the propagation of errors.

```
function random() {
	return new Promise(function(fulfill, reject) {
    	if (Math.random() > 0.5) {
        	fulfill('Yeah');
        } else {
        	reject(new Error('Oh no something went wrong'));
        }
    });
}

random().then(function(results) {
    alert('Success');
}).catch(function(err) {
	alert('Error');
});
```

<iframe src="https://jsfiddle.net/klarstil/few8L7gm/1/embedded/result/" frameborder="0" width="100%" height="250px"></iframe>

*Error handling with promises - the right way*

We're switching out the exception with a call of `reject()` and using the exception as the first argument. Now we can use the benefits of the automatic propagation feature. Returning a promise has the benefit that people can always handle all errors in the same consistent way.

Keep in mind, you can use the `Promise.reject()` method to create a new promise object with a rejected state and an error instead of instaniating a new promise object:

```
Promise.reject(new Error('Oh no something went wrong'));
```

Here are some brief advices for handling errors in promises:

- Don't swallow errors in your promise chain. Either handle the error from the promise directly in the chain or return the promise to the caller, so it can be handled elsewhere.
- If you're dealing with multiple promise chains use `Promise.all()` to catch any error which may occur in the chain.
- Bubble up errors the promise chain to the caller and consider throwing the exception to trigger the default unhandled error notifications (e.g. `window.onerror` or the `error` event in `process` for example). This is a convenient way to handle errors in your promise chains, especially when you have a generic error handler in your application already.

## Passing state
Sometimes you want to pass around state in your promise chain. A common example is solving relationships between two data sets for example an "n-1" relationship between multiple posts and an author. You want to fetch the post and author asychronously and render both objects when both operations are fulfilled.

Again, we're using our `fetchPost()` function to fetch the post from the endpoint. We're adding a new function called `fetchAuthor()` which fetches the author from the endpoint:

```
function fetchPost(id) {
	 id = id || 1;
    return Promise.resolve($.ajax({
    	'url': 'https://jsonplaceholder.typicode.com/posts/' + id
	}));
}

function fetchAuthor(id) {
	 id = id || 1;
    return Promise.resolve($.ajax({
    	'url': 'https://jsonplaceholder.typicode.com/users/' + id
	}));
}

fetchPost(1).then(function(post) {
	return Promise.all([ post, fetchAuthor(post.userId) ]);
}).then(function(results) {
	var data = {
    	'post': results[0],
       'user': results[1]
    };
    
    // Do something with the data...
    console.log(data)
});
```

<iframe src="https://jsfiddle.net/klarstil/hkm9o9rp/embedded/result/" frameborder="0" width="100%" height="250px"></iframe>

*Passing state around in a promise chain*

Unfortunately there's no great way to solve this problem, so you're best bet is our old friend `Promise.all()`. We're building up an array of all the objects we need and waiting for the new promises while we're keeping the existing values (e.g. state).

Calling `Promise.all()` returns a promise with the post and the author. The promise will be fulfilled once the author has been fetched from the endpoint. We have to use `Promise.all()` here because returning an array from a `then()` callback will not wait for all promises in the array to fulfill. The second argument would still have a promise of the author instead of the author's data we're looking for.

## Who will be first?

`Promise.race()` is an often overlooked feature of the [Promise API](http://www.ecma-international.org/ecma-262/6.0/#sec-promise-constructor). It provides you with the ability to race two promises against each other. This is quite useful for a timeout method for example. Our goal is to create a function `delay()` which represents the timeout delay and a method `timeout()` which is, well, the actual timeout. `timeout()`'s first argument is a promise and the second argument is the timeout time.

```
function delay(time) {
	return new Promise(function (fulfill) {
		setTimeout(fulfill, time);
	});
}

function timeout(promise, time) {
	return Promise.race([
    	promise,
        delay(time).then(function () {
        	// When the delay promise will be settled first, we're throwing an exception.
    		throw new Error('Operation timed out');
  		});
  	]);
}

timeout(fetchPost(1), 500).then(function(post) {
	console.log(post);
});
```

Whichever promise settles (fulfills or rejects) first wins the race and determines the result.

## Conclusion
Promises are an awesome utility to get rid of the "pyramid of doom" and enhance the flow control of your application. The transition from jQuery promises to the standard compatible implementation is quite simple and the additional methods in the API gives us an excellent flexibility to solve common tasks when working with asychronous operations. The automatic propagation of errors is a powerful feature. It has some pitfalls you can easily overcome when you're using the API correctly.

This blog post rounds up the JavaScript promises series in this blog.