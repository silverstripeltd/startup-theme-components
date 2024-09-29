<?php

namespace SilverStripe\StartupThemeComponents\PageTypes;

use Page;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\RequiredFields;

class BlocksPage extends Page
{
    private static string $table_name = 'BlocksPage';

    private static array $db = [
        'HeroTitle' => 'Varchar(255)',
        'ShowHero' => 'Boolean',
    ];

    private static array $defaults = [
        'ShowHero' => true,
    ];

    /**
     * @return RequiredFields
     */
    public function getCMSValidator(): RequiredFields
    {
        return RequiredFields::create([
            'HeroTitle',
        ]);
    }

    protected function buildMetaAreaFields(FieldList $fields): void
    {
        parent::buildMetaAreaFields($fields);

        $fields->addFieldToTab(
            'Root.Main',
            $metaTitle = TextField::create('HeroTitle', $this->fieldLabel('HeroTitle')),
            'MenuTitle'
        );

        $fields->removeByName([
        ]);
    }
}
