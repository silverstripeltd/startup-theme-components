<?php

namespace SilverStripe\StartupThemeComponents\Elemental\Block;

use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\Validation\CompositeValidator;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\Validation\RequiredFieldsValidator;
use SilverStripe\LinkField\Form\LinkField;
use SilverStripe\LinkField\Models\Link;

class ImageTextBlock extends BaseElement
{

    private static string $icon = 'font-icon-block-promo-3';

    private static string $description = 'A block to display an image and text';

    private static string $table_name = 'ImageTextBlock';

    private static string $singular_name = 'image and text block';

    private static string $plural_name = 'image and text blocks';

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

    public function getCMSFields(): FieldList
    {
        $fields = parent::getCMSFields();

        $fields->removeByName(
            [
                'CTAButtonLinkID',
            ]
        );

        $uploadField = UploadField::create('ImageTextBlockImage', 'image')
            ->setDescription('Max file upload size: 2MB');
        $uploadField->getValidator()->setAllowedMaxFileSize(2 * 1024 * 1024);

        $fields->insertBefore('ImagePosition', $uploadField);
        $fields->addFieldsToTab(
            'Root.Main',
            [
                HTMLEditorField::create('Content', 'Content'),
                LinkField::create('CTAButtonLink'),
            ]
        );
        return $fields;
    }

    public function getCMSCompositeValidator(): CompositeValidator
    {
        $composite = parent::getCMSCompositeValidator();

        $requiredFields = [
            'ImageTextBlockImage',
            'Title'
        ];
        $composite->addValidator(RequiredFieldsValidator::create($requiredFields));

        return $composite;
    }

    public function getType(): string
    {
        return _t(__CLASS__ . '.BlockType', 'Image and text');
    }

    protected function provideBlockSchema(): array
    {
        $blockSchema = parent::provideBlockSchema();

        $image = $this->ImageTextBlockImage();

        if ($image && $image->exists() && $image->getIsImage()) {
            $blockSchema['fileURL'] = $image->CMSThumbnail()->getURL();
            $blockSchema['fileTitle'] = $image->getTitle();
        }

        $blockSchema['content'] = $this->dbObject('Content')->Summary(20);

        return $blockSchema;
    }

    /**
     * Helper method to provide CSS class for block variant
     * Variant based on image position
     */
    public function getBlockVariantClass(): string
    {
        $block = $this->getBlockNameClass();
        $variant = strtolower($this->ImagePosition);

        return sprintf('%s--%s', $block, $variant);
    }

}
