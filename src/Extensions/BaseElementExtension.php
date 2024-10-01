<?php

namespace SilverStripe\StartupTheme;

use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\DataExtension;

class BaseElementExtension extends DataExtension
{
    private static array $db = [
        'BlockBackgroundColor' => 'Varchar(255)',
    ];

    private static $blockBackgroundColors = [
        'white' => 'White',
        'pale-grey' => 'Grey',
    ];

    /**
     * @inheritDoc
     */
    public function updateCMSFields(FieldList $fields): void
    {
        $fields->addFieldsToTab(
            'Root.Settings',
            [
                DropdownField::create(
                    'BlockBackgroundColor',
                    'Background color',
                    static::$blockBackgroundColors
                )
            ]
        );
    }

    public function getBlockName(): string
    {
        return strtolower(str_replace(' ', '-', strtolower($this->owner->singular_name())));
    }

    public function getSectionColorClass(): string
    {
        return sprintf('section--color-%s',  $this->owner->BlockBackgroundColor ?? 'white');
    }

}
