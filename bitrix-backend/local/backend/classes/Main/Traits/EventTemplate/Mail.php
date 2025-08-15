<?php

namespace Main\Traits\EventTemplate;

trait Mail
{
    function renderMailContent($groupContent, $groupHeader = null, $style = 'margin: 0 0 0 0;', $headerStyle = '', $itemStyle = 'margin: 0 0 7px 0;')
    {
        if (is_array($groupContent)) {
            $lines = [];
            foreach ($groupContent as $fieldName => $field) {

                $line = '<div style="' . $itemStyle . '">';

                if ($field['NAME']) {
                    $line .= '<span style="color: #999;margin-right: 10px;">' . $field['NAME'] . ':</span>';
                }

                if ($field['VALUE']) {
                    $value = $field['VALUE'];
                } else if ($field['PRICE']) {
                    $value = $this->formatPrice($field['PRICE']);
                } else {
                    $value = null;
                }

                if ($value)
                    $line .= '<span>' . $value . '</span>';

                $line .= '</div>';

                $lines[] = $line;
            }
            $groupContent = join('', $lines);
        }

        $out = '<div style="' . $style . '">';

        if ($groupHeader) {
            $out .= '<div style="' . $headerStyle . '"><b>' . $groupHeader . '</b></div>';
        }

        $out .= $groupContent;

        $out .= '</div>';

        return $out;
    }
}
