---
title: Mutation Testing with Infection
tags:
- phpunit
- infection
- testing

categories:
- dev

authors: [tn]
github_link: blog/_posts/2018-01-11-mutation-testing-with-infection.md

---

<div class="alert alert-info">
Anyone who is just searching for the updated example source code and does not want to read the complete blog post, <a href="https://github.com/teiling88/mutation-testing">here it is.</a>
</div>

This blog post is a follow up for my mutation testing post from [24.08.2017](https://developers.shopware.com/blog/2017/08/24/mutation-testing/). Since [31.12.2017](https://github.com/humbug/humbug/commit/53730b3306efebf85bd66b6f7ec870d500f5ccbd) humbug is marked as deprecated with a link to infection as an alternative. Maks Rafalko is the author of infection and well known as [borNfreee](https://github.com/borNfreee) on github. The last released version of Infection is 0.7.0. So let us have a closer look into it.  

## Installation and Integration
I will use <a href="https://github.com/teiling88/mutation-testing/commit/78dfccf89d8b97040f5efbffa9542b18fed44c59">this</a> commit as the starting point for the next steps. If you execute infection the first time, it will guide you through a small wizard to configure infection for your application. It is not necessary to keep a special configuration command in mind. Foremost the wizard wants to configure your source directory:

```bash
Welcome to the Infection config generator

We did not find a configuration file. The following questions will help us to generate it for you.

Which source directories do you want to include (comma separated)? [src]: 
  [0] .
  [1] build
  [2] src
  [3] tests
  [4] vendor
 > 
```
After that, you can exclude folders in your source directory:

```bash
There can be situations when you want to exclude some folders from generating mutants.
You can use glob pattern (*Bundle/**/*/Tests) for them or just regular dir path.
It should be relative to the source directory.
Press <return> to stop/skip adding dirs.

Any directories to exclude from within your source directories?: 
```
Next step is to configure the timeout for each test:

```bash
Single test suite timeout in seconds [10]: 
```

At last we have to configure a path for your infection log file:

```
Where do you want to store the text log file? [infection-log.txt]: build/infection.log
```
After successfully configuration Infection is executed for the first time:

```bash
Configuration file "infection.json.dist" was created.

    ____      ____          __  _
   /  _/___  / __/__  _____/ /_(_)___  ____ 
   / // __ \/ /_/ _ \/ ___/ __/ / __ \/ __ \
 _/ // / / / __/  __/ /__/ /_/ / /_/ / / / /
/___/_/ /_/_/  \___/\___/\__/_/\____/_/ /_/

Running initial test suite...

Phpunit version: 5.7.2

   12 [============================] < 1 sec

Generate mutants...

Processing source code files: 1/1
Creating mutated files and processes: 8/8
.: killed, M: escaped, S: uncovered, E: fatal error, T: timed out

........                                             (8 / 8)

8 mutations were generated:
       8 mutants were killed
       0 mutants were not covered by tests
       0 covered mutants were not detected
       0 errors were encountered
       0 time outs were encountered

Metrics:
         Mutation Score Indicator (MSI): 100%
         Mutation Code Coverage: 100%
         Covered Code MSI: 100%

Please note that some mutants will inevitably be harmless (i.e. false positives).
```

The result page looks very similar to the humbug result page. Infection created 8 mutations which all were killed. Finally the initial configuration process is easy and the generated `infection.json.dist` file is ready for more detailed settings.

## Changes under the hood

As we learned infection is easy to setup and run. But in which way does infection differ from humbug?

* the generated mutations are based on [AST](https://en.wikipedia.org/wiki/Abstract_syntax_tree).
* more mutators are available like `Function Signature` and `Loop` 
* great performance improvements

Let us have a detailed look into some points.

## Abstract Syntax Tree

What kind of benefit gets infection from using an Abstract Syntax Tree? 

* the sourcecode is easier to maintain
* easier to write new mutators
* much easier to handle false-positives and different edge cases, e.g. deciding when mutation should be done or should not in difficult situation 

To prove this benefits I decided to compare some mutators from infection with the equivalents in humbug. So let us see the Plus mutator which is changing `+` into `-` from infection first and the mutator from humbug as second:

```php 
// https://github.com/infection/infection/blob/master/src/Mutator/Arithmetic/Plus.php

class Plus extends FunctionBodyMutator
{
    /**
     * Replaces "+" with "-"
     *
     * @param Node $node
     *
     * @return Node\Expr\BinaryOp\Minus
     */
    public function mutate(Node $node)
    {
        return new Node\Expr\BinaryOp\Minus($node->left, $node->right, $node->getAttributes());
    }
    public function shouldMutate(Node $node): bool
    {
        if (!($node instanceof Node\Expr\BinaryOp\Plus)) {
            return false;
        }
        if ($node->left instanceof Array_ && $node->right instanceof Array_) {
            return false;
        }
        return true;
    }
}
```

```php 
// https://github.com/humbug/humbug/blob/master/src/Mutator/Arithmetic/Addition.php

class Addition extends MutatorAbstract
{
    /**
     * Replace plus sign (+) with minus sign (-)
     *
     * @param array $tokens
     * @param int $index
     * @return array
     */
    public static function getMutation(array &$tokens, $index)
    {
        $tokens[$index] = '-';
    }
    /**
     * Not all additions can be mutated.
     *
     * The PHP language allows union of arrays : $var = ['foo' => true] + ['bar' => true]
     * see http://php.net/manual/en/language.operators.array.php for details.
     *
     * So for this case, we can't create a mutation.
     *
     * @param array $tokens
     * @param $index
     * @return bool
     */
    public static function mutates(array &$tokens, $index)
    {
        $t = $tokens[$index];
        if (!is_array($t) && $t == '+') {
            $tokenCount = count($tokens);
            for ($i = $index + 1; $i < $tokenCount; $i++) {
                // check for short array syntax
                if (!is_array($tokens[$i]) && $tokens[$i][0] == '[') {
                    return false;
                }
                // check for long array syntax
                if (is_array($tokens[$i]) && $tokens[$i][0] == T_ARRAY && $tokens[$i][1] == 'array') {
                    return false;
                }
                // if we're at the end of the array
                // and we didn't see any array, we
                // can probably mutate this addition
                if (!is_array($tokens[$i]) && $tokens[$i] == ';') {
                    return true;
                }
            }
            return true;
        }
        return false;
    }
}
```

As we can see the approach from infection with AST is much smaller, easier to read and understand. If you need another example to prove the benefits on your own just have a look into the FunctionCall mutator from [humbug](https://github.com/humbug/humbug/blob/1.0.0-alpha2/src/Mutator/ReturnValue/FunctionCall.php) and [infection](https://github.com/infection/infection/blob/0.2.1/src/Mutator/ReturnValue/FunctionCall.php).

## More Mutators

Infection has two additional mutator types as humbug. The first is the `Function Signature` mutator. It will
change the visibility of a method and change it to protected or private. If no error occurs it might be possible to change
the visibility to a more restricted one.

Another one is the Loop mutator. This mutation changes some special keywords within a loop. You can see a table with possible
changes below: 

 Name      | Original                      |    Mutated         
-----------|-------------------------------|--------------------
 Break_    | break;                        | continue;          
 Continue_ | continue;                     | break;             
 Foreach_  | foreach ($someVar as …);      | foreach ([] as …);

## Performance

To have a small test I used our [psh](https://github.com/shopwareLabs/psh) test suite as base for the performance measurements. First I started humbug with the psh test suite. As we can see, humbug created 156 mutations and needed overall ~ 54 seconds. 

```bash
 _  _            _
| || |_  _ _ __ | |__ _  _ __ _
| __ | || | '  \| '_ \ || / _` |
|_||_|\_,_|_|_|_|_.__/\_,_\__, |
                          |___/
Humbug 1.0-dev

Humbug running test suite to generate logs and code coverage data...

   76 [==========================================================] 4 secs

Humbug has completed the initial test run successfully.
Tests: 76 Line Coverage: 69.15%

Humbug is analysing source files...

Mutation Testing is commencing on 32 files...
(.: killed, M: escaped, S: uncovered, E: fatal error, T: timed out)

......M.....M..SSS...................M.......M..M......M.M.. |   60 (17/32)
MMM.MS.....SSM.M...T........TSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS |  120 (25/32)
SSSSSSSS.....M......MSSS...M....M.M.

156 mutations were generated:
      88 mutants were killed
      48 mutants were not covered by tests
      18 covered mutants were not detected
       0 fatal errors were encountered
       2 time outs were encountered

Metrics:
    Mutation Score Indicator (MSI): 58%
    Mutation Code Coverage: 69%
    Covered Code MSI: 83%

Time: 53.84 seconds Memory: 8.00MB
Humbug results are being logged as TEXT to: humbuglog.txt
```

Next I had to create a small script to have the possibility to take the execution time of infection. No rocket science but it does the job.

```bash 
START=`date +%s%N`

./bin/infection

END=$((`date +%s%N` - $START))

bc <<< "scale=2; $END/1000000000"
```

Let us start infection and take the time. In the first run infection needed overall ~ 42 seconds and created 223 mutations. As we can see infection creates more mutations and needs less time. Awesome :-) 

```bash
   ____      ____          __  _
   /  _/___  / __/__  _____/ /_(_)___  ____ 
   / // __ \/ /_/ _ \/ ___/ __/ / __ \/ __ \
 _/ // / / / __/  __/ /__/ /_/ / /_/ / / / /
/___/_/ /_/_/  \___/\___/\__/_/\____/_/ /_/
 
Running initial test suite...

Phpunit version: 6.5.5

   82 [============================] 2 secs

Generate mutants...

Processing source code files: 32/32
Creating mutated files and processes: 223/223
.: killed, M: escaped, S: uncovered, E: fatal error, T: timed out

....TMM.....ME.ES..S............................E.   ( 50 / 223)
................M....M.M..M....EE...E....MMM.SM..M   (100 / 223)
MMM.M.SS............SSSM...T......TSSSSSSSSSSSSSSS   (150 / 223)
SSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS....S...M..   (200 / 223)
.......M......M.M......                              (223 / 223)

223 mutations were generated:
     130 mutants were killed
      63 mutants were not covered by tests
      21 covered mutants were not detected
       6 errors were encountered
       3 time outs were encountered

Metrics:
         Mutation Score Indicator (MSI): 62%
         Mutation Code Coverage: 72%
         Covered Code MSI: 87%

Please note that some mutants will inevitably be harmless (i.e. false positives).
42.33    
```

At last we should test the threading option of infection. Be aware this option can give you many false-positives results if your tests depends on each other or use a non stateless database for testing purpose. I started infection with 4 threads and the result is amazing. Instead of 42 Seconds infection needs only 19 seconds to execute the whole mutation stuff.

```bash
    ____      ____          __  _
   /  _/___  / __/__  _____/ /_(_)___  ____ 
   / // __ \/ /_/ _ \/ ___/ __/ / __ \/ __ \
 _/ // / / / __/  __/ /__/ /_/ / /_/ / / / /
/___/_/ /_/_/  \___/\___/\__/_/\____/_/ /_/
 
Running initial test suite...

Phpunit version: 6.5.5

   80 [============================] 2 secs

Generate mutants...

Processing source code files: 32/32
Creating mutated files and processes: 223/223
.: killed, M: escaped, S: uncovered, E: fatal error, T: timed out

....M.M....EESM.S..............................E..   ( 50 / 223)
................M...M.M...M...EE..E.....MMSM.M..MM   (100 / 223)
M..SSM...........SSS..M.........SSSSSSSSSSSSSSSSSS   (150 / 223)
SSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS....S...M.....   (200 / 223)
....M......M.M......TTT                              (223 / 223)

223 mutations were generated:
     131 mutants were killed
      63 mutants were not covered by tests
      20 covered mutants were not detected
       6 errors were encountered
       3 time outs were encountered

Metrics:
         Mutation Score Indicator (MSI): 63%
         Mutation Code Coverage: 72%
         Covered Code MSI: 88%

Please note that some mutants will inevitably be harmless (i.e. false positives).
19.11
```

mutation framework       | created mutations | execution time | processed mutations per second
-------------------------|-------------------|----------------|--------------------------------
humbug                   | 156               | 54 seconds     |  2.88 mutations
infection                | 223               | 42 seconds     |  5.30 mutations
infection with 4 threads | 223               | 19 seconds     | 11.74 mutations

## Conclusion
Compared to Humbug, Infection does a lot of things differently. I like the way, infection solves the mutation challenges. I hope this tool will be still maintained in the future and we will see some new features. Maybe in the future infection won't have to execute the whole test suite before doing the mutation stuff. This would be another great performance improvement. :-)

If you are interested in the updated source code, it can be found [here](https://github.com/teiling88/mutation-testing). 
