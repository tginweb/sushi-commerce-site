<?php

namespace Main\Service;

class ThemeService extends BaseService
{
    function svgIcon($name, $class = '', $fill = "none", $stroke = "currentColor", $viewBox = '0 0 24 24')
    {
        $options = [
            'fill' => $fill,
            'stroke' => $stroke,
            'viewBox' => $viewBox
        ];

        if (is_array($class)) {
            $options = $class + $options;
        } else {
            $options['class'] = $class;
        }

        $icon = $this->svgIcons[$name];

        ?>

        <svg class="<?= $options['class'] ?>" xmlns="http://www.w3.org/2000/svg" fill="<?= $options['fill'] ?>"
             viewBox="<?= $options['viewBox'] ?>"
             stroke="<?= $options['stroke'] ?>">
            <?= $icon; ?>
        </svg>

        <?php

    }
}
