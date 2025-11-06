<?php

namespace SilverStripe\StartupTheme;

use DNADesign\Elemental\Models\ElementContent;
use Heyday\MenuManager\MenuItem;
use Heyday\MenuManager\MenuSet;
use Page;
use SilverStripe\Assets\Folder;
use SilverStripe\Assets\Image;
use SilverStripe\CMS\Controllers\RootURLController;
use SilverStripe\Control\Director;
use SilverStripe\Core\Extension;
use SilverStripe\Core\Validation\ValidationException;
use SilverStripe\LinkField\Models\ExternalLink;
use SilverStripe\LinkField\Models\SiteTreeLink;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\DB;
use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\StartupThemeComponents\Elemental\Block\ImageTextBlock;
use SilverStripe\StartupThemeComponents\PageTypes\BlocksPage;

class DefaultRecordsExtension extends Extension
{
    /**
     * The Startup theme ships with some specific content, so we'll create this on creation of the SiteConfig.
     * @return void
     * @throws ValidationException
     */
    public function onRequireDefaultRecords(): void
    {
        // On initial dev-build, the default SiteConfig is created.
        // So provided SiteConfig doesn't exist, we know this is the first dev/build on a fresh DB,
        // and we want to create default pages, menus and blocks.
        if (!SiteConfig::get()->first()) {
            // Start creating some default data
            $this->createSiteConfig();
            $this->createHomePage();
            $this->createImages();

            // Start sort at 2, as Home page is one
            $sort = 2;

            // Create other top level pages
            $pages = [
                'About',
                'Resources',
            ];

            $aboutPage = BlocksPage::create([
                'Title' => 'About',
                'ShowHero' => '1',
                'Intro' => 'The Startup package is a simple site for you to either try out Silverstripe CMS, or use as a starting point for your next project.',
                'Sort' => $sort,
            ]);
            $aboutPage->write();
            $aboutPage->publishRecursive();
            $sort++;

            $resourcesPage = Page::create([
                'Title' => 'Resources',
                'Sort' => $sort,
            ]);
            $resourcesPage->write();
            $resourcesPage->publishRecursive();
            $sort++;

            // Create top level pages
            foreach ($pages as $pageTitle) {
                $page = Page::create([
                    'Title' => $pageTitle,
                    'Sort' => $sort,
                ]);
                $page->write();
                $page->publishSingle();
                $sort++;
            }

            // Add child pages to About page
            $aboutChildPages = [
                'The Startup package',
                'Silverstripe CMS',
            ];
            $aboutPage = Page::get()->filter('Title', 'About')->first();
            foreach ($aboutChildPages as $pageTitle) {
                $page = Page::create([
                    'Title' => $pageTitle,
                    'ParentID' => $aboutPage->ID,
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

            // Get footer menu and add external link to it
            $footerMenu = MenuSet::get()->filter('Name', 'FooterMenu')->first();
            $item = MenuItem::create([
                'MenuTitle' => 'Contact',
                'Link' => 'https://www.silverstripe.com/contact/',
                'IsNewWindow' => 1,
            ]);
            $item->write();
            $footerMenu->MenuItems()->add($item);

            // Create various blocks
            $this->createTextImageBlocks();

            // Add the rest of the content to the other pages
            $this->createPageContent();

            DB::alteration_message("All default Startup content created", "created");
        }

        // Copy over project theme if it doesn't exist
        $this->copyThemeToProject();
    }

    /**
     * Copy the theme folder from vendor to project root themes directory
     *
     * @return void
     */
    protected function copyThemeToProject(): void
    {
        $vendorThemePath = Director::baseFolder() . '/vendor/silverstripeltd/startup-theme-components/themes/startup-theme-components';
        $projectThemesPath = Director::baseFolder() . '/themes';

        // 1. Copy CSS files to themes/startup-theme/css
        $vendorCssPath = $vendorThemePath . '/css';
        $projectStartupThemePath = $projectThemesPath . '/startup-theme';
        $projectCssPath = $projectStartupThemePath . '/css';

        // Check if source CSS folder exists
        if (is_dir($vendorCssPath)) {
            // Ensure themes/startup-theme/css directory exists
            if (!is_dir($projectCssPath)) {
                mkdir($projectCssPath, 0755, true);
            }

            // Copy CSS files
            $copiedCss = 0;
            $dir = opendir($vendorCssPath);
            while (($file = readdir($dir)) !== false) {
                if ($file === '.' || $file === '..') {
                    continue;
                }

                $sourcePath = $vendorCssPath . '/' . $file;
                $destPath = $projectCssPath . '/' . $file;

                // Only copy if file doesn't exist (don't overwrite existing files)
                if (!file_exists($destPath) && is_file($sourcePath)) {
                    copy($sourcePath, $destPath);
                    $copiedCss++;
                }
            }
            closedir($dir);

            if ($copiedCss > 0) {
                DB::alteration_message("Copied {$copiedCss} CSS file(s) to themes/startup-theme/css", "created");
            }
        }

        // 2. Copy images and templates folders to themes/startup-theme-components
        $projectComponentsThemePath = $projectThemesPath . '/startup-theme-components';

        // Copy images folder
        $vendorImagesPath = $vendorThemePath . '/images';
        $projectImagesPath = $projectComponentsThemePath . '/images';

        if (is_dir($vendorImagesPath) && !is_dir($projectImagesPath)) {
            $this->recursiveCopy($vendorImagesPath, $projectImagesPath);
            DB::alteration_message("Copied images folder to themes/startup-theme-components/images", "created");
        }

        // Copy templates folder
        $vendorTemplatesPath = $vendorThemePath . '/templates';
        $projectTemplatesPath = $projectComponentsThemePath . '/templates';

        if (is_dir($vendorTemplatesPath) && !is_dir($projectTemplatesPath)) {
            $this->recursiveCopy($vendorTemplatesPath, $projectTemplatesPath);
            DB::alteration_message("Copied templates folder to themes/startup-theme-components/templates", "created");
        }
    }

    /**
     * Recursively copy a directory
     *
     * @param string $source
     * @param string $dest
     * @return void
     */
    protected function recursiveCopy(string $source, string $dest): void
    {
        if (!is_dir($dest)) {
            mkdir($dest, 0755, true);
        }

        $dir = opendir($source);
        while (($file = readdir($dir)) !== false) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $sourcePath = $source . '/' . $file;
            $destPath = $dest . '/' . $file;

            if (is_dir($sourcePath)) {
                $this->recursiveCopy($sourcePath, $destPath);
            } else {
                copy($sourcePath, $destPath);
            }
        }
        closedir($dir);
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
            'LinkText' => 'Package repo',
            'ExternalUrl' => 'https://github.com/silverstripeltd/startup-theme-components',
            'OpenInNew' => true,
        ]);
        $headerButton->write();
        $headerButton->publishRecursive();

        $siteConfig = DataObject::get_one(SiteConfig::class);

        // Set site config values
        $siteConfig->Title = 'Startup Theme';
        $siteConfig->Copyright = 'Silverstripe';
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
            'MetaDescription' => 'Welcome to Silverstripe CMS Startup',
            'ShowHero' => '0',
            'Sort' => '1',
        ]);
        $page->write();
        $page->publishSingle();
        DB::alteration_message('Home page created', 'created');
    }

    public function createImages(): void
    {
        $images = [
            'illustration-content.png' => 'Content',
            'illustration-recipe.png' => 'Recipe',
            'illustration-resources.png' => 'Resources',
            'illustration-silverstripe-cms.png' => 'SilverstripeCMS',
        ];

        // Create this set of images for the Silverstripe filesystem
        foreach ($images as $path => $title) {
            $imagePath = Director::getAbsFile('vendor/silverstripeltd/startup-theme-components/themes/startup-theme-components/images/' . $path);
            $image = Image::create([
                'CanViewType' => 'Anyone',
                'Title' => $title,
            ]);
            $image->setFromString(file_get_contents($imagePath), basename($imagePath));
            $image->write();
            $image->publishFile();
        }
    }

    public function createTextImageBlocks(): void
    {
        // Create links for the blocks
        $startuplink = SiteTreeLink::create([
            'LinkText' => 'What\'s included',
            'PageID' => Page::get()->filter('Title', 'The Startup package')->first()->ID,
        ]);
        $startuplink->write();
        $startuplink->publishRecursive();

        $cmsLink = SiteTreeLink::create([
            'LinkText' => 'More about Silverstripe CMS',
            'PageID' => Page::get()->filter('Title', 'Silverstripe CMS')->first()->ID,
        ]);
        $cmsLink->write();
        $cmsLink->publishRecursive();


        // Create Homepage image and text blocks
        $homePage = Page::get()->filter('Title', 'Home')->first();
        $image1 = Image::get()->filter('Title', 'Content')->first();
        $imageTextBlock1 = ImageTextBlock::create([
            'TopPageID' => $homePage->ID,
            'ParentID' => $homePage->ElementalAreaID,
            'Title' => 'Welcome to Silverstripe CMS Startup',
            'ShowTitle' => '1',
            'ImagePosition' => 'Right',
            'Sort' => 1,
            'Content' => '
                <p>The Startup package is a theme and set of modules for you to either try out Silverstripe CMS, or use
                as a starting point for your next project.</p>
                ',
            'ImageTextBlockImageID' => $image1->ID,
        ]);
        $imageTextBlock1->write();
        $imageTextBlock1->publishRecursive();

        $image2 = Image::get()->filter('Title', 'Recipe')->first();
        $imageTextBlock2 = ImageTextBlock::create([
            'TopPageID' => $homePage->ID,
            'ParentID' => $homePage->ElementalAreaID,
            'Title' => 'Templates and modules to help you start-up your next project',
            'ShowTitle' => '1',
            'ImagePosition' => 'Left',
            'Sort' => 2,
            'Content' => '
                <p>The Startup package comes with a number of modules, features, page templates and content blocks to
                get you started.</p>
                ',
            'CTAButtonLink' => $startuplink->ID,
            'ImageTextBlockImageID' => $image2->ID,
        ]);
        $imageTextBlock2->write();
        $imageTextBlock2->publishRecursive();

        $image3 = Image::get()->filter('Title', 'SilverstripeCMS')->first();
        $imageTextBlock3 = ImageTextBlock::create([
            'TopPageID' => $homePage->ID,
            'ParentID' => $homePage->ElementalAreaID,
            'Title' => 'Powered by Silverstripe CMS',
            'ShowTitle' => '1',
            'ImagePosition' => 'Right',
            'Sort' => 3,
            'Content' => '
                <p>Built in Aotearoa New Zealand and used all over the world, Silverstripe CMS is a powerful and
                customisable product that can help you create the digital experience your customers need.</p>
                ',
            'CTAButtonLink' => $cmsLink->ID,
            'ImageTextBlockImageID' => $image3->ID,
        ]);
        $imageTextBlock3->write();
        $imageTextBlock3->publishRecursive();
        $homePage->write();
        $homePage->publishRecursive();

        // Blocks for the About page
        $aboutPage = Page::get()->filter('Title', 'About')->first();
        $imageTextBlock4 = ImageTextBlock::create([
            'TopPageID' => $aboutPage->ID,
            'ParentID' => $aboutPage->ElementalAreaID,
            'Title' => 'What\'s in the Startup package?',
            'ShowTitle' => '1',
            'ImagePosition' => 'Right',
            'Sort' => 1,
            'Content' => '
                <p>The Startup package is a demo to show off the authoring interface of the Silverstripe Content
                Management System (CMS). It comes with a number of features, page templates, content blocks and
                pre-installed modules.</p>
                ',
            'CTAButtonLink' => $startuplink->ID,
            'ImageTextBlockImageID' => $image2->ID,
        ]);
        $imageTextBlock4->write();
        $imageTextBlock4->publishRecursive();

        $imageTextBlock5 = ImageTextBlock::create([
            'TopPageID' => $aboutPage->ID,
            'ParentID' => $aboutPage->ElementalAreaID,
            'Title' => 'Why use Silverstripe CMS?',
            'ShowTitle' => '1',
            'ImagePosition' => 'Left',
            'Sort' => 2,
            'Content' => '
                <p>The Silverstripe CMS is designed to promote accessible site design, semantic markup, and HTML5 use.
                It has been downloaded well over 500,000 times and powers websites for government agencies,
                corporations, non-profits and a large number of much smaller websites.</p>
                ',
            'CTAButtonLink' => $cmsLink->ID,
            'ImageTextBlockImageID' => $image3->ID,
        ]);
        $imageTextBlock5->write();
        $imageTextBlock5->publishRecursive();
        $aboutPage->write();
        $aboutPage->publishRecursive();
    }

    public function createPageContent(): void
    {
        // Add content to The Startup package page
        $startupImage = Image::get()->filter('Title', 'Recipe')->first();
        $startupPage = Page::get()->filter('Title', 'The Startup package')->first();
        $startupPage->Intro =
            'The Startup package is a simple yet functional site install to get you started on your next project.';
        $startupPage->Content = '
            <p><img width="1160" height="800" alt="" src="'. $startupImage->getURL() .'" loading="lazy" class="leftAlone ss-htmleditorfield-file image"></p>
            <h2>Theme styles</h2>
            <p>The Startup package comes with a set of simple yet robust styles including:</p>
            <ul>
                <li>Colour variables</li>
                <li>Typography</li>
                <li>Interactions</li>
            </ul>
            <h2>Modules</h2>
            <p>The following modules are included:</p>
            <ul>
                <li><a rel="noopener noreferrer" href="https://github.com/silverstripe/silverstripe-elemental" target="_blank">Elemental</a> - allows you to create and structure pages with content blocks</li>
                <li><a rel="noopener noreferrer" href="https://github.com/jonom/silverstripe-focuspoint" target="_blank">Image focus point</a></li>
                <li><a rel="noopener noreferrer" href="https://github.com/WPP-Public/akqa-nz-silverstripe-menumanager" target="_blank">Menu manager</a></li>
            </ul>
            <h2>Page templates</h2>
            <p>The following page templates are included:</p>
            <ul>
                <li>Blocks page</li>
                <li>Content page (this page)</li>
            </ul>
            <h2>Content blocks</h2>
            <p>Content blocks are reusable templated sections which can be used across various pages on a site. This gives you the flexibility to design and structure your content how you want.</p>
            <p>Blocks included in the Startup package:</p>
            <ul>
                <li>WYSIWYG block: rich text block which allows for text, links, tables, and images.</li>
                <li>Image-text block: a simple block which allows for a large image on either the left or right, with simple text content</li>
            </ul>
        ';
        $startupPage->write();
        $startupPage->publishRecursive();

        // Add content to Silverstripe CMS page
        $cmsImage = Image::get()->filter('Title', 'SilverstripeCMS')->first();
        $cmsPage = Page::get()->filter('Title', 'Silverstripe CMS')->first();
        $cmsPage->Intro = 'Built in Aotearoa New Zealand and used all over the world.';
        $cmsPage->Content = '
            <p><img width="1160" height="800" alt="" src="'. $cmsImage->getURL() .'" loading="lazy" class="leftAlone ss-htmleditorfield-file image"></p>
            <p>We believe the open source model simply produces better web software and in turn, better meets our own needs and those of other developers who use the software. As with all open source software, anyone has access to the source code, and a global community of developers can share best practices, code, documentation, roadmap ideas and so on.</p>
            <h2>Features and benefits</h2>
            <h3>Content blocks</h3>
            <p>Building your pages block by block gives you flexibility to design and structure your content how you want.</p>
            <h3>Security</h3>
            <p>Silverstripe CMS is professionally maintained and has security features built-in to protect your website’s data.</p>
            <h3><span>Add-on library</span></h3>
            <p><span>Extend the functionality of your website with pre-built add-ons. There are thousands of add-ons to choose from in the add-on library.</span></p>
            <h3><span>User workflows</span></h3>
            <p><span>Set up user roles and permissions and then create workflows so that content is reviewed and approved by the right people.</span></p>
            <h3><span>History and version controls</span></h3>
            <p><span>See how your content has changed with each version and revert changes if you need to. Content history allows you to see publishing information, and archive it within the CMS.</span></p>
            <h3><span>Decoupled content delivery</span></h3>
            <p><span>Silverstripe CMS combines the strength of a traditional CMS with powerful </span><span>API integrations for a headless or decoupled approach if required.</span></p>
            <h2><span>Licensing</span></h2>
            <p><span>You never have to pay a licensing fee for Silverstripe CMS, and you never have to worry about vendor lock-in. Under the BSD license, which is one of the most flexible open source licenses and Open Source Initiative approved, you benefit from the contributions of others in the community, and the transparent way in which the software is developed in consultation with its users.</span></p>
                    ';
        $cmsPage->write();
        $cmsPage->publishRecursive();

        // Add content to Resources page
        $resourceImage = Image::get()->filter('Title', 'Resources')->first();
        $resourcesPage = Page::get()->filter('Title', 'Resources')->first();
        $resourcesPage->Intro = 'Links to help you get started as well as technical resources.';
        $resourcesPage->Content = '
            <p><img width="1160" height="800" alt="" src="'. $resourceImage->getURL() .'" loading="lazy" class="leftAlone ss-htmleditorfield-file image"></p>
            <h2>For CMS users</h2>
            <p>New to Silverstripe CMS? Our user guide will help get you started with managing content. The guide has been specially created to help web administrators and content authors learn how to make the most of Silverstripe CMS.</p>
            <ul>
                <li><a rel="noopener noreferrer" href="https://userhelp.silverstripe.org/" target="_blank">User help guide</a></li>
            </ul>
            <h2>For developers</h2>
            <p>We believe knowledge is best learnt by engaging with other Silverstripe CMS practitioners and getting to know others in the community. Whether your focus is front-end or back-end development, there are several resources and ways to connect with others to help you on your way.</p>
            <ul>
                <li><a rel="noopener noreferrer" href="https://docs.silverstripe.org/" target="_blank">Silverstripe CMS Docs</a></li>
                <li><a rel="noopener noreferrer" href="https://api.silverstripe.org/" target="_blank">API Docs</a></li>
                <li><a rel="noopener noreferrer" href="https://silverstripe-users.slack.com" target="_blank">Slack</a></li>
            </ul>
        ';
        $resourcesPage->write();
        $resourcesPage->publishRecursive();
    }
}
