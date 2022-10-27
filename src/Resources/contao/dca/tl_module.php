<?php

declare(strict_types=1);

/*
 * This file is part of Contao EstateManager.
 *
 * @see        https://www.contao-estatemanager.com/
 * @source     https://github.com/contao-estatemanager/expose-social-tags
 * @copyright  Copyright (c) 2021 Oveleon GbR (https://www.oveleon.de)
 * @license    https://www.contao-estatemanager.com/lizenzbedingungen.html
 */

use Contao\CoreBundle\DataContainer\PaletteManipulator;

// Extend the default palettes
PaletteManipulator::create()
    ->addLegend('social_tags_legend', 'module_legend', PaletteManipulator::POSITION_AFTER)
    ->addField('addSocialTags', 'social_tags_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('realEstateExpose', 'tl_module')
;

// Add selector
$GLOBALS['TL_DCA']['tl_module']['palettes']['__selector__'][] = 'addSocialTags';

// Add subpalette
$GLOBALS['TL_DCA']['tl_module']['subpalettes']['addSocialTags'] = 'imgSize';

// Add field
$GLOBALS['TL_DCA']['tl_module']['fields']['addSocialTags'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_module']['addSocialTags'],
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => ['submitOnChange' => true, 'tl_class' => 'w50'],
    'sql' => "char(1) NOT NULL default ''",
];
