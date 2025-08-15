<?php

namespace Offer\Core;

use Bitrix;
use Main\Service\BaseService;
use Offer\Core\Entity\OfferModel;

class Service extends BaseService
{
    public $commonOffers;

    function getCommonOffersByScope()
    {
        return $this->getCommonOffers()->groupBy('IBLOCK_SECTION_CODE');
    }

    function getCommonOffers()
    {
        if (!isset($this->commonOffers)) {

            $this->commonOffers = OfferModel::query()->filter([
                'IBLOCK_SECTION_ID' => 127
            ])->active()->withViewList()->getList();

            /*
            $this->commonOffers = PromoElementModel::query()
                ->active()
                ->withViewPublic()
                ->getList()
                ->map(function (PromoElementModel $element) {

                    $offer = new OfferModel();
                    $offer->fields = $element->fields;

                    $offer['PROPERTY_BANNER_HOR_DESKTOP_VALUE'] = $element->getProp('INDEX_PAGE_PIC');
                    $offer['PROPERTY_BANNER_HOR_MOBILE_VALUE'] = $element->getProp('INDEX_PAGE_PIC_MOBILE');
                    $offer['PROPERTY_BANNER_SQUARE_VALUE'] = $element['DETAIL_PICTURE'] ?: $element['PREVIEW_PICTURE'];

                    $offer['DETAIL_TEXT'] = $element['DETAIL_TEXT'];
                    $offer['PREVIEW_TEXT'] = $element['PREVIEW_TEXT'];

                    $offer['PROPERTY_BG_COLOR_VALUE'] = $element['PROPERTY_BG_COLOR_VALUE'];
                    $offer['PROPERTY_IS_HOT_VALUE'] = $element['PROPERTY_IS_HOT_VALUE'];

                    $offer['PROPERTY_SLIDES_VALUE'] = [$offer['PROPERTY_BANNER_SQUARE_VALUE']];

                    $offer['PROPERTY_ACTION_SECTION_ID_VALUE'] = $element['PROPERTY_ACTION_SECTION_ID_VALUE'];
                    $offer['PROPERTY_ACTION_TITLE_VALUE'] = $element['PROPERTY_ACTION_TITLE_VALUE'];

                    $offer['PROPERTY_ACTION_WEB_URL_VALUE'] = $element['PROPERTY_ACTION_WEB_URL_VALUE'];
                    $offer['PROPERTY_ACTION_WEB_PROPS_VALUE'] = $element['PROPERTY_ACTION_WEB_PROPS_VALUE'];

                    $offer['PROPERTY_ACTION_MOBILE_URL_VALUE'] = $element['PROPERTY_ACTION_MOBILE_URL_VALUE'];
                    $offer['PROPERTY_ACTION_MOBILE_PROPS_VALUE'] = $element['PROPERTY_ACTION_MOBILE_PROPS_VALUE'];

                    //$offer->setPropValue('BANNER_HOR_DESKTOP', $element->getProp('INDEX_PAGE_PIC'));
                    //$offer->setPropValue('BANNER_HOR_MOBILE', $element->getProp('INDEX_PAGE_PIC_MOBILE'));
                    //$offer->setPropValue('BANNER_SQUARE', $element['DETAIL_IMAGE'] || $element['LIST_IMAGE']);
                    return $offer;
                });
            */
        }
        return $this->commonOffers;
    }

    function getIndividualOffers()
    {

        $userId = $this->container->getUserId();
        $user = $this->container->getUser();

        $result = [];

        if ($userId) {

            $card = $this->container->getSaleClientCardService()->getCurrentUserCard();

            if (!$card)
                return [];

            $offers = OfferModel::query()->filter([
                'IBLOCK_SECTION_ID' => 118
            ])->withViewList()->getList();

            if ($offers) {
                $offersByDiscountCode = $offers->pluck(null, 'PROPERTY_COND_DISCOUNT_CODE_VALUE');
                $offersByCode = $offers->pluck(null, 'CODE');
            }

            $birthdayOffer = $offersByCode['birthday'];

            if ($birthdayOffer && $user->isBirthday()) {
                $birthdayOffer['VID'] = $birthdayOffer['ID'] . '-' . $user->getBirthday();
                $result[] = $birthdayOffer;
            } else {

                $birthdayNearOffer = $offersByCode['birthday_near'];

                if ($birthdayNearOffer && $user->isBirthdayNear()) {
                    $birthdayNearOffer['VID'] = $birthdayNearOffer['ID'] . '-' . $user->getBirthday();
                    $result[] = $birthdayNearOffer;
                }
            }

            foreach ($card->getDiscounts() as $discount) {
                $discountOffer = $offersByDiscountCode[$discount['CODE']];
                if ($discountOffer) {
                    //$discountOffer->setContextVars($discount->fields);
                    $result[] = $discountOffer;
                }
            }
        }

        return $result;
    }

    function getSharedOffers()
    {

    }
}



