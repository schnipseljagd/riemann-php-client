riemann-php-client
==================

http://riemann.io/quickstart.html

query the events:
```
$ irb -r riemann/client
ruby-1.9.3 :001 > r = Riemann::Client.new
 => #<Riemann::Client ... >
ruby-1.9.3 :003 > r['service =~ "php%"']
```