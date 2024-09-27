<?php

namespace SilverStripe\Com\Extensions;

use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\DataExtension;

/**
 * @property string $HeroTitle
 * @property bool $ShowHero
 */
class HeroExtension extends DataExtension
{

    /**
     * Config to enable or disable this extension
     */
    private static bool $enable_hero_extension = true;

    private static array $db = [
        'HeroTitle' => 'Varchar(255)',
        'ShowHero' => 'Boolean',
    ];

    private static array $defaults = [
        'ShowHero' => 1,
    ];


    public function updateCMSFields(FieldList $fields)
    {
        return $fields;
    }

}
