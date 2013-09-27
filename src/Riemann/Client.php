<?php
namespace Riemann;

use DrSlump\Protobuf;
use Thrift\Transport\TSocket;

class Client
{
    /**
     * @var Event[]
     */
    private $events;

    public function __construct()
    {
        $this->socketClient = new TSocket("udp://localhost", 5555);
    }

    public function getEventBuilder()
    {
        $builder = new EventBuilder(
            new DateTimeProvider(),
            'some host',
            array('some tag')
        );
        $builder->setClient($this);
        return $builder;
    }

    public function sendEvent(Event $event)
    {
        $this->events[] = $event;
    }

    public function flush()
    {
        $message = new Msg();
        $message->ok = true;
        $message->events = $this->events;
        var_dump($message->events);
        $this->socketClient->open();
        $this->socketClient->write(Protobuf::encode($message));
        $this->socketClient->close();
    }
}
