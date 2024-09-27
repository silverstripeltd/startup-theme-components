<?php

namespace SilverStripe\StartupTheme;

use SilverStripe\Forms\FieldList;
use SilverStripe\LinkField\Form\LinkField;
use SilverStripe\LinkField\Models\Link;
use SilverStripe\ORM\DataExtension;

class HeaderButtonExtension extends DataExtension {

    public static array $has_one = [
        'HeaderLink' => Link::class
    ];

    public function updateCMSFields(FieldList $fields): void {

        $fields->addFieldsToTab('Root.HeaderLink',
            [
                Linkfield::create('HeaderLink','Header Link')
            ]);
    }
}
