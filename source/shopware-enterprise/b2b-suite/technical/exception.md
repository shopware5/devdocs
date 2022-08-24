---
layout: default
title: Exception
github_link: shopware-enterprise/b2b-suite/technical/exception.md
indexed: true
menu_title: Exception
menu_order: 16
menu_style: numeric
menu_chapter: true
group: Shopware Enterprise
subgroup: B2B-Suite
subsubgroup: Technical Documentation
---

## Translatable Exception

To show the customer a translated exception message in the shopware error controller, the exception must implement the `B2BTranslatableException` Interface.

```php
...

class NotAllowedRecordException extends \DomainException implements B2BTranslatableException
{
    /**
     * @var string
     */
    private $translationMessage;

    /**
     * @var array
     */
    private $translationParams;

    /**
     * @param string $message
     * @param string $translationMessage
     * @param array $translationParams
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(
        $message = '',
        string $translationMessage = '',
        array $translationParams = [],
        $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);

        $this->translationMessage = $translationMessage;
        $this->translationParams = $translationParams;
    }

    /**
     * {@inheritdoc}
     */
    public function getTranslationMessage(): string
    {
        return $this->translationMessage;
    }

    /**
     * {@inheritdoc}
     */
    public function getTranslationParams(): array
    {
        return $this->translationParams;
    }
}
```
The snippet key is a modified `translationMessage`.

```php
preg_replace('([^a-zA-Z0-9]+)', '', ucwords($exception->getTranslationMessage()))
```

Variables in the message will be replaced by the `string_replace()` method.
The identifiers are the keys of the `translationParams` array. 
