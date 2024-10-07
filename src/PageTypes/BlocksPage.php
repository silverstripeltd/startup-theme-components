<?php

namespace SilverStripe\StartupThemeComponents\PageTypes;

use Page;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\TextareaField;

class BlocksPage extends Page
{

    private static string $table_name = 'BlocksPage';

    private static $icon_class = 'font-icon-p-alt-2';

    private static array $db = [
        'ShowHero' => 'Boolean',
        'Intro' => 'Text',
    ];

    private static array $defaults = [
        'ShowHero' => true,
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->insertAfter(
            'MenuTitle',
            CheckboxField::create(
                'ShowHero',
                'Show Hero',
            )->setDescription('Show hero area containing breadcrumbs and page name.'),
        );

        $fields->insertAfter(
            'ShowHero',
            TextareaField::create(
                'Intro',
                'Summary Intro',
            )->setDescription('Summary introduction in the hero area.'),
        );

        return $fields;
    }

}
