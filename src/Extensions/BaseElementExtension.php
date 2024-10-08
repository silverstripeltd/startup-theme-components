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

    /**
     * Helper method to provide CSS class for block name
     */
    public function getBlockNameClass(): string
    {
        return strtolower(str_replace(' ', '-', strtolower($this->owner->singular_name())));
    }

    /**
     * Helper method to determine if a block is the first on a page where the hero area is not displayed
     */
    public function getIsFirstBlock(): bool
    {
        $ownerPage = $this->owner->Parent()->getOwnerPage();
        $showHero = $ownerPage->ShowHero;
        $firstBlock = $ownerPage->ElementalArea()->Elements()->first();

        return $firstBlock instanceof $this->owner && $this->owner->Sort === 1 && !$showHero;
    }

    /**
     * Helper method to provide CSS class for section background color
     */
    public function getSectionColorClass(): string
    {
        // Fallback to white if no background color selected
        $color = $this->owner->BlockBackgroundColor ?? 'white';

        return sprintf('section--color-%s',  $color);
    }

}
