<?php

namespace robuust\clientonline\console\controllers;

use craft\console\Controller;

/**
 * Import controller.
 */
class ImportController extends Controller
{
    /**
     * Import action.
     */
    public function actionIndex()
    {
        $items = $this->module->clientonline->getFeed();
        $this->stdout('items found: '.count($items)."\n");

        // Import items
        $count = 0;
        foreach ($items as $item) {
            if ($this->module->clientonline->importItem($item)) {
                ++$count;
            }
        }
        $this->stdout("items imported: {$count}\n");
    }
}
