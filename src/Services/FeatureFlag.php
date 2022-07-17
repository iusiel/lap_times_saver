<?php

namespace App\Services;

class FeatureFlag
{
    public function check(string $featureFlag): bool
    {
        return !empty($_ENV[$featureFlag]) ? true : false;
    }
}
