<?php

namespace Main\Entity\Model\Traits;

use Bitrix\Main\ObjectException;
use Bitrix\Main\Type\DateTime;
use Main\Entity\D7\D7Model;

trait DeactivationTrait
{
    /**
     * Active element.
     */
    public function activate()
    {
        $this->markForActivation()->save();
    }

    /**
     * @return $this
     */
    public function markForActivation()
    {
        $this['UF_DEACTIVATED_AT'] = null;

        return $this;
    }

    /**
     * Deactivate element.
     */
    public function deactivate()
    {
        $this->markForDeactivation()->save();
    }

    /**
     * @return $this
     * @throws ObjectException
     */
    public function markForDeactivation()
    {
        $this['UF_DEACTIVATED_AT'] = $this instanceof D7Model ? new DateTime() : date('Y-m-d H:i:s');

        return $this;
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $this instanceof D7Model
            ? $query->filter(['==UF_DEACTIVATED_AT' => null])
            : $query->whereNull('UF_DEACTIVATED_AT');
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeDeactivated($query)
    {
        return $this instanceof D7Model
            ? $query->filter(['!==UF_DEACTIVATED_AT' => null])
            : $query->whereNotNull('UF_DEACTIVATED_AT');
    }
}
