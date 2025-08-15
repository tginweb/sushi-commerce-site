<?php

namespace Main\Entity\D7;

class HLQuery extends D7Query
{
    public function sort($by, $order = 'ASC')
    {
        $sort = is_array($by) ? $by : [$by => $order];
        foreach ($sort as $key => $order) {
            if (strpos($key, 'UF_') !== 0 && $key !== 'ID') {
                $this->sort['UF_' . $key] = $order;
            } else {
                $this->sort[$key] = $order;
            }
        }
        return $this;
    }
}
