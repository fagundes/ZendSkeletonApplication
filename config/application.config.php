<?php
/**
 * Set in your vhost or .htaccess your env variable something like that:
 *
 * SetEnv APP_ENV "development"
 *
 * Futher information in:
 *
 * @see http://framework.zend.com/manual/current/en/tutorials/config.advanced.html#environment-specific-system-configuration
 * @see http://framework.zend.com/manual/current/en/tutorials/config.advanced.html#environment-specific-application-configuration
 */
$env_options = array(
    /**
     * Configure here the default env
     */
    'default_env'                     => 'production',
    /**
     * Configure here modules actives in all envs
     */
    'modules_default'                 => array(
        'DoctrineModule',
        'DoctrineORMModule',
        'ZfcBase',
        'ZfcUser',
        'ZfcUserDoctrineORM',
        'BjyAuthorize',
        'AssetManager',
        'ZfTable',
        'Zff\\Base',
        'Zff\\Html2Pdf',
        'Application',
    ),
    /**
     * Configure here modules actives only in some envs
     */
    'modules_per_env'                 => array(
        'production'  => array(),
        'development' => array(),
        'staging'     => array(),
        'testing'     => array(),
    ),
    /**
     * Configure here the default module listener options
     */
    'module_listener_options_default' => array(
        'config_cache_enabled'     => false,
        'config_cache_key'         => 'app_config',
        'module_map_cache_enabled' => false,
        'module_map_cache_key'     => 'module_map',
        'cache_dir'                => 'data/cache/',
        'check_dependencies'       => true,
    ),
    /**
     * Configure here the module listener options per env
     */
    'module_listener_options_per_env' => array(
        'production'  => array(
            'config_cache_enabled'     => true,
            'module_map_cache_enabled' => true,
            'check_dependencies'       => true,
        ),
        'development' => array(),
        'staging'     => array(),
        'testing'     => array(),
    ),
);

//
// You probably do not need to edit anything below
//

$env = getenv('APP_ENV') ?: $env_options['default_env'];

if(!defined('APP_ENV')) {
    define('APP_ENV', $env);
}
if(!defined('PROJECT_NAME')) {
    define('PROJECT_NAME', 'zff-sprout');
}

$module_listener_options_per_env = array_merge(
    $env_options['module_listener_options_default'],
    $env_options['module_listener_options_per_env'][$env]
);

$modules = array_merge(
    $env_options['modules_default'],
    $env_options['modules_per_env'][$env]
);

return array(
    // This should be an array of module namespaces used in the application.
    'modules'                 => $modules,
    // These are various options for the listeners attached to the ModuleManager
    'module_listener_options' => array(
        // This should be an array of paths in which modules reside.
        // If a string key is provided, the listener will consider that a module
        // namespace, the value of that key the specific path to that module's
        // Module class.
        'module_paths' => array(
            './module',
            './vendor',
        ),
        // An array of paths from which to glob configuration files after
        // modules are loaded. These effectively override configuration
        // provided by modules themselves. Paths may use GLOB_BRACE notation.
        'config_glob_paths'        => array(
            sprintf('config/autoload/{,*.}{global,local,%s}.php', $env),
        ),
        // Whether or not to enable a configuration cache.
        // If enabled, the merged configuration will be cached and used in
        // subsequent requests.
        'config_cache_enabled'     => $module_listener_options_per_env['config_cache_enabled'],
        // The key used to create the configuration cache file name.
        'config_cache_key'         => $module_listener_options_per_env['config_cache_key'],
        // Whether or not to enable a module class map cache.
        // If enabled, creates a module class map cache which will be used
        // by in future requests, to reduce the autoloading process.
        'module_map_cache_enabled' => $module_listener_options_per_env['module_map_cache_enabled'],
        // The key used to create the class map cache file name.
        'module_map_cache_key'     => $module_listener_options_per_env['module_map_cache_key'],
        // The path in which to cache merged configuration.
        'cache_dir'                => $module_listener_options_per_env['cache_dir'],
        // Whether or not to enable modules dependency checking.
        // Enabled by default, prevents usage of modules that depend on other modules
        // that weren't loaded.
        'check_dependencies'       => $module_listener_options_per_env['check_dependencies'],
    ),
);