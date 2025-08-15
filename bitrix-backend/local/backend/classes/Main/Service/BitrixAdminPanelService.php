<?php

namespace Main\Service;

use function TG\Main\Service\AddEventHandler;
use const TG\Main\Service\ADMIN_SECTION;

class BitrixAdminPanelService extends BaseService
{

    function embedAdminHtml($htmlStart, $htmlEnd)
    {
        if (
            !defined('ADMIN_SECTION') ||
            ADMIN_SECTION !== true ||
            strpos($GLOBALS['APPLICATION']->GetCurPage(), '/bitrix/admin/') !== 0
        ) {
            return;
        }

        AddEventHandler("main", "OnEndBufferContent", function (&$content) use ($htmlStart, $htmlEnd) {
            // Проверяем наличие тега body и отсутствие AJAX-заголовков
            $isHtmlPage = (stripos($content, '<body') !== false);
            $isNotAjax = (
                strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'text/html') !== false &&
                empty($_SERVER['HTTP_BX_AJAX']) &&
                empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            );

            if ($isHtmlPage && $isNotAjax) {

                // Вставка после открывающего <body>
                if ($htmlStart) {
                    if (is_callable($htmlStart)) {
                        $htmlStart = $htmlStart();
                    }
                    $content = preg_replace(
                        '/(<body[^>]*>)/i',
                        '$1' . $htmlStart,
                        $content,
                        1
                    );
                }

                // Вставка перед закрывающим </body>
                if ($htmlEnd) {
                    if (is_callable($htmlEnd)) {
                        $htmlEnd = $htmlEnd();
                    }
                    $content = preg_replace(
                        '/<\/body>/i',
                        $htmlEnd . '$0',
                        $content,
                        1
                    );
                }
            }
        });
    }
}
