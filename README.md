# The Startup Package: startup-theme-components

This module extends the Startup theme with some commonly used modules, and some example models to get you started.

## Installation
This module should be installed as part of a clean Silverstripe project if you'd like all the default content to be created.

This guide assumes you have [Composer](https://getcomposer.org/) installed, as well as knowlege of running a webserver with PHP and a database.

In the CLI, navigate to the folder where you want to create your Silverstripe project.

Create a new Silverstripe project via composer:
```bash
composer create-project silverstripe/installer {my-project}
```

Navigate to your project folder:
```bash
cd {my-project}
```

Point your webserver root to the `public` folder inside your project folder.

Then require the `startup-theme-components` module:
```bash
composer require silverstripeltd/startup-theme-components
```

As with most Silverstripe modules, you'll need to then build the database fields.
```bash
vendor/bin/sake db:build
```

As part of the build process, this module will move its theme files into your project's root `themes` folder.
It will also augment the default `startup-theme` CSS folder with dome additional files from this module.
In your project's `app/_config/theme.yml` file, add the `startup-theme-components` theme as the default theme.
Your config should look something like this:
```yaml
---
Name: mytheme
---
SilverStripe\View\SSViewer:
  themes:
    - 'startup-theme-components'
    - 'startup-theme'
    - '$public'
    - '$default'
```

You can now modify and extend the theme as you like. See here for more information on [customising themes](https://docs.silverstripe.org/en/6/developer_guides/templates/themes/).

Now set up your .env file as required, see the [quick start guide](https://docs.silverstripe.org/en/6/getting_started/#quickstart-installation)

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

### [FocusPoint](https://github.com/jonom/silverstripe-focuspoint)
FocusPoint allows content authors to define a focal point for images, so that images are cropped intelligently when resampled.
Consult the [README](https://github.com/jonom/silverstripe-focuspoint?tab=readme-ov-file) for detailed usaged instructions.

## Other customisations
There have been some other useful extensions added to core Silverstripe models, to demonstrate how their data can be 
customised.

* **Site Config Extension** - The Site Config has been extended to include a field for the footer copyright line, and a 
custom link button for the site header. Site Config extensions are very useful for providing global data, so you'll 
find one in almost every Silverstripe project.
* **Page Extension** - An Intro field and option to hide the sibling menu are added to the base Page type.
* **Image Extension** - Adds a field to customise the `alt` attribute for images.
