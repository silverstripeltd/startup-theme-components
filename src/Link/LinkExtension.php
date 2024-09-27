<?php

namespace SilverStripe\Link;

use SilverStripe\ORM\DataExtension;
use SilverStripe\SiteConfig\SiteConfig;

class LinkExtension extends DataExtension {

    private static array $belongs_to = [
        'HeaderLink' => SiteConfig::class . '.HeaderLink',
    ];
}