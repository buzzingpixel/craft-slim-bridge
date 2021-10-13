<?php

declare(strict_types=1);

namespace BuzzingPixel\SlimBridge\ElementSetRoute;

use BuzzingPixel\SlimBridge\ElementSetRoute\RouteParsing\ParsedRoute;
use BuzzingPixel\SlimBridge\ElementSetRoute\RouteParsing\RouteParser;
use BuzzingPixel\SlimBridge\ElementSetRoute\SetRouteFromParsed\SetRouteContract;
use BuzzingPixel\SlimBridge\ElementSetRoute\SetRouteFromParsed\SetRouteFromParsedFactory;
use craft\elements\Category;
use craft\errors\SiteNotFoundException;
use craft\events\SetElementRouteEvent;
use craft\models\CategoryGroup_SiteSettings;
use craft\models\Site;
use craft\services\Categories;
use craft\services\Sites;
use PHPUnit\Framework\TestCase;

/** @psalm-suppress PropertyNotSetInConstructor */
class SetRouteForCategoryTest extends TestCase
{
    /**
     * @throws SiteNotFoundException
     */
    public function testSet(): void
    {
        $parsedRoute = new ParsedRoute(
            isMatch: true,
            routeString: 'testRouteString',
        );

        $siteSettings1           = new CategoryGroup_SiteSettings();
        $siteSettings1->siteId   = 123;
        $siteSettings1->template = 'test/template/123';

        $siteSettings2           = new CategoryGroup_SiteSettings();
        $siteSettings2->siteId   = 456;
        $siteSettings2->template = 'test/template/456';

        $siteSettings = [
            $siteSettings1,
            $siteSettings2,
        ];

        $categoryStub = $this->createMock(Category::class);

        $categoryStub->groupId = 789;

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

        $categoriesServiceSpy = $this->createMock(
            Categories::class,
        );

        $categoriesServiceSpy->expects(self::once())
            ->method('getGroupSiteSettings')
            ->with(self::equalTo(789))
            ->willReturn($siteSettings);

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

        $set = new SetRouteForCategory(
            category: $categoryStub,
            sitesService: $sitesServiceStub,
            routeParser: $routeParserSpy,
            event: $eventStub,
            categoriesService: $categoriesServiceSpy,
            setRouteFromParsedFactory: $setRouteFromParsedFactorySpy,
        );

        $set->set();
    }
}
