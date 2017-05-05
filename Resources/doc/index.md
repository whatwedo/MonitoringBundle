# Getting Started

This documentation provides a basic view of the possibilities of the whatwedoMonitoringBundle. 
The documentation will be extended while developing the bundle.

## Requirements

This bundle has been tested on PHP >= 7.0 and Symfony >= 3.0. 
We don't guarantee that it works on lower versions.


## Installation

First, add the bundle to your dependencies and install it.

```
composer require whatwedo/monitoring-bundle
```

Secondly, enable this bundle in your kernel.

```
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new whatwedo\MonitoringBundle\whatwedoMonitoringBundle(),
        // ...
    );
}
```

If you want want to check the status over HTTP(S) you need to add the following route to your `routing.yml` file.

```
whatwedo_monitoring:
    resource: "@whatwedoMonitoringBundle/Resources/config/routing.yml"
    prefix:   /monitoring
```

That's it!

## Use the bundle

This bundle uses a zero configuration principle. The enabled checks are selected automatically according to your other enabled bundles. All unnecessary checks will be skipped automatically. 

### Command line

You can run the checks by running the following command:

```
bin/console whatwedo:monitoring:check
```

If all checks run without an error the exit code will be 0. If there any failure or warning in any check the exit code will be 1. 

## HTTP(S)

It's also possible to get the current check status by running a HTTP(S) check in your existing monitoring system against `[YOUR-APPLICATION]/monitoring`. If there any failed checks the HTTP status code will be 500. If all checks run without failure or warning the status code will be 200.

## More resources

- [Included checks](included-checks.md)
* [Add your own checks](own-checks.md)
