<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * @author Vitaly Lavrenko
 */
class MS_URI extends CI_URI
{

    function _fetch_uri_string()
    {
        if(strtoupper($this->config->item('uri_protocol')) == 'AUTO')
        {

            
            // Is the request coming from the command line?
            if(php_sapi_name() == 'cli' or defined('STDIN'))
            {
                $this->_set_uri_string($this->_parse_cli_args());
                return;
            }

            // Let's try the REQUEST_URI first, this will work in most situations
            if($uri = $this->_detect_uri_ex())
            {
                $this->_set_uri_string($uri);
                return;
            }

            // Is there a PATH_INFO variable?
            // Note: some servers seem to have trouble with getenv() so we'll test it two ways
            $path = (isset($_SERVER['PATH_INFO'])) ? $_SERVER['PATH_INFO'] : @getenv('PATH_INFO');
            if(trim($path, '/') != '' && $path != "/" . SELF)
            {
                $this->_set_uri_string($path);
                return;
            }

            // No PATH_INFO?... What about QUERY_STRING?
            $path = (isset($_SERVER['QUERY_STRING'])) ? $_SERVER['QUERY_STRING'] : @getenv('QUERY_STRING');
            if(trim($path, '/') != '')
            {
                $this->_set_uri_string($path);
                return;
            }

            // As a last ditch effort lets try using the $_GET array
            if(is_array($_GET) && count($_GET) == 1 && trim(key($_GET), '/') != '')
            {
                $this->_set_uri_string(key($_GET));
                return;
            }

            // We've exhausted all our options...
            $this->uri_string = '';
            return;
        }

        $uri = strtoupper($this->config->item('uri_protocol'));

        if($uri == 'REQUEST_URI')
        {
            $this->_set_uri_string($this->_detect_uri_ex());
            return;
        }
        elseif($uri == 'CLI')
        {
            $this->_set_uri_string($this->_parse_cli_args());
            return;
        }

        $path = (isset($_SERVER[$uri])) ? $_SERVER[$uri] : @getenv($uri);
        $this->_set_uri_string($path);
    }

    /**
    * Parse cli arguments
    *
    * Take each command line argument and assume it is a URI segment.
    *
    * @access	private
    * @return	string
    */
    private function _parse_cli_args()
    {
            $args = array_slice($_SERVER['argv'], 1);

            return $args ? '/' . implode('/', $args) : '';
    }
    
    /**
     * Detects the URI
     *
     * This function will detect the URI automatically and fix the query string
     * if necessary.
     *
     * @access	private
     * @return	string
     */
    protected function _detect_uri_ex()
    {
        if(!isset($_SERVER['REQUEST_URI']) OR !isset($_SERVER['SCRIPT_NAME']))
        {
            return '';
        }

        $uri = $_SERVER['REQUEST_URI'];

        if(strpos($uri, $_SERVER['SCRIPT_NAME']) === 0)
        {
            $uri = substr($uri, strlen($_SERVER['SCRIPT_NAME']));
        }
        elseif(strpos($uri, dirname($_SERVER['SCRIPT_NAME'])) === 0)
        {
            $uri = substr($uri, strlen(dirname($_SERVER['SCRIPT_NAME'])));
        }

        // This section ensures that even on servers that require the URI to be in the query string (Nginx) a correct
        // URI is found, and also fixes the QUERY_STRING server var and $_GET array.
        if(strncmp($uri, '?/', 2) === 0)
        {
            $uri   = substr($uri, 2);
        }
        $parts = preg_split('#\?#i', $uri, 2);
        $uri   = $parts[0];
        if(isset($parts[1]))
        {
            $_SERVER['QUERY_STRING'] = $parts[1];
            parse_str($_SERVER['QUERY_STRING'], $_GET);
        }
        else
        {
            $_SERVER['QUERY_STRING'] = '';
            $_GET                    = array();
        }

        if($uri == '/' || empty($uri))
        {
            return '/';
        }

        $uri = parse_url($uri, PHP_URL_PATH);

        //remove lang prefix
        $lngs = '(' . implode('|', array_keys($this->config->item('languages'))) . ')';
        $uri  = preg_replace('/^' . $lngs . '\/(.*)/i', "$2", $uri);
        
        // Do some final cleaning of the URI and return it
        return str_replace(array('//', '../'), '/', trim($uri, '/'));
    }

}