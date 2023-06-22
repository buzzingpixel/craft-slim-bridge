<?php

declare(strict_types=1);

namespace BuzzingPixel\SlimBridge\ElementSetRoute;

use BuzzingPixel\SlimBridge\ElementSetRoute\RouteParsing\RouteParser;
use BuzzingPixel\SlimBridge\ElementSetRoute\SetRouteFromParsed\SetRouteFromParsedFactory;
use craft\elements\Category;
use craft\errors\SiteNotFoundException;
use craft\events\SetElementRouteEvent;
use craft\models\CategoryGroup_SiteSettings;
use craft\services\Categories;
use craft\services\Sites;

use function array_filter;
use function array_values;
use function assert;

// phpcs:disable Squiz.Functions.MultiLineFunctionDeclaration.FirstParamSpacing

class SetRouteForCategory implements SetRouteContract
{
    public function __construct(
        private Category $category,
        private Sites $sitesService,
        private RouteParser $routeParser,
        private SetElementRouteEvent $event,
        private Categories $categoriesService,
        private SetRouteFromParsedFactory $setRouteFromParsedFactory,
    ) {
    }

    /**
     * @throws SiteNotFoundException
     */
    public function set(): void
    {
        $groupSiteSettings = $this->categoriesService->getGroupSiteSettings(
            (int) $this->category->groupId,
        );

        $currentSiteId = (int) $this->sitesService->getCurrentSite()->id;

        $thisSiteSettings = array_values(array_filter(
            $groupSiteSettings,
            static fn (
                CategoryGroup_SiteSettings $s
            ) => $s->siteId === $currentSiteId,
        ))[0];

        assert(
            $thisSiteSettings instanceof CategoryGroup_SiteSettings
        );

        $this->setRouteFromParsedFactory->make(
            event: $this->event,
            parsedRoute: $this->routeParser->parseRouteString(
                fullRouteString: (string) $thisSiteSettings->template,
            ),
        )->set();
    }
}
