<?php

namespace SilverStripe\StartupTheme;

use DNADesign\Elemental\Models\ElementContent;
use Heyday\MenuManager\MenuItem;
use Heyday\MenuManager\MenuSet;
use Page;
use SilverStripe\Assets\Image;
use SilverStripe\CMS\Controllers\RootURLController;
use SilverStripe\Control\Director;
use SilverStripe\Core\Manifest\ModuleResourceLoader;
use SilverStripe\LinkField\Models\ExternalLink;
use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\DB;
use SilverStripe\ORM\ValidationException;
use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\StartupThemeComponents\Elemental\Block\ImageTextBlock;
use SilverStripe\StartupThemeComponents\PageTypes\BlocksPage;
use SilverStripe\View\ThemeResourceLoader;

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
            // Create site config
            $this->createSiteConfig();

            // Create Home page
            $this->createHomePage();

            // Create pages (adding pages here interferes with the core SiteTree logic which will create the About and
            // Contact pages based on the current page count, so we'll add our own ones here.
            $pages = [
                'About',
                'Resources',
                'Contact',
            ];

            $footerPages = [
                'About Startup',
                'Another page',
            ];

            // Start sort at 2, as Home page is one
            $sort = 2;

            $allPages = array_merge($pages, $footerPages);

            foreach ($allPages as $pageTitle) {
                $page = Page::create([
                    'Title' => $pageTitle,
                    'Sort' => $sort,
                ]);
                $page->write();
                $page->publishSingle();
                $sort++;
            }

            // Get default MenuSet and add the default items to it
            $mainMenu = MenuSet::get()->filter('Name', 'MainMenu')->first();

            foreach ($pages as $pageTitle) {
                $item = MenuItem::create([
                    'MenuTitle' => $pageTitle,
                    'PageID' => Page::get()->filter('Title', $pageTitle)->first()->ID,
                ]);
                $item->write();
                $mainMenu->MenuItems()->add($item);
            }

            // Get footer menu and add items to it
            $footerMenu = MenuSet::get()->filter('Name', 'FooterMenu')->first();

            foreach ($footerPages as $pageTitle) {
                $item = MenuItem::create([
                    'MenuTitle' => $pageTitle,
                    'PageID' => Page::get()->filter('Title', $pageTitle)->first()->ID,
                ]);
                $item->write();
                $footerMenu->MenuItems()->add($item);
            }
        }
    }

    /**
     * Create site config
     *
     * The site config normally gets created within the SiteConfig requireDefaultRecords() hook which
     * runs after this extension. As we require a site config record to attach a header button to,
     * it must be created here.
     *
     * @return void
     * @throws ValidationException
     */
    public function createSiteConfig(): void
    {
        $siteConfig = SiteConfig::create();
        $siteConfig->write();
        DB::alteration_message("Added default site config", "created");

        // Create header button link
        $headerButton = ExternalLink::create([
            'LinkText' => 'Dev docs',
            'ExternalUrl' => 'https://docs.silverstripe.org/',
            'OpenInNew' => true,
        ]);
        $headerButton->write();
        $headerButton->publishRecursive();

        $siteConfig = DataObject::get_one(SiteConfig::class);
        $siteConfig->HeaderButtonID = $headerButton->ID;
        $siteConfig->write();
        $headerButton->publishRecursive();
    }

    /**
     * Create Home page
     *
     * The Home page normally gets created within the SiteTree requireDefaultRecords() hook which
     * runs after this extension. As we require the Home page to be of `BlocksPage` page type,
     * it must be created here.
     *
     * @return void
     * @throws ValidationException
     */
    public function createHomePage(): void
    {
        $page = BlocksPage::create([
            'Title' => 'Home',
            'URLSegment' => RootURLController::config()->get('default_homepage_link'),
            'MetaDescription' => 'Welcome to Silverstripe CMS Sandbox',
            'ShowHero' => '0',
            'Sort' => '1',
        ]);
        $page->write();
        $page->publishSingle();
        DB::alteration_message('Home page created', 'created');

        // Create various blocks for the Home page
        $page = Page::get()->filter('Title', 'Home')->first();
        $this->createTextImageBlock($page);
        $this->createContentBlock($page);

        // Publish Home page
        $page->publishRecursive();
    }

    public function createTextImageBlock($page): void
    {
        // Create an image
        $imagePath = Director::getAbsFile('themes/startup/images/block1-image.webp');
        $image = Image::create();
        $image->setFromString(file_get_contents($imagePath), basename($imagePath));
        $image->write();
        $image->publishFile();

        // Create external link
        $link = ExternalLink::create([
            'LinkText' => 'Check out the features',
            'ExternalUrl' => 'https://docs.silverstripe.org/',
            'OpenInNew' => false,
        ]);
        $link->write();
        $link->publishRecursive();

        // Create image and text block
        $imageTextBlock = ImageTextBlock::create([
            'ParentID' => $page->ElementalAreaID,
            'Title' => 'Welcome to Silverstripe CMS Sandbox',
            'ShowTitle' => '1',
            'ImagePosition' => 'Right',
            'Sort' => 1,
            'Content' => '
                <p>Silverstripe CMS works to empower your teams and your customers by keeping things clean, simple,
                 and easy-to-use.</p>
                ',
            'ImageTextBlockImageID' => $image->ID,
            'CTAButtonLinkID' => $link->ID,
        ]);

        $imageTextBlock->write();
        $imageTextBlock->publishRecursive();
    }

    public function createContentBlock($page): void
    {
        // Create content block
        $block = ElementContent::create([
            'TopPageID' => $page->ID,
            'ParentID' => $page->ElementalAreaID,
            'Title' => 'Block heading 2',
            'ShowTitle' => '1',
            'Sort' => 2,
            'BlockBackgroundColor' => 'pale-grey',
            'BlockNarrowContentWidth' => '1',
            'HTML' => '
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum eu quam orci. Duis vitae rutrum
                metus. Vivamus eu erat vulputate, ornare sem et, auctor diam. Ut nec mollis neque, vehicula mollis
                dolor. Proin vel quam cursus, malesuada sapien vel, maximus urna. Sed lectus ligula, lacinia non mauris
                at, maximus placerat sapien. Proin euismod augue et felis hendrerit commodo. Proin gravida urna a velit
                molestie fringilla. Maecenas consectetur dui vitae tellus scelerisque, non posuere massa tincidunt.
                Etiam porta sed mi eget hendrerit.</p>
                <p>Nunc sed massa in erat venenatis rutrum. Sed nec pretium neque. Etiam laoreet id ex vitae porttitor.
                Quisque quis lacus lacinia nisi laoreet sodales sit amet rutrum eros. Duis pulvinar porttitor quam vel
                pellentesque. Nulla facilisi. Pellentesque habitant morbi tristique senectus et netus et malesuada
                fames ac turpis egestas. Quisque et dui sed sem suscipit varius quis sit amet massa. Nullam lacinia est
                non lorem luctus accumsan.</p>
                <h3>Heading 3</h3>
                <p>Ut nec felis congue, tincidunt sapien et, lacinia neque. In ac est a risus luctus porta. Donec eget
                tincidunt mi. Vestibulum efficitur nisi eu enim tincidunt, luctus finibus turpis dignissim. Nulla
                sollicitudin viverra neque eu aliquam. Nunc in feugiat risus, et sodales libero. Morbi eleifend magna
                sit amet quam tempor aliquet. Praesent eget odio laoreet, efficitur nulla sed, gravida urna. Morbi
                elementum suscipit urna, a aliquam est congue auctor.</p>
                ',
        ]);
        $block->write();
        $block->publishRecursive();
    }
}

