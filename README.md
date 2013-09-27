riemann-php-client
==================

http://riemann.io/quickstart.html

Uses thrift socket transport atm, but there is no real need for this dependency.

example client
```php
use Riemann\Client;

require __DIR__ . '/vendor/autoload.php';

$riemannClient = Client::create('localhost', 5555);

$eventBuilder = $riemannClient->getEventBuilder();
$eventBuilder->setService("php stuff");
$eventBuilder->setMetric(mt_rand(0, 99));
$eventBuilder->addTag('histogram');
$eventBuilder->sendEvent();

$eventBuilder = $riemannClient->getEventBuilder();
$eventBuilder->setService("php stuff2");
$eventBuilder->setMetric(mt_rand(99, 199));
$eventBuilder->addTag('meter');
$eventBuilder->sendEvent();

$riemannClient->flush();
```

query the events:
```ruby
$ irb -r riemann/client
ruby-1.9.3 :001 > r = Riemann::Client.new
 => #<Riemann::Client ... >
ruby-1.9.3 :003 > r['service =~ "php%"']
```
