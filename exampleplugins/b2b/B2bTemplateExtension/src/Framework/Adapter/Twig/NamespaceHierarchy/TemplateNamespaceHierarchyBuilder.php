<?php declare(strict_types=1);

namespace B2bTemplateExtension\Framework\Adapter\Twig\NamespaceHierarchy;

use Shopware\Core\Framework\Adapter\Twig\NamespaceHierarchy\TemplateNamespaceHierarchyBuilderInterface;
use function array_merge;

class TemplateNamespaceHierarchyBuilder implements TemplateNamespaceHierarchyBuilderInterface
{
    public function buildNamespaceHierarchy(array $namespaceHierarchy): array
    {
        return array_merge($namespaceHierarchy, ['B2bTemplateExtension']);
    }
}
