<?php

if(!function_exists('get_config'))
{

    function &get_config($replace = array())
    {
        static $_config;

        if(isset($_config))
        {
            return $_config[0];
        }

        // Is the config file in the environment folder?
        $file_path = APPPATH . 'config/config.php';

        // Fetch the config file
        if(!isset($file_path) || !file_exists($file_path))
        {
            exit('The configuration file does not exist.');
        }

        require($file_path);

        // Fetch the config file
        if(defined('ENVIRONMENT') && file_exists($file_env_path = APPPATH . 'config/' . ENVIRONMENT . '/config.php'))
            require($file_env_path);




        // Does the $config array exist in the file?
        if(!isset($config) OR !is_array($config))
        {
            exit('Your config file does not appear to be formatted correctly.');
        }

        // Are any values being dynamically replaced?
        if(count($replace) > 0)
        {
            foreach($replace as $key => $val)
            {
                if(isset($config[$key]))
                {
                    $config[$key] = $val;
                }
            }
        }

        return $_config[0] = & $config;
    }

}

if(!function_exists('getActiveDBConfig'))
{

    function getActiveDBConfig()
    {
        if(!defined('ENVIRONMENT') OR !file_exists($file_path = APPPATH . 'config/' . ENVIRONMENT . '/database.php'))
        {
            if(!file_exists($file_path = APPPATH . 'config/database.php'))
            {
                show_error('The configuration file database.php does not exist.');
            }
        }

        include($file_path);
        return $db[$active_group];
    }

}
?>
