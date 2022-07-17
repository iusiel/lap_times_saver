<?php

namespace App\Twig;

use App\Services\FeatureFlag;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private $featureFlagService;

    public function __construct(FeatureFlag $featureFlagService)
    {
        $this->featureFlagService = $featureFlagService;
    }

    public function getFunctions()
    {
        return [new TwigFunction('featureFlag', [$this, 'checkFeatureFlag'])];
    }

    public function checkFeatureFlag(string $featureFlag): bool
    {
        return $this->featureFlagService->check($featureFlag);
    }
}
