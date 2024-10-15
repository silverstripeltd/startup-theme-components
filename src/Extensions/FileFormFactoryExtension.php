<?php

namespace SilverStripe\StartupTheme;

use SilverStripe\AssetAdmin\Forms\AssetFormFactory;
use SilverStripe\Forms\Tip;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\TippableFieldInterface;

class FileFormFactoryExtension extends DataExtension
{
    public function updateFormFields(FieldList $fields, $controller, $name, $context)
    {
        $record = $context['Record'] ?? null;
        $type = $context['Type'] ?? null;

        if (!$record || $record->appCategory() !== 'image') {
            return;
        }

        $altTextField = TextField::create(
            'AltText',
            _t('SilverStripe\\AssetAdmin\\Controller\\AssetAdmin.AltText', 'Alternative text (alt)')
        );

        if ($type) {
            $altTextField = $altTextField->performReadonlyTransformation();
        }

        $altTextDescription = _t(
            'SilverStripe\\AssetAdmin\\Controller\\AssetAdmin.AltTextTip',
            'Description for visitors who are unable to view the image (using screenreaders or ' .
            'image blockers). Recommended for images which provide unique context to the content.'
        );

        if ($altTextField instanceof TippableFieldInterface) {
            $altTextField->setTip(new Tip($altTextDescription, Tip::IMPORTANCE_LEVELS['HIGH']));
        } else {
            $altTextField->setDescription($altTextDescription);
        }

        $fields->insertAfter('Title', $altTextField);
    }

}
