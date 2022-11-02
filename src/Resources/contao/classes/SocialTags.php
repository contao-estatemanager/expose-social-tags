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

namespace ContaoEstateManager\ExposeSocialTags;

use Contao\Controller;
use Contao\Environment;
use Contao\FilesModel;
use Contao\PageModel;
use Contao\StringUtil;
use Contao\System;
use ContaoEstateManager\FilesHelper;
use ContaoEstateManager\RealEstate;

class SocialTags extends Controller
{
    /**
     * Set OpenGraph meta tags.
     *
     * @param $objTemplate
     * @param $objRealEstate
     * @param $context
     */
    public function setSocialTags(&$objTemplate, $objRealEstate, $context): void
    {
        if (!$context->addSocialTags)
        {
            return;
        }

        $realEstate = new RealEstate($objRealEstate);

        if (null === ($objFile = FilesModel::findOneByUuid($realEstate->getMainImageUuid())))
        {
            return;
        }

        if (!file_exists(System::getContainer()->getParameter('kernel.project_dir') . '/' . $objFile->path))
        {
            return;
        }

        $arrData = [
            'singleSRC' => $objFile->path,
        ];

        if ('' !== $context->imgSize)
        {
            $size = StringUtil::deserialize($context->imgSize);

            if ($size[0] > 0 || $size[1] > 0 || is_numeric($size[2]))
            {
                $arrData['size'] = $context->imgSize;
            }
        }

        $this->addImageToTemplate($objTemplate, $arrData, null, null, $objFile);

        $picture = $objTemplate->picture['img'];

        $base = Environment::get('base');
        $imageUrl = $base.$picture['src'];
        $type = 'image/'. strtolower(FilesHelper::fileExt($picture['src']));
        $width = $picture['width'] ?? null;
        $height = $picture['height'] ?? null;
        $url = $base.Environment::get('request');
        $arrTexts = $realEstate->getTexts(['objektbeschreibung'], 200);

        $description = '';

        if (isset($arrTexts['objektbeschreibung']))
        {
            $description = $arrTexts['objektbeschreibung']['value'];
        }

        /** @var PageModel $objPage */
        global $objPage;
        $pageDetails = $objPage->loadDetails();

        $GLOBALS['TL_HEAD'][] = '<meta prefix="og: http://ogp.me/ns#" property="og:title" content="'.$realEstate->title.'">';
        $GLOBALS['TL_HEAD'][] = '<meta prefix="og: http://ogp.me/ns#" property="og:image" content="'.$imageUrl.'">';
        $GLOBALS['TL_HEAD'][] = '<meta prefix="og: http://ogp.me/ns#" property="og:image:type" content="'.$type.'">';
        $GLOBALS['TL_HEAD'][] = '<meta prefix="og: http://ogp.me/ns#" property="og:image:width" content="'.$width.'">';
        $GLOBALS['TL_HEAD'][] = '<meta prefix="og: http://ogp.me/ns#" property="og:image:height" content="'.$height.'">';
        $GLOBALS['TL_HEAD'][] = '<meta prefix="og: http://ogp.me/ns#" property="og:url" content="'.$url.'">';
        $GLOBALS['TL_HEAD'][] = '<meta prefix="og: http://ogp.me/ns#" property="og:description" content="'.$description.'">';
        $GLOBALS['TL_HEAD'][] = '<meta prefix="og: http://ogp.me/ns#" property="og:site_name" content="'.$pageDetails->rootTitle.'">';
    }
}
