Client Online plugin for Craft
=================

Plugin that allows you to import ClientOnline RSS feeds.

## Requirements

This plugin requires Craft CMS 3.1.0 or later.

## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Then tell Composer to load the plugin:

        composer require robuust/craft-clientonline

3. In the Control Panel, go to Settings → Plugins and click the “Install” button for Client Online.

## Config

Create a file called `clientonline.php` in you Craft config folder with the following contents:

```php
<?php

return [
    // General
    'office_id' => 9999, // YOUR OFFICE_ID
    // Section
    'sectionHandle' => 'YOUR_NEWS_SECTION_HANDLE',
    'entryTypeHandle' => 'YOUR_NEWS_ENTRY_TYPE_HANDLE',
    // Fields
    'articleIdField' => 'YOUR_NEWS_ARTICLE_ID_FIELD', // PlainText
    'imageField' => 'YOUR_NEWS_IMAGE_FIELD', // Asset
    'textField' => 'YOUR_NEWS_TEXT_FIELD', // Redactor
];

```

## Usage

Run `craft clientonline/import` on the CLI to import the newest items.
