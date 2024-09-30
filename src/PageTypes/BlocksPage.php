<?php

namespace SilverStripe\StartupThemeComponents\PageTypes;

use Page;
use SilverStripe\Forms\CheckboxField;

class BlocksPage extends Page
{
    private static string $table_name = 'BlocksPage';

    private static array $db = [
        'ShowHero' => 'Boolean',
    ];

    private static array $defaults = [
        'ShowHero' => true,
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->insertAfter(
            'MenuTitle',
            $metaTitle = CheckboxField::create(
                'ShowHero', 
                'Show Hero',
            )->setDescription('Show hero area containing breadcrumb and page name.'),
        );
        return $fields;
    }
}
