<?php
/**
 * @file
 */

namespace TwoDotsTwice\SilexFeatureToggles;

use Qandidate\Toggle\Serializer\InMemoryCollectionSerializer;
use Qandidate\Toggle\ToggleManager;
use Silex\Application;
use Silex\ServiceProviderInterface;

class FeatureTogglesProvider implements ServiceProviderInterface
{
    /**
     * @var array
     */
    protected $config;

    /**
     * FeatureTogglesProvider constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @inheritdoc
     */
    public function register(Application $app)
    {
        $config = $this->config;

        $app['toggles'] = $app->share(
            function (Application $app) use ($config) {
                $serializer = new InMemoryCollectionSerializer();
                $collection = $serializer->deserialize($config);

                $toggles = new ToggleManager($collection);
                return $toggles;
            }
        );
    }

    /**
     * @inheritdoc
     */
    public function boot(Application $app)
    {

    }
}
