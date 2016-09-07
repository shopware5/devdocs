<?php

namespace Shopware\SwagDynamicEmotion\Components;

use Doctrine\DBAL\Connection;

/**
 * Class CustomComponents knows all custom emotion components this plugin creates - so its easier to query them
 * @package Shopware\SwagDynamicEmotion\Components
 */
class CustomComponents
{
    private $pluginId;
    private $components;
    /**
     * @var Connection
     */
    private $connection;

    public function __construct($pluginId, Connection $connection)
    {
        $this->pluginId = $pluginId;
        $this->connection = $connection;
    }

    /**
     * Check if the given $cls belongs to a custom component.
     *
     * @param $cls
     * @return bool
     */
    public function isCustomComponents($cls)
    {
        return isset($this->getCustomComponents()[$cls]);
    }

    /**
     * Get all custom components of the plugin. Is cached per request
     *
     * @return mixed
     */
    public function getCustomComponents()
    {
        if ($this->components) {
            return $this->components;
        }
        $components =  $this->connection->fetchAll('SELECT * FROM `s_library_component` WHERE pluginID = ?', [$this->pluginId]);
        foreach ($components as $component) {
            $this->components[$component['cls']] = $component;
        }

        return $this->components;
    }
}