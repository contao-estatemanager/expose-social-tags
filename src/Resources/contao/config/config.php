<?php
/**
 * This file is part of Contao EstateManager.
 *
 * @link      https://www.contao-estatemanager.com/
 * @source    https://github.com/contao-estatemanager/expose-social-tags
 * @copyright Copyright (c) 2019  Oveleon GbR (https://www.oveleon.de)
 * @license   https://www.contao-estatemanager.com/lizenzbedingungen.html
 */

// HOOKS
$GLOBALS['TL_HOOKS']['compileRealEstateExpose'][] = array('ContaoEstateManager\\ExposeSocialTags\\SocialTags', 'setSocialTags');
