---
title: Inspecting PDO queries
tags:
    - inspect pdo
    - parse sql query
    - sql injection detection

categories:
- dev
indexed: false
github_link: blog/_posts/2015-11-24-pdo-inspector.md

authors: [dn]
---
This blog post will describe, how to detect (potential) SQL queries automatically by parsing and analyzing the queries
which hit the PDO connection.

# Motivation
The [Open Web Application Security Project (OWAP)](https://www.owasp.org/) defines (SQL) injections as
[one of the top ten security risks in the web](https://www.owasp.org/index.php/Top_10_2013-A1-Injection).
One of the main reasons for this is the fact, that SQL injections can be tested / used with minimal effort and have
an enormous impact on the compromised database; starting from defacing web pages, copying user data, stealing credit card
numbers or even whole identities, the scenarios of SQL injections are hard to overestimate.

# What is an SQL injection and what to do about it?
Generally speaking a SQL injection is an attack, where the attacker exploits a badly prepared SQL statement, by passing
input data, that will allow the attacker to execute arbitrary SQL statements.

A simple example would be this:

`SELECT user_id FROM user WHERE name='$_GET['name']'`

This will the attacker to provide a HTTP query string like this:

`?name=peter; DROP TABLE user; --`

which will then result in a query like this:

`SELECT user_id FROM user WHERE name='peter; DROP TABLE user; --'`

In this case the database will then first execute the (intended) SELECT-statement and then the injected `DROP TABLE user`
 statement and therefore delete the user table. Any remaining parts of the original query (in this case `'`) will be marked
 as comment, so that the database will not complain at all.

The actual attacking scenarios are quite broat: From dropping tables to gaining admin privileges or even dumping the database
in a file that will then be downloaded later.

A common technique to prevent SQL injections are [prepared statements](https://en.wikipedia.org/wiki/Prepared_statement).
These will not directly introduce values into the SQL query, but use placeholders in the query, which are later resolved:

`SELECT user_id FROM user WHERE name=?`

This way the DBMS can safely populate the SQL query, no matter how it looks like. A malicious query string like `peter; DROP TABLE user; --`
could then still be properly escaped, and would not be executed as another query.

But even though there are techniques to prevent SQL injections, attack vectors making use of them are quite widespread.
 Possible reasons for this might be legacy applications, missing convenience of prepared statements in some cases, missing information
 of developers or the high degree of abstraction, that many applications use when working with databases.


## Is my application safe?
All this considerations bring up the question, how to make sure, that an existing peace of software does not contain any SQL injections. By the
very nature of SQL queries, statically finding and analyzing those queries in an application is quite hard and error-prone,
as the queries might be composed during runtime and database connection objects might be hidden by variable names
or implementation details of the application.

### Pentests
A common approach are so called [penetration test](https://en.wikipedia.org/wiki/Penetration_test) which (in some cases)
might use automated tools, to black-box scan a given application for errors and SQL injections. Such a test might try
to crawl all pages of a web application, change input parameters and look for conspicuous responses from the server.
Such a "conspicuous response" might be an error message, a differing HTTP response code, changed sizes of the response
or changed response times. As you might imagine, this is a time-consuming task, as the crawler will need to retry any single
possible call with multiple values and check for all the possible criteria mentioned above.

### Analyzing queries
Discussing ways to detect security issues automatically with Florian Ressel, a student from munich currently writing
his bachelor thesis about web application security, I wondered if it was possible to inspect queries on the database connection level
and find possible SQL injections by actually parsing and analyzing the queries. As I didn't find existing libraries
 doing so, I implemented a prototype and after a few evenings, I think the answer is "yes".

## SQL Query inspector
The SQL Query inspector is a simple library, which decorates the PDO object and parses all SQL queries, that are about
to hit the SQL server. At this point, the SQL queries are fully assembled and either plain text SQL queries or prepared statements.

Using the [PHP SQL parser from Justin Swanhart](https://github.com/greenlion/PHP-SQL-Parser), the library is able to find
query parts, which contain hardcoded constant values in the query. This can be legit values like

`SELECT * FROM test_table WHERE id=1`

but might also be possible SQL injections like

`SELECT * FROM test_table WHERE id={$_GET['id']}`

So this scenario will produce false positives for hardcoded values - but also catch real SQL injection vulnerabilities.

Using `debug_backtrace` of PHP and routing information of either the `$_SERVER` super global or a application specific handler,
the tool will also be able to provide you the concrete route / path of any possible SQL injection as well as the concrete stack trace and even
 the source code of the method / function that triggered that query.

For example the unit tests of the SQL query inspector library produces this log:

```
(
    [route] =>
    [problems] => Array
        (
            [0] => Array
                (
                    [expr_type] => const
                    [base_expr] => "Test"
                    [sub_tree] =>
                )

        )

    [code] => Array
        (
            [40] =>     public function testThis()
            [41] =>     {
            [42] =>         $sql = <<<EOF
            [43] => CREATE TABLE test
            [44] => (
            [45] => id int,
            [46] => name varchar(255)
            [47] => );
            [48] => EOF;
            [49] =>
            [50] =>         $this->getPDO()->prepare($sql)->execute();
            [51] =>
            [52] =>         $sql = 'INSERT INTO "test" (`name`) VALUES("Test")';
            [!!!] =>         $this->getPDO()->prepare($sql)->execute();
            [54] =>         $this->assertEquals($sql, $this->storage->getDocument('problem', '42dec3f3d68a119b4faef11cd2b6afe3')['sql']);
            [55] =>
            [56] =>     }
        )

    [sql] => INSERT INTO "test" (`name`) VALUES("Test")
    [trace] => stdClass Object
        (
            [0] => Test: testThis:
            [1] => ReflectionMethod: invokeArgs:  963
            [2] => PHPUnit_Framework_TestCase: runTest:  835
            [3] => PHPUnit_Framework_TestCase: runBare:  643
            [4] => PHPUnit_Framework_TestResult: run:  771
            [5] => PHPUnit_Framework_TestCase: run:  703
            [6] => PHPUnit_Framework_TestSuite: run:  703
            [7] => PHPUnit_Framework_TestSuite: run:  423
            [8] => PHPUnit_TextUI_TestRunner: doRun:  186
            [9] => PHPUnit_TextUI_Command: run:  138
            [10] => PHPUnit_TextUI_Command: main:  42
        )

    [normalized] => Array
        (
            [INSERT] => Array
                (
                    [0] => Array
                        (
                            [expr_type] => reserved
                            [base_expr] => INTO
                        )

                    [1] => Array
                        (
                            [expr_type] => table
                            [table] => "test"
                            [no_quotes] => Array
                                (
                                    [delim] =>
                                    [parts] => Array
                                        (
                                            [0] => test
                                        )

                                )

                            [alias] =>
                            [base_expr] => "test"
                        )

                    [2] => Array
                        (
                            [expr_type] => column-list
                            [base_expr] => (`name`)
                            [sub_tree] => Array
                                (
                                    [0] => Array
                                        (
                                            [expr_type] => colref
                                            [base_expr] => `name`
                                            [no_quotes] => Array
                                                (
                                                    [delim] =>
                                                    [parts] => Array
                                                        (
                                                            [0] => name
                                                        )

                                                )

                                        )

                                )

                        )

                )

            [VALUES] => Array
                (
                    [0] => Array
                        (
                            [expr_type] => record
                            [base_expr] => ("Test")
                            [data] => Array
                                (
                                    [0] => Array
                                        (
                                            [expr_type] => const
                                            [base_expr] => NORMALIZED
                                            [sub_tree] =>
                                        )

                                )

                            [delim] =>
                        )

                )

        )

)

```

As you can see, the following info is available:


* `route`: The route / request, that triggered that query - empty in this case, as it is a unit test
* `problems`: The list of the static values, in this case the hardcoded "Test" string was detected
* `code`: The code of the function, that triggered the problematic query. As you can see, the relevant is highlighted using
exclamation marks as key
* `sql`: The SQL being executed
* `trace`: The trace of the SQL - so you can tell, which file / which line executed it
* `normalized`: The parsed and normalized SQL query - it is normalized so that the same query with other values will not
be registered as a new incident

This way reviewing the generated result is quite easy, as the generated logs provide all information you need, without
having to re-check the original source code.

## In-detail: How does it work?

### Half the battle: Decorating the PDO connection:
As mentioned before, statically analyzing all SQL queries from an application is hard, as the queries might be assembled
dynamically during runtime. But there is another point to address: The database connection itself: As every SQL query
is going to hit the database server sooner or later, one could try to catch the queries there - after they have been assembled
by the application and before they hit the SQL server.
In modern PHP application this will the PDO connection in most cases. The PDO object in PHP has three functions we
might be interested in:

```
class Pdo
{
	/**
	 * Executes an SQL statement, returning a result set as a PDOStatement object
	 */
	public function query ($statement, $mode = PDO::ATTR_DEFAULT_FETCH_MODE, $arg3 = null) {}

	/**
	 * Execute an SQL statement and return the number of affected rows
	 */
	public function exec ($statement) {}

	/**
	 * Prepares a statement for execution and returns a statement object
	 */
    public function prepare ($statement, array $driver_options = array()) {}
}
```

These three methods will either directly execute a query (e.g. `DELETE * FROM test`), query a result (e.g. `SELECT * FROM test`)
or prepare a SQL query for later execution (e.g `SELECT * FROM test WHERE id=?`). Now we can write a simple inspector,
that will behave just like the PDO object but analyze any query before it passes it to the database server:

```
class PDOInspectionDecorator extends \PDO
{
    #
    # Overrides of the original PDO object
    # in order to inspect the queries
    #

    public function prepare($statement, $options = array())
    {
        $this->inspector->inspect($statement);
        return $this->innerPDO->prepare($statement, $options);
    }


    public function query()
    {
        // remove empty constructor params list if it exists
        $args = func_get_args();

        $this->inspector->inspect($args[0]);

        return call_user_func_array(array($this->innerPDO, 'query'), $args);
    }

    public function exec($statement)
    {
        $this->inspector->inspect($statement);
        return $this->innerPDO->exec($statement);
    }
}
```

As you can see, all overridden functions will pass the SQL query to the "sql query inspector" and then execute it on the
decorated PDO object ("innerPDO"). This way the application will still run as always - but behind the scenes, every single query
is analyzed for a possible SQL injection:

### The second half: Inspecting the result
The `\Dnoegel\DatabaseInspection\SqlProblemInspector` will inspect every SQL by parsing it and checking for constant
values in the query. If the normalized and hashed query was whitelisted, no further logic will be executed.
If possible injections where detected, the script will get the routing information (`\Dnoegel\DatabaseInspection\RouteProvider\RouteProvider`)
as well as a debug trace + source code of the relevant file (`\Dnoegel\DatabaseInspection\Trace\Trace`). All this
information will then be stored using a simple storage interface (`\Dnoegel\DatabaseInspection\Storage\Storage`) which
defaults to a json storage. Theoretically this can easy be changed to store the information e.g. in a database or somewhere
else.

### Possible improvements
There are several things to consider for this library:

First of all, parsing every SQL query, getting debug information and writing this info to hard disc has a negative effect
on the performance of the application. This might be ok while pentesting a setup but is not ideal for live systems. I think
this is a reasonable trade of, as live systems with e.g. caching enabled are not a reasonable source for proper results.
If this is required anyway, one could easily alter the script to just store all the queries and do the parsing / analyzing
later.

Secondly the amount of false positives might be quite high. One the one hand, one could discuss, if "hardcoded" values
in SQL queries are necessary at all - why not using prepared statements for those, too? On the other hand, having
false positives might be still reasonable, as the debug format makes it easy to tell those queries apart from problematic
queries. After whitelisting those queries once, they shouldn't appear again, so I think its worth the effort.

### Can this tool be used to actually prevent SQL injection attacks?
This tool shouldn't be used in an "input filter" like manner: It is a QA / developer tool, not a security layer. Limitations
are:

* Performance: Parsing and analyzing the queries is quite costly
* Security: Even though SQL injections will mostly derive from not-properly prepared statements, an actual SQL injection
attack does not necessarily have a constant query part.

For these reasons, this tool should ony be considered a developer analytics tool and is by no mean a "security layer" of
whatsoever kind.

## Other approaches
As mentioned before, there are other approaches to detect SQL injections. Since 2014 there is a [PHP RFC by Wietse Venema](https://wiki.php.net/rfc/taint)
who suggests "tainting" of PHP variables. This way PHP would internally store, how a string was composed.
A string created completely from within the application might be safe, a string created using user input from HTTP query variables,
shell arguments or similar would be marked as "tainted". As soon as such a "tainted" string would be e.g. printed out
to the template or passed to the database connection, PHP could then raise an exception, as a the string might cause injection
vulnerabilities.
Furthermore the RFC proposes to let certain PHP functions remove such taint flags: So e.g. `htmlspecialchars` might
 remove the taint for HTML output, `mysql_real_escape_string` for database connections and `escapeshellcmd` for shell
 output.

Even though this RFC did not hit the PHP core any perhaps never will - I think its not only a nice concept, but also
very informative, as you actually should consider any string "tainted", which was generated from user input. So it might
also help you to understand injections attacks better.

Another interesting library is [php-reaper](https://github.com/emanuil/php-reaper) which will parse your PHP source code
for tainted SQL queries. Currently its bound to AdoDB and also produces a lot of false positives - but I think its also
a nice approach to find possible SQL injections statically.

## Download
You can find the query inspector on my [github repo](https://github.com/dnoegel/pdo-inspector)