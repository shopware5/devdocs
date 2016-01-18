---
title: On interfaces traits decorators
tags:
    - interface
    - trait
    - decorator
    - extensibility

categories:
- dev

authors: [bc]
---

Limits of Decorator based Extensibility

Interfaces
Design by Contract


```php
<?php

/**
 * Inflector interface used to convert the casing of words
 * Borrowed from \Guzzle\Inflection\InflectorInterface
 */
interface InflectorInterface
{
    /**
     * Converts strings from camel case to snake case (e.g. CamelCase camel_case).
     */
    public function snake($word);

    /**
     * Converts strings from snake_case to upper CamelCase
     */
    public function camel($word);
}
```

```php
/**
 * Default inflection implementation
 * Borrowed from \Guzzle\Inflection\Inflector
 */
class Inflector implements InflectorInterface
{
    public function snake($word)
    {
        return ctype_lower($word) ? $word : strtolower(preg_replace('/(.)([A-Z])/', "$1_$2", $word));
    }

    public function camel($word)
    {
        return str_replace(' ', '', ucwords(strtr($word, '_-', '  ')));
    }
}

```

```php
trait InflectorDecoratorTrait
{
    /**
     * @return InflectorInterface
     */
    abstract protected function getDecorated();

    public function snake($word)
    {
        return $this->getDecorated()->camel($word);
    }

    public function camel($word)
    {
        return $this->getDecorated()->camel($word);
    }
}

```

```php
class PrependInflector implements InflectorInterface
{
    use InflectorDecoratorTrait;

    private $decorated;

    private $word;

    public function __construct(InflectorInterface $decorated, $word)
    {
        $this->decorated = $decorated;
        $this->word = $word;
    }

    /**
     * @return InflectorInterface
     */
    protected function getDecorated()
    {
        return $this->decorated;
    }

    public function snake($word)
    {
        return $this->word . $this->getDecorated()->snake($word);
    }
}
```

```php
class PrependInflector implements InflectorInterface
{
    private $decorated;

    private $word;

    public function __construct(InflectorInterface $decorated, $word)
    {
        $this->decorated = $decorated;
        $this->word = $word;
    }

    public function __call($method, $args)
    {
        return call_user_func_array(array($this->decorated, $method), $args);
    }

    public function snake($word)
    {
        return $this->word . $this->getDecorated()->snake($word);
    }
}
```

```php
$inflector = new Inflector();
$inflector = new PrependInflector($inflector, "prepended");

assert($inflector->snake("CamelCase") === "prependedcamel_case");
assert($inflector->camel("snake_case") === "SnakeCase");
```
