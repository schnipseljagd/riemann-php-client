<?php
namespace Riemann\Test;

use Riemann\Event;
use Riemann\EventBuilder;

class EventBuilderTest extends \PHPUnit_Framework_TestCase
{
    const SOME_SERVICE = "some.service";
    const A_INT_METRIC = 42;
    const A_TAG = 'aTag';
    const ANOTHER_TAG = 'anotherTag';
    const SOME_SERVER_ROLE_TAG = 'some_server_role';
    const A_FLOAT_METRIC = 12.34;
    const A_HOST = 'some.host';
    const CURRENT_DATETIME = '2013-10-10 12:34:31';
    const CURRENT_TIMESTAMP = '1381401271';

    /**
     * @var EventBuilder
     */
    private $eventBuilder;

    protected function setUp()
    {
        $this->eventBuilder = new EventBuilder(
            $this->dateTimeFactoryMock(),
            self::A_HOST
        );
    }


    /**
     * @test
     */
    public function itShouldSendBuiltEventToClient()
    {
        $service = self::SOME_SERVICE;
        $metric = self::A_INT_METRIC;
        $tag = self::A_TAG;

        $this->eventBuilder
            ->setService($service)
            ->setMetric($metric)
            ->addTag($tag);

        $expectedEvent = new Event();
        $expectedEvent->service = $service;
        $expectedEvent->time = self::CURRENT_TIMESTAMP;
        $expectedEvent->metric_sint64 = $metric;
        $expectedEvent->metric_f = $metric;
        $expectedEvent->tags = array($tag);
        $expectedEvent->host = self::A_HOST;

        $clientMock = $this->getMockBuilder('Riemann\Client')
            ->disableOriginalConstructor()
            ->getMock();
        $clientMock->expects($this->once())
            ->method('sendEvent')
            ->with($this->equalTo($expectedEvent));
        $this->eventBuilder->setClient($clientMock);
        $this->eventBuilder->sendEvent();
    }

    /**
     * @test
     * @expectedException \RuntimeException
     */
    public function itShouldRequireAService()
    {
        $this->eventBuilder->build();
    }

    /**
     * @test
     */
    public function itShouldUseDefaultsIfOnlyAServiceIsGiven()
    {
        $service = self::SOME_SERVICE;
        $this->eventBuilder->setService($service);

        $expectedEvent = new Event();
        $expectedEvent->service = $service;
        $expectedEvent->time = self::CURRENT_TIMESTAMP;
        $expectedEvent->metric_sint64 = EventBuilder::DEFAULT_METRIC;
        $expectedEvent->metric_f = EventBuilder::DEFAULT_METRIC;
        $expectedEvent->tags = array();
        $expectedEvent->host = self::A_HOST;

        $this->assertThat(
            $this->eventBuilder->build(),
            $this->equalTo($expectedEvent)
        );
    }

    /**
     * @test
     */
    public function itShouldApplyAdditionalTagsToInitialTags()
    {
        $initialTag = self::SOME_SERVER_ROLE_TAG;
        $aTag = self::A_TAG;
        $anotherTag = self::ANOTHER_TAG;

        $builder = new EventBuilder(
            $this->dateTimeFactoryMock(),
            self::A_HOST,
            array($initialTag)
        );
        $builder->setService(self::SOME_SERVICE)
            ->addTag($aTag)
            ->addTag($anotherTag);

        $this->assertThat(
            $builder->build(),
            $this->attributeEqualTo('tags', array($initialTag, $aTag, $anotherTag))
        );
    }

    /**
     * @test
     */
    public function itShouldSetFloatAndDoubleMetricsForFloatValues()
    {
        $floatMetric = self::A_FLOAT_METRIC;
        $event = $this->eventBuilder
            ->setService(self::SOME_SERVICE)
            ->setMetric($floatMetric)
            ->build();

        $this->assertThat(
            $event,
            $this->logicalAnd(
                $this->attributeEqualTo('metric_d', $floatMetric),
                $this->attributeEqualTo('metric_f', $floatMetric)
            )
        );
    }

    /**
     * @test
     */
    public function itShouldSetTheCurrentTimeOnBuild()
    {
        $now = new \DateTime();
        $dateTimeProvider = $this->getMock('Riemann\DateTimeProvider');
        $dateTimeProvider->expects($this->once())
            ->method('now')
            ->will($this->returnValue($now));
        $builder = new EventBuilder(
            $dateTimeProvider,
            self::A_HOST
        );
        $builder->setService(self::SOME_SERVICE);
        $this->assertThat(
            $builder->build(),
            $this->attributeEqualTo('time', $now->getTimestamp())
        );
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function dateTimeFactoryMock()
    {
        $now = new \DateTime(self::CURRENT_DATETIME);
        $dateTimeProvider = $this->getMock('Riemann\DateTimeProvider');
        $dateTimeProvider->expects($this->any())
            ->method('now')
            ->will($this->returnValue($now));
        return $dateTimeProvider;
    }
}
