<?php

namespace SilverStripe\StartupTheme;

use SilverStripe\Forms\FieldList;
use SilverStripe\LinkField\Form\LinkField;
use SilverStripe\LinkField\Models\Link;
use SilverStripe\ORM\DataExtension;

class HeaderButtonExtension extends DataExtension {

    private static array $has_one = [
        'HeaderButton' => Link::class
    ];

    private static array $owns = [
        'HeaderButton',
    ];

    public function updateCMSFields(FieldList $fields): void {
        $fields->addFieldToTab('Root.HeaderButton', Linkfield::create('HeaderButton', 'Header Link'));
    }

}
