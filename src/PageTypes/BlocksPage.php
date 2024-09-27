<?php

namespace SilverStripe\StartupThemeComponents\PageTypes;

use Page;
// use SilverStripe\StartupThemeComponents\Extensions\HeroExtension;
use SilverStripe\Forms\RequiredFields;

class BlocksPage extends Page
{
    private static string $table_name = 'BlocksPage';

    // private static array $extensions = [
    //     HeroExtension::class,
    // ];


    /**
     * @return RequiredFields
     */
    public function getCMSValidator(): RequiredFields
    {
        return RequiredFields::create([
            'Title',
        ]);
    }
}
