<?php

namespace robuust\clientonline;

use Craft;
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

        // Redirects
        $co2 = Craft::$app->getRequest()->getQueryParam('co2_news_task', false);
        if ($co2) {
            $articleId = Craft::$app->getRequest()->getQueryParam('article_id');
            $article = $this->clientonline->getEntry($articleId);
            if ($article) {
                Craft::$app->getResponse()->redirect($article->uri, 301)->send();
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function createSettingsModel()
    {
        return new Settings();
    }
}
