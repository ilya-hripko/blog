<?php

Class MS_Config extends CI_Config
{

    function load($file = '', $use_sections = FALSE, $fail_gracefully = FALSE)
    {
        $file   = ($file == '') ? 'config' : str_replace('.php', '', $file);
        $found  = FALSE;
        $loaded = FALSE;

        foreach($this->_config_paths as $path)
        {

            $check_locations = defined('ENVIRONMENT') && file_exists($path . 'config/' . ENVIRONMENT . '/' . $file . '.php') ? array($file, ENVIRONMENT . '/' . $file) : array($file);

            foreach($check_locations as $location)
            {
                $file_path = $path . 'config/' . $location . '.php';

                if(in_array($file_path, $this->is_loaded, TRUE))
                {
                    $loaded = TRUE;
                    continue 2;
                }

                if(file_exists($file_path))
                {
                    $found = TRUE;
                    require $file_path;
                    if(!isset($config) OR !is_array($config))
                    {
                        if($fail_gracefully === TRUE)
                        {
                            return FALSE;
                        }
                        show_error('Your ' . $file_path . ' file does not appear to contain a valid configuration array.');
                    }

                    if($use_sections === TRUE)
                    {
                        if(isset($this->config[$file]))
                        {
                            $this->config[$file] = array_merge($this->config[$file], $config);
                        }
                        else
                        {
                            $this->config[$file] = $config;
                        }
                    }
                    else
                    {
                        $this->config = array_merge($this->config, $config);
                    }

                    $this->is_loaded[] = $file_path;
                    unset($config);

                    $loaded = TRUE;
                    log_message('debug', 'Config file loaded: ' . $file_path);
                }
            }

            if($found === FALSE)
            {
                continue;
            }
            break;
        }

        if($loaded === FALSE)
        {
            if($fail_gracefully === TRUE)
            {
                return FALSE;
            }
            show_error('The configuration file ' . $file . '.php does not exist.');
        }

        return TRUE;
    }

    function secure_site_url($uri = '')
    {
        if (is_array($uri))
        {
            $uri = implode('/', $uri);
        }
 
        if ($uri == '')
        {
            return $this->slash_item('secure_base_url').$this->item('index_page');
        }
        else
        {
            $suffix = ($this->item('url_suffix') == FALSE) ? '' : $this->item('url_suffix');
            return $this->slash_item('secure_base_url').$this->slash_item('index_page').preg_replace("|^/*(.+?)/*$|", "\\1", $uri).$suffix;
        }
    }
}

?>
