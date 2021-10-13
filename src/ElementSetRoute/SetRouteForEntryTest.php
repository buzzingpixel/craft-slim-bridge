<?php

declare(strict_types=1);

namespace BuzzingPixel\SlimBridge\ElementSetRoute;

use BuzzingPixel\SlimBridge\ElementSetRoute\RouteParsing\ParsedRoute;
use BuzzingPixel\SlimBridge\ElementSetRoute\RouteParsing\RouteParser;
use BuzzingPixel\SlimBridge\ElementSetRoute\SetRouteFromParsed\SetRouteContract;
use BuzzingPixel\SlimBridge\ElementSetRoute\SetRouteFromParsed\SetRouteFromParsedFactory;
use craft\elements\Entry;
use craft\errors\SiteNotFoundException;
use craft\events\SetElementRouteEvent;
use craft\models\Section;
use craft\models\Section_SiteSettings;
use craft\models\Site;
use craft\services\Sites;
use PHPUnit\Framework\TestCase;
use yii\base\InvalidConfigException;

/** @psalm-suppress PropertyNotSetInConstructor */
class SetRouteForEntryTest extends TestCase
{
    /**
     * @throws SiteNotFoundException
     * @throws InvalidConfigException
     */
    public function testSet(): void
    {
        $parsedRoute = new ParsedRoute(
            isMatch: true,
            routeString: 'testRouteString',
        );

        $siteSettings1           = new Section_SiteSettings();
        $siteSettings1->siteId   = 123;
        $siteSettings1->template = 'test/template/123';

        $siteSettings2           = new Section_SiteSettings();
        $siteSettings2->siteId   = 456;
        $siteSettings2->template = 'test/template/456';

        $siteSettings = [
            $siteSettings1,
            $siteSettings2,
        ];

        $sectionStub = $this->createMock(Section::class);

        $sectionStub->method('getSiteSettings')->willReturn(
            $siteSettings,
        );

        $entryStub = $this->createMock(Entry::class);

        $entryStub->method('getSection')->willReturn(
            $sectionStub,
        );

        $site     = $this->createMock(Site::class);
        $site->id = 456;

        $sitesServiceStub = $this->createMock(Sites::class);

        $sitesServiceStub->method('getCurrentSite')->willReturn(
            $site,
        );

        $routeParserSpy = $this->createMock(
            RouteParser::class,
        );

        $routeParserSpy->expects(self::once())
            ->method('parseRouteString')
            ->with(self::equalTo('test/template/456'))
            ->willReturn($parsedRoute);

        $eventStub = $this->createMock(
            SetElementRouteEvent::class,
        );

        $setRouteSpy = $this->createMock(
            SetRouteContract::class,
        );

        $setRouteSpy->expects(self::once())
            ->method('set');

        $setRouteFromParsedFactorySpy = $this->createMock(
            SetRouteFromParsedFactory::class,
        );

        $setRouteFromParsedFactorySpy->expects(self::once())
            ->method('make')
            ->with(
                self::equalTo($parsedRoute),
                self::equalTo($eventStub),
            )
            ->willReturn($setRouteSpy);

        $set = new SetRouteForEntry(
            entry: $entryStub,
            sitesService: $sitesServiceStub,
            routeParser: $routeParserSpy,
            event: $eventStub,
            setRouteFromParsedFactory: $setRouteFromParsedFactorySpy,
        );

        $set->set();
    }
}
