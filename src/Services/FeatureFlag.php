<?php

namespace App\Services;

class FeatureFlag
{
    /**
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function check(string $featureFlag): bool
    {
        return !empty($_ENV[$featureFlag]) ? true : false;
    }
}
