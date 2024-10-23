# startup-theme-components

This module extends the Startup theme with some commonly used modules, and some example models to get you started.

## Included Modules

### [Silverstripe Elemental](https://github.com/silverstripe/silverstripe-elemental)
The Elemental module is a popular and essential add-on to Silverstripe CMS. It replaces the standard `Content` field on 
a page with an `ElementalArea` field. This enables content authors to compose webpages from sets of Elements - also 
known as Blocks. Find the full developer documentation for Elemental [here](https://docs.silverstripe.org/en/5/optional_features/elemental/).

Startup Theme Components (STC) already has some basic Elemental setup. We have created a Blocks Page model found at 
src/PageTypes/BlocksPage.php, and this has the DNADesign\Elemental\Extensions\ElementalPageExtension applied (see 
_config.startup.yml to see extension configurations). Add a Blocks Page via the CMS and add blocks to the page. STC 
comes with pre-defined block types: **Content Block** and **Image & Text Block**. For information on creating custom 
block types, see the [documentation](https://docs.silverstripe.org/en/5/optional_features/elemental/defining-you-own-elements/). 

### [Silverstripe Menu Manager](https://github.com/WPP-Public/akqa-nz-silverstripe-menumanager)
Menu Manager lets you define custom menus in the CMS, and use them in templates. STC ships with a Main Menu and a Footer
Menu, edit these via the "Menus" link in the CMS admin. For more info on creating new menus, check the [README](https://github.com/WPP-Public/akqa-nz-silverstripe-menumanager#silverstripe-menu-manager).

## Other customisations
There have been some other useful extensions added to core Silverstripe models, to demonstrate how their data can be 
customised.

* **Site Config Extension** - The Site Config has been extended to include a field for the footer copyright line, and a 
custom link button for the site header. Site Config extensions are very useful for providing global data, so you'll 
find one in almost every Silverstripe project.
* **Page Extension** - An Intro field and option to hide the sibling menu are added to the base Page type.
* **Image Extension** - Adds a field to customise the `alt` attribute for images.
