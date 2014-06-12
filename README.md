#Woof - A simple DataDogStatsD Client for PHP 5.4+

[![Build Status](https://travis-ci.org/squinones/woof.svg?branch=master)](https://travis-ci.org/squinones/woof)

Woof is a simple PHP client for the DataDog agent, used to send metrics information to DataDog.

Woof uses non-blocking UDP connections to send data with a minimal risk to application perfomance and is *deeply* inspired by [Alex Corsley's library](https://github.com/anthroprose/php-datadogstatsd)

##Usage
(Shamelessly cribbed from here: http://docs.datadoghq.com/guides/dogstatsd/)

###Creating a client
```php
// Create a new client (localhost:8125 is the default)
$woof = new Woof("localhost", 8125);
```

###Gauges
Gauges measure the value of a particular thing at a particular time, like the amount of fuel in a car’s gas tank or the number of users connected to a system. 
```php
$woof->gauge("gas_tank.level", 0.75);
$woof->gauge("users.active", 1001);
```

###Counters
Counters track how many times something happened per second, like the number of database requests or page views.
```php
$woof->increment("database.query.count");
$woof->increment("page_view.count", 10);
$woof->decrement("available.threads");
```

###Histograms
Histograms track the statistical distribution of a set of values, like the duration of a number of database queries or the size of files uploaded by users. Each histogram will track the average, the minimum, the maximum, the median and the 95th percentile.
```php
$woof->histogram("database.query.time", 0.5);
$woof->histogram("file.upload.size", filesize($file));
```

###Sets
Sets are used to count the number of unique elements in a group. If you want to track the number of unique visitor to your site, sets are a great way to do that.
```php
$woof->set("users.uniques", $user->getId());
```

###Timers
StatsD only supports histograms for timing, not generic values (like the size of uploaded files or the number of rows returned from a query). Timers are essentially a special case of histograms, so they are treated in the same manner by DogStatsD for backwards compatibility.
```php
$woof->timer("response.time", 200);
```

###Tags
Tags are a Datadog specific extension to StatsD. They allow you to tag a metric with a dimension that’s meaningful to you and slice and dice along that dimension in your graphs. For example, if you wanted to measure the performance of two video rendering algorithms, you could tag the rendering time metric with the version of the algorithm you used.
```php
$woof->increment("api.requests", 1, ["api"]);               // adds #api tag
$woof->increment("api.errors", 1, ["error_code" => 400]);   // adds #error_code:400 tag
```

###Sample Rates
The overhead of sending UDP packets can be too great for some performance intensive code paths. To work around this, StatsD clients support sampling, that is to say, only sending metrics a percentage of the time. For example:
```php
$woof->histogram("my.histogram", 1, [], 0.5);
```
will only be sent to the server about half of the time, but it will be multipled by the sample rate to provide an estimate of the real data.

###Events
Not currently supported