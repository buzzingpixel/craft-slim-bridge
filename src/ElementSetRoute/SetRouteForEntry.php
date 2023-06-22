<?php

declare(strict_types=1);

namespace BuzzingPixel\SlimBridge\ElementSetRoute;

use BuzzingPixel\SlimBridge\ElementSetRoute\RouteParsing\RouteParser;
use BuzzingPixel\SlimBridge\ElementSetRoute\SetRouteFromParsed\SetRouteFromParsedFactory;
use craft\elements\Entry;
use craft\errors\SiteNotFoundException;
use craft\events\SetElementRouteEvent;
use craft\models\Section_SiteSettings;
use craft\services\Sites;
use yii\base\InvalidConfigException;

use function array_filter;
use function array_values;
use function assert;

// phpcs:disable Squiz.Functions.MultiLineFunctionDeclaration.FirstParamSpacing

class SetRouteForEntry implements SetRouteContract
{
    public function __construct(
        private Entry $entry,
        private Sites $sitesService,
        private RouteParser $routeParser,
        private SetElementRouteEvent $event,
        private SetRouteFromParsedFactory $setRouteFromParsedFactory,
    ) {
    }

    /**
     * @throws SiteNotFoundException
     * @throws InvalidConfigException
     */
    public function set(): void
    {
        $sectionSiteSettings = $this->entry->getSection()->getSiteSettings();

        $currentSiteId = (int) $this->sitesService->getCurrentSite()->id;

        $thisSiteSettings = array_values(array_filter(
            $sectionSiteSettings,
            static fn (
                Section_SiteSettings $s
            ) => $s->siteId === $currentSiteId,
        ))[0];

        assert($thisSiteSettings instanceof Section_SiteSettings);

        $this->setRouteFromParsedFactory->make(
            event: $this->event,
            parsedRoute: $this->routeParser->parseRouteString(
                fullRouteString: (string) $thisSiteSettings->template,
            ),
        )->set();
    }
}
