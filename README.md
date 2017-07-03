[![Build Status](https://travis-ci.org/2dotstwice/silex-feature-toggles-provider.svg?branch=master)](https://travis-ci.org/2dotstwice/silex-feature-toggles-provider)

# Installation

## Service provider

Register the _FeatureTogglesProvider_ with your Silex application.
_$featureToggleConfiguration_ should be an array with the toggle configuration. 
You can for example retrieve this from a configuration file, but how to handle 
that is up to your application.

```php
$app->register(
    new \TwoDotsTwice\SilexFeatureToggles\FeatureTogglesProvider(
        $featureToggleConfiguration
    )
);
```

At 2dotstwice, we've been successfully using this together with DerAlex's 
[YamlConfigServiceProvider](https://github.com/deralex/YamlConfigServiceProvider).

```php
$app->register(new \DerAlex\Silex\YamlConfigServiceProvider(__DIR__ . '/config.yml'));

$app->register(
    new \TwoDotsTwice\SilexFeatureToggles\FeatureTogglesProvider(
        $app['config']['toggles']
    )
);
```

The contents of _config.yml_ might look like this:
 
```yaml
toggles:
  profile-date-of-birth:
    name: profile-date-of-birth
    conditions: {}
    status: always-active
  remember-password-option:
    name: remember-password-option
    conditions: {}
    status: inactive
```

For details on the configuration semantics, consult the documentation of Qandidate's
[Toggle library](https://github.com/qandidate-labs/qandidate-toggle).

## Tiny REST API

Additionally, you can add a small REST API which exposes the state of the
toggles by mounting the _FeatureTogglesControllerProvider_ in your Silex
application.
This might be useful for manual inspection, or when the state of the toggles
is needed on a decoupled front-end.

```php
$app->mount('/', new \TwoDotsTwice\SilexFeatureToggles\FeatureTogglesControllerProvider());
```

A request to /toggles will return a JSON response. An example of a response body:

```json
{
    "profile-date-of-birth": true,
    "remember-password-option": false
}
```

_true_ means the toggle is currently ON, _false_ means it is OFF.
