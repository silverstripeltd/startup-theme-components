<?php

namespace SilverStripe\StartupTheme;

use SilverStripe\Core\Extension;
use SilverStripe\Forms\CheckboxSetField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextareaField;

class PageExtension extends Extension
{

    private static array $db = [
        'Intro' => 'Text',
        'ShowSiblingMenu' => 'Boolean',
    ];

    private static array $defaults = [
        'ShowSiblingMenu' => true,
    ];

    public function updateCMSFields(FieldList $fields): void
    {
        $fields->insertBefore(
            'Content',
            TextareaField::create(
                'Intro',
                'Summary Intro',
            )->setDescription('Summary introduction above the content.'),
        );

        $fields->insertAfter(
            'Content',
            CheckboxSetField::create(
                'ShowSiblingMenu',
                'Show Sibling Menu',
                [
                    '1' => 'If this is a sub-page with siblings, display a sibling menu',
                ]
            )
        );
    }


}
