---
title: An anecdote about pair programming
tags:
- Agile Development
- Extreme programming
- Development techniques

categories: 
- dev

authors: [cr]
github_link: /blog/_posts/2018-06-07-pair-programming.md

---

When we decided to rebuild a central piece of our Merchant Integration Architecture, I was assigned to write a new component, that handles asynchronous function calls through event queueing. 
So from the get go it was assumed that this was quite a large task. After having completed a rough prototype to interact with our queue service, I hit a problem with deciding how to proceed.
There were many paths that needed to be worked on: error handling, at least once execution, backing up the queue to our database, et cetera.
So I asked for ideas during our daily stand-up meeting, our team lead suggested to team up with a colleague who's out of work to try [pair programming](http://www.extremeprogramming.org/rules/pair.html). 

## Parallel Thinking
At first we both had doubts about the benefits of sitting at the same computer, working at the same task and fighting over the keyboard.
But we quickly realized that the thought process was streamlined even though the typing speed remained the same.

It's a well known fact, that in any moderately complex project or component there are many possible paths to think about. Generally this isn't a problem. 
The developer figures out a [happy path](https://en.wikipedia.org/wiki/Happy_path) and thinks about the obvious edge cases, then they write down this path as code and add handling for the edge cases. 

Here the first advantage of pair programming takes effect: any two people don't exactly think the same. For example: 
I tried to handle errors by writing a time stamp to the database and having an external script run periodically to re-queue eligible events. But then my coworker came up with the idea to simply use the build in delay ability of our 
queue service. This is the strength of _parallel thinking_. While I was occupied thinking how to structure my code and where to put it, he had a whole different path in mind.
And the synthesis of these two ideas turned out to be the ideal structure for our use case.   

## It's not only the lines of code that count
"Figuring out solutions together is nice but a single dev could have done it by themselves.", some of you might say. 

But coming up with solutions, arguably the most fun part of the job, is by far not the only time sink developing software. Another significant source of delays and frustration are bugs.
And im not strictly talking about the ones being found by QA or customers, that need intensive research and tracing to fix. I mean those tiny bugs that keep the tests from succeeding and the dev from continuing their work.

Here pair programming helps out again. Not only can two pairs of eyes see more than one, also two brains can hold more context than one. But what is context in this case? 

By context I mean the mental picture a developer has about the code while working on it. It consists of: 
- Landmarks (central & important pieces of code)
- Call paths leading into and out of the code
- Well known pitfalls
- Limitations (real or imagined)

This picture may not be accurate but it's the framework in which the dev actually makes changes to the code.

Having two people keeping track of and building their context, instead of one, leads to many otherwise hard to find bugs being found in matter of minutes sometimes even instantly. That might sound overblown but I lost track of 
how many time as soon a local test failed one of us went "Ahh that's the problem" and fixed something that was hard to see, like this:
```php
class Foo {

    /**
     * @var string
    **/
    private $prefix;

    /**
     * @var array
    **/
    private $config;

    /**
     * @var SomeService
    **/
    private $someService;

    /**
     * @var array
    **/
    private $stringVariations;

    public function __construct(array $config, SomeService $someService, string $prefix, string $str) 
    {
        $this->config = $config;
        $this->stringVariations = $this->createStringVariations($str);
        $this->someService = $someService;
        $this->prefix = $prefix;
    }

    private function createStringVariations(string $str): array
    {
       $arr = [];
       for($i = 1; i< 5; $i++) {
           
           $arr[] = $this->prefix . $str . $i;
       }
       
       return $arr;
    }
}
```

A problem like this caused really weird behavior locally and in the failing tests, but my coworker spotted it right away:

The lines `$this->stringVariations = $this->createStringVariations($str);` and
`$this->prefix = $prefix;` simply had to be swapped.

This bug was an artifact left over from a round of refactoring that simply was not present in my mind because I worked on the `createStringVariations` method before running the tests.

These things make pair programming itself enjoyable and productive for the developers actively participating. But lets switch gears and talk about the effects on the team. 

## Islands made of knowledge
As is often the case every member of our team knows some parts of the project better than others and this is fine. But when this specialisation develops too far, it leads to pretty big problems.

In the extreme case the [bus factor](https://en.wikipedia.org/wiki/Bus_factor) drops to 1 and should somebody be absent no progress can be made.
Even before the problem escalates that much, it will reduce the ability of the team to spread the work load properly.
Because not every team member can handle every task, some team members will inevitably get more work assigned than others. This can stall progress while leaving a part of the team bored.

Pair programming prevents this by simply involving two developers. The effects of that are obvious. My coworker and I both know the component we wrote well, and already briefed other team members on it.

## Logistics & Agility
Now for something that's well known: Most agile developers are not writing code nonstop through their workday.

Of course this is not a bad thing, great ideas can spring from meetings and team performance is best evaluated through dialogue between its members.

Nonetheless meetings are not necessarily relevant to the current task of the developer and these meetings therefore seem to stop progress for their duration.
At least in our case there were quite a few days when either I or my coworker were held up in meetings or one of us had a day off.

Regardless we were able to keep the pace up, because the other one of us was still free to work on the task.
This was one of the really big speedups that pair programming gave to us.

# Epilogue
Once we finished the component feedback from the team was very positive.
Together we managed to write a core component that performs well and is easy to adapt to changes.
Even though it took two developers 3 weeks to finish a task that would have taken 4 weeks by a single one   .

It's not only the result that convinced us to use pair programming in the future.
It also felt very productive and the steady progress throughout the development process satisfied the whole team.

Although this post describes the success we had with pair programming, pair programming should be seen for what it is: just another technique to help you and your team.
It may not help your team as much as it helped us. It may not be needed for the size of tasks you regularly encounter. And you may not be comfortable with taking the backseat when writing code.
Due to these very valid concerns, I think every team should judge pair programming themselves.

Still don't be put off at first and give a try the next time you are in a tight spot.
