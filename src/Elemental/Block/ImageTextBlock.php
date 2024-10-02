<?php

namespace SilverStripe\StartupThemeComponents\Elemental\Block;

use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\CompositeValidator;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\LinkField\Form\LinkField;
use SilverStripe\LinkField\Models\Link;

class ImageTextBlock extends BaseElement {

    private static string $icon = 'font-icon-block-file';

    private static string $description = 'Image Text Block';

    private static string $table_name = 'ImageTextBlock';

    private static string $singular_name = 'Image Text Block';

    private static string $plural_name = 'Image Text Block';

    private static bool $inline_editable = false;

    private static array $db = [
        'ImagePosition' => 'Enum("Left,Right","Left")',
        'Content' => 'HTMLText',
    ];

    private static array $has_one = [
        'CTAButtonLink' => Link::class,
        'ImageTextBlockImage' => Image::class
    ];

    private static array $owns = [
        'CTAButtonLink',
        'ImageTextBlockImage',
    ];

    public function getCMSFields(): FieldList {
        $fields = parent::getCMSFields();

        $fields->removeByName(
            [
                'CTAButtonLinkID',
            ]
        );

        $fields->insertBefore('ImagePosition', UploadField::create('ImageTextBlockImage', 'image'));
        $fields->addFieldsToTab(
            'Root.Main',
            [
                HTMLEditorField::create('Content', 'Content'),
                LinkField::create('CTAButtonLink'),
            ]
        );
        return $fields;
    }

    public function getCMSCompositeValidator(): CompositeValidator {
        $composite = parent::getCMSCompositeValidator();

        $requiredFields = [
            'ImageTextBlockImage',
            'Title',
        ];
        $composite->addValidator(RequiredFields::create($requiredFields));

        return $composite;
    }

    public function getType(): string {
        return $this->config()->get('singular_name');
    }

    public function getVariantClass(): string {
        $variantClass = 'image-text-block--%s';
        return sprintf($variantClass, strtolower($this->ImagePosition));
    }

}
