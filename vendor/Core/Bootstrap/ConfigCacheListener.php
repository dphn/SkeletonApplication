<?php

namespace Core\Bootstrap;

class ConfigCacheListener
{
    /**
     * @const string
     */
    const CacheKey = 'config.cache';

    /**
     * @const string
     */
    const ContainerClass = 'Phalcon\Cache\Frontend\Data';

    /**
     * @var string
     */
    protected $storageClass = 'Phalcon\Cache\Backend\File';

    /**
     * @var array
     */
    protected $storageOptions = [
        'cacheDir' => './data/cache',
    ];

    /**
     * Default 24 hours
     *
     * @var integer
     */
    protected $lifetime = 86400;

    /**
     * @var boolean
     */
    protected $enableWritingCache = false;

    public function mergeConfig($event, $application)
    {
        $di = $application->getDI();
        $config = $di->get('config');

        if (! (isset($config['config_cache']['enabled'])
                && $config['config_cache']['enabled'])
        ) {
            return;
        }

        $this->readConfig($config);


        $containerClass = static::ContainerClass;
        $container = new $containerClass($this->lifetime);

        $storageClass = $this->storageClass;
        $cache = new $storageClass($container, $this->storageOptions);

        $cachedConfig = $cache->get(static::CacheKey, $this->lifetime);

        if (empty($cachedConfig)) {
            $this->enableWritingCache = true;
            return;
        }

        $config->merge($cachedConfig);
        $event->stop();
    }

    protected function afterMergeConfig($event, $application)
    {
        if (! $this->enableWritingCache) {
            return;
        }

        $di = $application->getDI();
        $config = $di->get('config');

        $containerClass = static::ContainerClass;
        $container = new $containerClass($this->lifetime);

        $storageClass = $this->storageClass;
        $cache = new $storageClass($container, $this->storageOptions);

        $cache->save(static::CacheKey, $config, $this->lifetime);
    }

    protected function readConfig($config)
    {
        $options = $config['config_cache'];

        if (isset($options['lifetime'])) {
            $this->lifetime = $options['lifetime'];
        }

        if (isset($options['storage']['class'])) {
            $this->storageClass = $options['storage']['class'];
        }

        if (isset($options['storage']['options'])) {
            $this->storageOptions = (array) $options['storage']['options'];
        }
    }
}
