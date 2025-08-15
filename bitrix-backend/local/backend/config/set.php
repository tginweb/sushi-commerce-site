<?php

$settings['SALE_DELIVERY_FREE_FROM_PRICE'] = 800;
$settings['SALE_RESERVE_MIN_TIME_COURIER'] = 30;
$settings['SALE_RESERVE_MIN_TIME_PICKUP'] = 20;
$settings['CATALOG_FRONT_SHOW_POPULAR_WIDGET'] = true;
$settings['CATALOG_FRONT_SHOW_FAV_TAB'] = true;
$settings['CATALOG_FRONT_SHOW_POPULAR_TAB'] = true;
$settings['IMAGE_DEV_BASE_URL'] = $this->container->getConfigService()->get('IMAGE.IMAGE_DEV_BASE_URL');
$settings['IMAGE_BASE_URL'] = $this->container->getConfigService()->get('IMAGE.BASE_URL');
$settings['IMAGE_STYLER_TEMPLATE'] = $this->container->getImageService()->getStylerProvider()->getTemplate();
$settings['USER_CONFIRM_PHONE_NEED'] = $this->container->getUserService()->confirmPhoneNeed();
$settings['USER_CONFIRM_EMAIL_NEED'] = $this->container->getUserService()->confirmEmailNeed();
