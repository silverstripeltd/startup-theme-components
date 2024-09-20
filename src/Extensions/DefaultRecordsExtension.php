<?php

namespace SilverStripe\StartupTheme;

use Heyday\MenuManager\MenuItem;
use Heyday\MenuManager\MenuSet;
use Page;
use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\DB;
use SilverStripe\ORM\ValidationException;
use SilverStripe\SiteConfig\SiteConfig;

class DefaultRecordsExtension extends DataExtension
{
    /**
     * The Startup theme ships with some specific content, so we'll create this on creation of the SiteConfig.
     * @return void
     * @throws ValidationException
     */
    public function requireDefaultRecords(): void
    {
        parent::requireDefaultRecords();

        // On initial dev-build, the default SiteConfig is created.
        // So provided SiteConfig doesn't exist, we know this is the first dev/build on a fresh DB,
        // and we want to create default pages, menus and blocks.
        if (!SiteConfig::get()->first()) {
            // Create pages (adding pages here interferes with the core SiteTree logic which will create the About and
            // Contact pages based on the current page count, so we'll add our own ones here. Home page still gets made
            // within the SiteTree requireDefaultRecords() hook).
            $pages = [
                'About',
                'Resources',
                'Contact',
            ];

            // Start sort at 2, as Home page is one
            $sort = 2;

            foreach ($pages as $pageTitle) {
                $page = Page::create([
                    'Title' => $pageTitle,
                    'Sort' => $sort,
                ]);
                $page->write();
                $page->publishSingle();
                $sort++;
            }

            // Get default MenuSet and add the default items to it
            $menu = MenuSet::get()->filter('Name', 'MainMenu')->first();

            foreach ($pages as $pageTitle) {
                $item = MenuItem::create([
                    'MenuTitle' => $pageTitle,
                    'PageID' => Page::get()->filter('Title', $pageTitle)->first()->ID,
                ]);
                $item->write();
                $menu->MenuItems()->add($item);
            }
        }
    }
}
