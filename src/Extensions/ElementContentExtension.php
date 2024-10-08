<?php

namespace SilverStripe\StartupTheme;

use SilverStripe\Core\Extension;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\FieldList;

class ElementContentExtension extends Extension
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
