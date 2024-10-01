<?php

namespace SilverStripe\StartupTheme;

use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\DataExtension;

class ElementContentExtension extends DataExtension
{
    private static array $db = [
        'BlockNarrowContentWidth' => 'Varchar(255)',
    ];

    /**
     * @inheritDoc
     */
    public function updateCMSFields(FieldList $fields): void
    {
        $fields->addFieldsToTab(
            'Root.Settings',
            [
                CheckboxField::create(
                    'BlockNarrowContentWidth',
                    'Narrow content width?'
                ),
            ]
        );
    }

}
