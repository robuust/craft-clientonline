<?php

namespace robuust\clientonline\services;

use Craft;
use craft\elements\Entry;
use craft\helpers\Json;
use DateTime;
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
    const URL = 'https://www.sra.nl/api/news/get';

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
        // Get feed items
        $request = Craft::createGuzzleClient()->get(static::URL, [
            'query' => [
                'officeid' => $this->settings->office_id,
                'full' => 'true',
            ],
        ]);

        return Json::decode((string) $request->getBody());
    }

    /**
     * Get entry by article id.
     *
     * @param string $articleId
     *
     * @return Entry|null
     */
    public function getEntry(string $articleId): ?Entry
    {
        $query = Entry::find()->section($this->section);
        $query[$this->settings->articleIdField] = $articleId;

        return $query->anyStatus()->one();
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
        $entry = $this->getEntry($item['ID']);

        if (!$entry) {
            $image = base64_encode(file_get_contents($item['NewsItem']['ImageUrl']));

            $entry = new Entry();
            $entry->sectionId = $this->section->id;
            $entry->typeId = $this->entryType->id;
            $entry->enabled = true;
            $entry->title = $item['Title'];
            $entry->postDate = new DateTime($item['Date']);
            $entry->setFieldValues([
                $this->settings->articleIdField => $item['ID'],
                $this->settings->imageField => ['data' => ['data:image/jpeg;base64,'.$image], 'filename' => [$item['ID'].'.jpg']],
                $this->settings->textField => $item['NewsItem']['PublicText'],
            ]);

            return Craft::$app->getElements()->saveElement($entry);
        }

        return false;
    }
}
