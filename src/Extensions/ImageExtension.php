<?php

namespace SilverStripe\StartupTheme;

use SilverStripe\Core\Extension;
use SilverStripe\Forms\CheckboxSetField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextareaField;

class ImageExtension extends Extension
{

    private static array $db = [
        'AltText' => 'Varchar(255)',
    ];

    /**
     * Ensure an empty string is returned in case of null DB field.
     *
     * @return string
     */
    public function AltText(): string
    {
        return $this->owner->AltText ?: '';
    }

    /**
     * Update attribute hook
     *
     * Change the alt attribute value to `AltText` instead of `Title`
     * Allows for an empty value to indicate a decorative image
     */
    public function updateAttributes(array &$attributes): void
    {
        $attributes['alt'] = $this->owner->AltText();
    }

}
