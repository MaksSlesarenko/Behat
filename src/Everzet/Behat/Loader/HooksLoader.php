<?php

namespace Everzet\Behat\Loader;

use Symfony\Component\EventDispatcher\EventDispatcher;

/*
 * This file is part of the behat package.
 * (c) 2010 Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Event hooks loader
 *
 * @author      Konstantin Kudryashov <ever.zet@gmail.com>
 */
class HooksLoader implements LoaderInterface
{
    protected $dispatcher;

    /**
     * Inits loader
     *
     * @param   EventDispatcher $dispatcher event dispatcher
     */
    public function __construct(EventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Loads hooks from specified path(s)
     *
     * @param   string|array    $paths      file or list of files to load hooks from
     * 
     * @return  Everzet\Behat\Loader\HooksLoader
     */
    public function load($paths)
    {
        foreach ((array) $paths as $file) {
            if (null !== $file && is_file($file)) {
                $hooks = $this;
                require $file;
            }
        }

        return $this;
    }

    /**
     * Hook to fire before context
     *
     * @param   string  $context    context name (f.e. features.load => features.load.before event)
     * @param   string  $callback   callback
     */
    public function before($context, $callback)
    {
        $this->dispatcher->connect($context . '.before', $callback, 1);
    }

    /**
     * Hook to fire after context
     *
     * @param   string  $context    context name (f.e. features.load => features.load.after event)
     * @param   string  $callback   callback
     */
    public function after($context, $callback)
    {
        $this->dispatcher->connect($context . '.after', $callback, 1);
    }
}
