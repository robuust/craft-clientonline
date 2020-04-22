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
        $request = Craft::$app->getRequest();
        if (!$request->getIsConsoleRequest() && $request->getQueryParam('co2_news_task', false)) {
            $articleId = $request->getQueryParam('article_id');
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
