<?php
namespace Riemann\Test;

use DrSlump\Protobuf;
use Riemann\Client;
use Riemann\Event;
use Riemann\Msg;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    const A_HOST = 'localhost';
    const A_PORT = 5555;

    /**
     * @test
     */
    public function itShouldSendMessage()
    {
        $message = $this->aMessage();

        $socketClient = $this->socketClientMock();
        $socketClient->expects($this->at(0))
            ->method('open');
        $socketClient->expects($this->at(1))
            ->method('write')
            ->with(Protobuf::encode($message));
        $socketClient->expects($this->at(2))
            ->method('close');
        $client = new Client($socketClient, $this->eventBuilderFactoryMock());
        $client->flush();
    }

    /**
     * @test
     */
    public function itShouldSendEvents()
    {
        $anEvent = new Event();
        $anotherEvent = new Event();
        $message = $this->aMessage(
            array(
                $anEvent,
                $anotherEvent,
            )
        );

        $socketClient = $this->socketClientMock();
        $socketClient->expects($this->once())
            ->method('write')
            ->with(Protobuf::encode($message));
        $client = new Client($socketClient, $this->eventBuilderFactoryMock());
        $client->sendEvent($anEvent);
        $client->sendEvent($anotherEvent);
        $client->flush();
    }
    
    /**
     * @test
     */
    public function itShouldReturnANewEventBuilder()
    {
        $eventBuilder = $this->eventBuilderMock();
        $eventBuilder->expects($this->once())
            ->method('setClient')
            ->with($this->isInstanceOf('Riemann\Client'));
        $eventBuilderFactory = $this->eventBuilderFactoryMock();
        $eventBuilderFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($eventBuilder));
        $client = new Client($this->socketClientMock(), $eventBuilderFactory);

        $this->assertThat(
            $client->getEventBuilder(),
            $this->equalTo($eventBuilder)
        );
    }

    private function socketClientMock()
    {
        return $this->getMock('Thrift\Transport\TSocket');
    }

    private function aMessage($events = array())
    {
        $message = new Msg();
        $message->ok = true;
        $message->events = $events;
        return $message;
    }

    private function eventBuilderFactoryMock()
    {
        return $this->getMockBuilder('Riemann\EventBuilderFactory')
            ->disableOriginalConstructor()
            ->getMock();
    }

    private function eventBuilderMock()
    {
        return $this->getMockBuilder('Riemann\EventBuilder')
            ->disableOriginalConstructor()
            ->getMock();
    }
}
