<?php
/**
 * This file is part of Contao EstateManager.
 *
 * @link      https://www.contao-estatemanager.com/
 * @source    https://github.com/contao-estatemanager/expose-social-tags
 * @copyright Copyright (c) 2019  Oveleon GbR (https://www.oveleon.de)
 * @license   https://www.contao-estatemanager.com/lizenzbedingungen.html
 */

namespace ContaoEstateManager\ExposeSocialTags;

use Contao\Environment;
use ContaoEstateManager\FilesHelper;

class SocialTags extends \Controller
{

    /**
     * Set OpenGraph meta tags
     *
     * @param $objTemplate
     * @param $realEstate
     * @param $context
     */
    public function setSocialTags(&$objTemplate, $realEstate, $context)
    {
        if (!$context->addSocialTags)
        {
            return;
        }

        $objFile = \FilesModel::findOneByUuid($realEstate->getMainImage());

        $arrData = array
        (
            'singleSRC' => $objFile->path
        );

        if ($context->imgSize != '')
        {
            $size = \StringUtil::deserialize($context->imgSize);

            if ($size[0] > 0 || $size[1] > 0 || is_numeric($size[2]))
            {
                $arrData['size'] = $context->imgSize;
            }
        }

        $this->addImageToTemplate($objTemplate, $arrData, null, null, $objFile);

        $picture = $objTemplate->picture['img'];

        $base = Environment::get('base');
        $imageUrl = $base . $picture['src'];
        $type = 'image/' . strtolower(FilesHelper::fileExt($picture['src']));
        $width = $picture['width'];
        $height = $picture['height'];
        $url = $base . Environment::get('request');
        $description = $realEstate->getTexts(['objektbeschreibung'])['objektbeschreibung']['value'] ? substr($realEstate->getTexts(['objektbeschreibung'])['objektbeschreibung']['value'], 0, 200).'...' : '';

        /** @var \PageModel $objPage */
        global $objPage;
        $pageDetails = $objPage->loadDetails();

        $GLOBALS['TL_HEAD'][] = '<meta prefix="og: http://ogp.me/ns#" property="og:title" content="'.$realEstate->getTitle().'">';
        $GLOBALS['TL_HEAD'][] = '<meta prefix="og: http://ogp.me/ns#" property="og:image" content="'.$imageUrl.'">';
        $GLOBALS['TL_HEAD'][] = '<meta prefix="og: http://ogp.me/ns#" property="og:image:type" content="'.$type.'">';
        $GLOBALS['TL_HEAD'][] = '<meta prefix="og: http://ogp.me/ns#" property="og:image:width" content="'.$width.'">';
        $GLOBALS['TL_HEAD'][] = '<meta prefix="og: http://ogp.me/ns#" property="og:image:height" content="'.$height.'">';
        $GLOBALS['TL_HEAD'][] = '<meta prefix="og: http://ogp.me/ns#" property="og:url" content="'.$url.'">';
        $GLOBALS['TL_HEAD'][] = '<meta prefix="og: http://ogp.me/ns#" property="og:description" content="'.$description.'">';
        $GLOBALS['TL_HEAD'][] = '<meta prefix="og: http://ogp.me/ns#" property="og:site_name" content="'.$pageDetails->rootTitle.'">';
    }
}