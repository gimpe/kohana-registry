<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Kohana Registry a registry for Dependency Injection.
 *
 * http://martinfowler.com/articles/injection.html
 * http://www.grobmeier.de/dependency-injection-a-design-pattern-16042009.html
 *
 *
 * Inspired by speakers at ConFoo 2010 and Christian Grobmeier post Dependency Injection – a Design Pattern
 *
 *
 * LICENSE: THE WORK (AS DEFINED BELOW) IS PROVIDED UNDER THE TERMS OF THIS
 * CREATIVE COMMONS PUBLIC LICENSE ("CCPL" OR "LICENSE"). THE WORK IS PROTECTED
 * BY COPYRIGHT AND/OR OTHER APPLICABLE LAW. ANY USE OF THE WORK OTHER THAN AS
 * AUTHORIZED UNDER THIS LICENSE OR COPYRIGHT LAW IS PROHIBITED.
 *
 * BY EXERCISING ANY RIGHTS TO THE WORK PROVIDED HERE, YOU ACCEPT AND AGREE TO
 * BE BOUND BY THE TERMS OF THIS LICENSE. TO THE EXTENT THIS LICENSE MAY BE
 * CONSIDERED TO BE A CONTRACT, THE LICENSOR GRANTS YOU THE RIGHTS CONTAINED HERE
 * IN CONSIDERATION OF YOUR ACCEPTANCE OF SUCH TERMS AND CONDITIONS.
 *
 * @category  module
 * @package   kohana-registry
 * @author    gimpe <gimpehub@intljaywalkers.com>
 * @copyright 2011 International Jaywalkers
 * @license   http://creativecommons.org/licenses/by/3.0/ CC BY 3.0
 * @link      http://github.com/gimpe/kohana-registry
 */
abstract class Kohana_Registry
{
    const USE_LAZYLOAD = 'use_lazyload';
    const USE_SINGLETON = 'use_singleton';

    public static $instance;
    private $_config;
    private $_instances = array();

    /**
     * This method is called in init.php and will force
     * object non lazy loading object to be instanciated
     *
     * @return void
     */
    public static function init()
    {
        Registry::instance();
    }

    /**
     * Create and return Registry singleton
     *
     * @return Registry Registry instance
     */
    public static function instance()
    {
        if ( ! isset(Registry::$instance))
        {
            // Load the configuration for this type
            $config = Kohana::config('registry');

            // Create a new Registry instance
            Registry::$instance = new Registry($config);
        }

        return Registry::$instance;
    }

    /**
     * Instanciate Registry (private constructor)
     *
     * @param Config $config Registry Config
     *
     * @retrun void
     */
    private function __construct($config)
    {
        $this->_config = $config;

        foreach ($this->_config as $id => $item_config)
        {
            // apply defaults
            $item_config += $this->defaults();
            $this->_config[$id] = $item_config;

            // instanciate now if lazyload = FALSE
            if ($item_config[self::USE_LAZYLOAD] === FALSE)
            {
                $this->get($id);
            }
        }
    }

    /**
     * Return mandatory default config values
     *
     * @return Config Config default values
     */
    protected function defaults()
    {
        return array(
            'constructor_attributes' => array(),
            'setter_attributes' => array(),
            self::USE_SINGLETON => TRUE,
            self::USE_LAZYLOAD => FALSE,
        );
    }

    /**
     * Instanciate and return an object from the Registry
     *
     * @param string $id Id of the object to instanciate
     *
     * @return object Instance of the requested object
     */
    public function get($id)
    {
        $item_config = Arr::get($this->_config, $id, NULL);

        if ($item_config === NULL)
        {
            throw new Kohana_Exception('id [' . $id . '] not found in kohana-registry configuration');
        }
        else
        {
            $instance = Arr::get($this->_instances, $id, NULL);

            if ($instance === NULL)
            {
                // load constructor_attributes
                $constructor_attributes = array();
                if (count($item_config['constructor_attributes']))
                {
                    $constructor_attributes = $item_config['constructor_attributes'];
                }

                $class = new ReflectionClass($item_config['class']);
                $instance = $class->newInstanceArgs($constructor_attributes);

                // load setter_attributes
                $reflection_class = new ReflectionClass($instance);
                foreach ($item_config['setter_attributes'] as $attribute_name => $attribute_value)
                {
                    if ($reflection_class->hasProperty($attribute_name)
                            && $reflection_class->getProperty($attribute_name)->isPublic())
                    {
                        $reflection_attribute = $reflection_class->getProperty($attribute_name);
                        $reflection_attribute->setValue($instance, $attribute_value);
                    }
                    else if ($reflection_class->hasMethod($attribute_name))
                    {
                        $reflection_method = $reflection_class->getMethod($attribute_name);
                        $reflection_method->invoke($instance, $attribute_value);
                    }
                    else if ($reflection_class->hasMethod('set_' . strtolower($attribute_name)))
                    {
                        $reflection_method = $reflection_class->getMethod('set_' . strtolower($attribute_name));
                        $reflection_method->invoke($instance, $attribute_value);
                    }
                    else if ($reflection_class->hasMethod('set' . ucfirst($attribute_name)))
                    {
                        $reflection_method = $reflection_class->getMethod('set' . ucfirst($attribute_name));
                        $reflection_method->invoke($instance, $attribute_value);
                    }
                    else
                    {
                        throw new Kohana_Exception('attribute [' . $attribute_name . ']  not found in [' . $id . ']');
                    }
                }
            }

            if ($item_config[self::USE_SINGLETON] === TRUE)
            {
                $this->_instances[$id] = $instance;
            }

            return $instance;
        }
    }

    /**
     * Set an object instance (or singleton) in the registry. Useful but it should be used only if it is not possible
     * to do it using the conf file. Note an empty entry with lazy true TRUE is required fot the $id.
     *
     * @param string $id       Id of the object in the registry
     * @param object $instance Instance of an object that is "hard" register in the conf file
     *
     * @return void
     */
    public function set($id, $instance)
    {
        $this->_instances[$id] = $instance;
    }
} // End Registry