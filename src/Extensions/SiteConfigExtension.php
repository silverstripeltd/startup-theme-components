<?php

namespace SilverStripe\StartupTheme;

use SilverStripe\Core\Extension;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;

class SiteConfigExtension extends Extension
{

    private static array $db = [
        'FooterCopyright' => 'Text'
    ];

    public function updateCMSFields(FieldList $fields): void
    {
        $fields->addFieldsToTab(
            'Root.Main',
            [
                TextField::create(
                    'FooterCopyright',
                    'Footer Copyright'
                )->setDescription('Customise the copyright text in the footer (leave blank to default to site title)'),
            ]
        );
    }

}
