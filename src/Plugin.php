<?php

namespace robuust\clientonline;

use robuust\clientonline\models\Settings;
use robuust\clientonline\services\ClientOnline;

/**
 * Client Online plugin.
 */
class Plugin extends \craft\base\Plugin
{
    /**
     * Initialize plugin.
     */
    public function init()
    {
        parent::init();

        // Register services
        $this->setComponents([
            'clientonline' => ClientOnline::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function createSettingsModel()
    {
        return new Settings();
    }
}
