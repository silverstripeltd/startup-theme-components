<?php

namespace SilverStripe\StartupTheme;

use SilverStripe\Core\Extension;

class ImageExtension extends Extension
{

    private static array $db = [
        'AltText' => 'Varchar(255)',
    ];

    /**
     * Update attribute hook
     *
     * Change the alt attribute value to `AltText` instead of `Title`
     * Allows for an empty value to indicate a decorative image
     */
    public function updateAttributes(array &$attributes): void
    {
        $attributes['alt'] = $this->getOwner()->AltText();
    }

}
