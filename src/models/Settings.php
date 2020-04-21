<?php

namespace robuust\clientonline\models;

use craft\base\Model;

/**
 * Settings model.
 */
class Settings extends Model
{
    /**
     * @var int
     */
    public $office_id;

    /**
     * @var int
     */
    public $suboffice_id;

    /**
     * @var string
     */
    public $sectionHandle;

    /**
     * @var string
     */
    public $entryTypeHandle;

    /**
     * @var string
     */
    public $articleIdField;

    /**
     * @var string
     */
    public $imageField;

    /**
     * @var string
     */
    public $textField;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['office_id', 'suboffice_id', 'sectionHandle', 'entryTypeHandle', 'articleIdField', 'imageField', 'textField'], 'required'],
        ];
    }
}
