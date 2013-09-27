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

    private $eventBuilderFactory;

    public function __construct(TSocket $socketClient, EventBuilderFactory $eventBuilderFactory)
    {
        $this->socketClient = $socketClient;
        $this->eventBuilderFactory = $eventBuilderFactory;
    }

    public static function create($host, $port, $persist = false)
    {
        return new self(
            new TSocket("udp://$host", $port, $persist),
            new EventBuilderFactory()
        );
    }

    public function getEventBuilder()
    {
        $builder = $this->eventBuilderFactory->create();
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
        $this->socketClient->open();
        $this->socketClient->write(Protobuf::encode($message));
        $this->socketClient->close();
    }
}
