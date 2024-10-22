# startup-theme-components

This module extends the Startup theme with some commonly used modules, and some example models to get you started.

## Included Modules

### [Silverstripe Elemental](https://github.com/silverstripe/silverstripe-elemental)
The Elemental module is one of the most popular and essential add-ons to Silverstripe CMS. Elememtal replaces the
standard `Content` field on a page with an `ElementalArea` field. This enables content authors to compose webpages
from sets of Elements - also known as Blocks. Full developer documentation for Elemental can be [found here](https://docs.silverstripe.org/en/5/optional_features/elemental/).

Startup Theme Components (STC) already has some basic Elemental setup done for you. We have created a Blocks Page model 
found at src/PageTypes/BlocksPage.php, and this has had the DNADesign\Elemental\Extensions\ElementalPageExtension 
applied to it (see _config.startup.yml to see all the extension configuration included with STC). Blocks pages can be
added via the CMS and blocks can be added to those pages. STC comes with a Content Block and an Image & Text Block, see
the [documentation](https://docs.silverstripe.org/en/5/optional_features/elemental/defining-you-own-elements/) for 
information on creating your own block types. 

### [Silverstripe Menu Manager](https://github.com/WPP-Public/akqa-nz-silverstripe-menumanager)
Menu Manager lets you define custom menus in the CMS, and use them in templates. STC ships with a Main Menu and a Footer
Menu. The menus can be edited in the CMS via the "Menus" link in the admin menu. Check the [REAADME](https://github.com/WPP-Public/akqa-nz-silverstripe-menumanager?tab=readme-ov-file#silverstripe-menu-manager)
for full documentation.

## Other customisations
There have been some other useful extensions added to core Silverstripe models, to demonstrate how their data can be customised.

* **Site Config Extension** - The Site Config has been extended to include fields for the copyright line in the footer 
and a custom link button for the site header. Site Config extensions are very useful for providing global data that 
you'll need to access site wide, and you'll find a Site Config Extension in almost every Silverstripe project.
* **Page Extension** - An Intro field and option to hide the sibling menu are added to the base Page type.
* **Image Extension** - Adds field to add an `alt` attribute to images.


