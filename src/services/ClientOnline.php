<?php

namespace robuust\clientonline\services;

use Craft;
use craft\elements\Entry;
use craft\helpers\UrlHelper;
use robuust\clientonline\Plugin;
use yii\base\Component;

/**
 * ClientOnline service.
 */
class ClientOnline extends Component
{
    /**
     * @var string
     */
    const URL = 'https://www.clientonline.nl/co2_news/news.php5';

    /**
     * @var Settings
     */
    protected $settings;

    /**
     * @var Sections
     */
    protected $sections;

    /**
     * @var Section
     */
    protected $section;

    /**
     * @var EntryType
     */
    protected $entryType;

    /**
     * Initialize service.
     */
    public function init()
    {
        $this->settings = Plugin::getInstance()->getSettings();
        $this->sections = Craft::$app->getSections();

        $this->section = $this->sections->getSectionByHandle($this->settings->sectionHandle);
        list($this->entryType) = $this->sections->getEntryTypesByHandle($this->settings->entryTypeHandle);
    }

    /**
     * Get client online feed.
     *
     * @return array
     */
    public function getFeed(): array
    {
        // Create feed url
        $url = $this->getFeedUrl();

        // Get feed items
        $items = Craft::$app->getFeeds()->getFeedItems($url);

        // Inject feed images and rewrite urls
        $items = array_map(function ($item) {
            $url = UrlHelper::urlWithScheme($item['permalink'], 'https');

            // Get feed image
            if (preg_match('/<img(.*?)src="(.*?)"/s', file_get_contents($url), $matches)) {
                $item['image'] = $matches[2];
            }

            // Get article id
            if (preg_match('/article_id=(.*?)$/s', $url, $matches)) {
                $item['article_id'] = $matches[1];
            }

            return $item;
        }, $items);

        return $items;
    }

    /**
     * Import item.
     *
     * @param array $item
     *
     * @return bool
     */
    public function importItem(array $item): bool
    {
        $query = Entry::find()->section($this->section);
        $query[$this->settings->articleIdField] = $item['article_id'];
        $entry = $query->anyStatus()->one();

        if (!$entry) {
            $entry = new Entry();
            $entry->sectionId = $this->section->id;
            $entry->typeId = $this->entryType->id;
            $entry->enabled = true;
            $entry->title = $item['title'];
            $entry->postDate = $item['date'];
            $entry->setFieldValues([
                $this->settings->articleIdField => $item['article_id'],
                $this->settings->imageField => ['data' => [$item['image']], 'filename' => [$item['article_id'].'.jpg']],
                $this->settings->textField => $item['content'],
            ]);

            return Craft::$app->getElements()->saveElement($entry);
        }

        return false;
    }

    /**
     * Get feed url.
     *
     * @return string
     */
    protected function getFeedUrl(): string
    {
        return UrlHelper::urlWithParams(static::URL, [
            'office_id' => $this->settings->office_id,
            'suboffice_id' => $this->settings->suboffice_id,
            'co2_news_task' => 'rss',
            'full_articles' => true,
        ]);
    }
}
