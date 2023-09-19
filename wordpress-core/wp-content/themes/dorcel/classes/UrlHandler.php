<?php

/**
 * Class UrlHandler
 *
 * Makes the href and target tag for the "a" element when used.
 * Adds language tags to url-s, and determines if site is external or internal thus adding or not adding target: _blank parameter
 *
 * @package SLS
 * @since 5.2.2
 */
class UrlHandler
{
    private static $external_hosts_rules = [
        [
            'host' => 'dorcelvision.com',
            'languages' => ['fr', 'en', 'de'],
        ],
        [
            'host' => 'xillimite.com',
            'languages' => ['fr', 'en', 'de'],
        ],
        [
            'host' => 'dorcelstore.com',
            'languages' => ['fr', 'en'],
        ],
        [
            'host' => 'dorcel.com',
            'languages' => ['fr', 'en', 'de'],
            'own_host_external' => true, // Only works when "extra_path" is defined in array
            'extra_path' => '/experience/',
        ],
        [
            'host' => 'dorceltv.com',
            'languages' => ['fr', 'en', 'de', 'es', 'nl', 'pl'],
        ],
        [
            'host' => 'dorcelclub.com',
            'languages' => ['fr', 'en'],
        ],
    ];

    private static $site_address_parsed;
    private static $site_address;
    private $default_language = "en";
    private $url;
    private $parsed_url;
    private $host;

    /**
     * UrlHandler constructor.
     */
    public function __construct()
    {
        self::$site_address = get_site_url();
        self::$site_address_parsed = $this->parse_url(self::$site_address);

        if (!array_key_exists('host', self::$site_address_parsed) || empty(self::$site_address_parsed['host'])) {
            self::$site_address_parsed = [];
        }
    }

    /**
     * Adds parameters for a link tag ('<a>' element) determined by given set of external site hosts
     *
     * @param $url
     * @return string
     */
    public function get_link_parameters($url)
    {
        $final_url = esc_url($url);

        // Matches URL-s, and extracts them separately to scheme, pre-domain tag, and domain by grouping
        if (!empty($final_url) && !preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $final_url)) {
            if (preg_match("/[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $final_url)) {
                $final_url = self::$site_address . '/' . ICL_LANGUAGE_CODE . $final_url;
            } else {
                return self::$site_address;
            }
        }

        if ($this->set_url($url)) {
            $final_url = $this->manage_url();
        }

        return sprintf('href="%s" %s',
            $final_url,
            !$this->is_address_internal() ? "target='_blank'" : ""
        );
    }

    /**
     * Reassembles URL from parsed parameters and adds language tag
     *
     * @return string
     */
    private function manage_url()
    {
        $scheme = isset($this->parsed_url['scheme']) ? $this->parsed_url['scheme'] . '://' : '';
        $host = isset($this->parsed_url['host']) ? $this->parsed_url['host'] : '';
        $port = isset($this->parsed_url['port']) ? ':' . $this->parsed_url['port'] : '';
        $user = isset($this->parsed_url['user']) ? $this->parsed_url['user'] : '';
        $pass = isset($this->parsed_url['pass']) ? ':' . $this->parsed_url['pass'] : '';
        $pass = ($user || $pass) ? "$pass@" : '';
        $path = $this->add_or_change_path_language_tag();
        $query = isset($this->parsed_url['query']) ? '?' . $this->parsed_url['query'] : '';
        $fragment = isset($this->parsed_url['fragment']) ? '#' . $this->parsed_url['fragment'] : '';
        return $scheme . $user . $pass . $host . $port . $path . $query . $fragment;
    }

    /**
     * Determine what language tag to add, if we want to add any
     *
     * @return string
     */
    private function add_or_change_path_language_tag()
    {
        if (!array_key_exists('path', $this->parsed_url) || empty($this->parsed_url['path'])) {
            return '';
        }

        $internal = $this->is_address_internal();

        if ($internal === true && ICL_LANGUAGE_CODE === "fr") {
            return $this->parsed_url['path'];
        }

        $return_path = $this->parsed_url['path'];
        preg_match("/\/([a-zA-Z]{2})(\/|$)/", $return_path, $language_matches);
        $host = null;
        $forced_external = "";

        foreach (self::$external_hosts_rules as $key => $external_host_rules) {
            if ($this->is_host_same($this->host, $external_host_rules['host'])) {
                $host = $external_host_rules;
            }

            if ($this->is_host_same($this->host, $external_host_rules['host']) &&
                array_key_exists('own_host_external', $external_host_rules) &&
                $external_host_rules['own_host_external'] === true &&
                array_key_exists('path', $this->parsed_url) &&
                strpos($this->parsed_url['path'], $external_host_rules['extra_path']) !== false
            ) {
                $forced_external = $external_host_rules['extra_path'];
            }
        }

        if (!empty($host)) {
            if (!empty($language_matches) && array_key_exists(1, $language_matches)) {
                if (in_array($language_matches[1], $host['languages'])) {
                    return $return_path;
                }

                $return_path = preg_replace("/\/([a-zA-Z]{2})(\/|$)/", $this->default_language . '/', $return_path);
            } else if (in_array(ICL_LANGUAGE_CODE, $host['languages'])) {
                $return_path = ICL_LANGUAGE_CODE . '/' . $return_path;
            } else {
                $return_path = $this->default_language . '/' . $return_path;
            }
        } else {
            return $return_path;
        }

        if (!empty($forced_external)) {
            $return_path = str_replace($forced_external, "", $return_path);
        }

        $return_path = $forced_external . '/' . $return_path;
        $return_path = preg_replace("/\/\//", '/', $return_path);
        return $return_path;
    }

    /**
     * Determines if given address is considered an internal address or not
     *
     * @return bool
     */
    private function is_address_internal()
    {
        $internal = $this->is_host_same($this->host, self::$site_address_parsed['host']);

        foreach (self::$external_hosts_rules as $external_host_rules) {
            if ($this->is_host_same($this->host, $external_host_rules['host']) &&
                array_key_exists('own_host_external', $external_host_rules) &&
                $external_host_rules['own_host_external'] === true &&
                array_key_exists('path', $this->parsed_url) &&
                strpos($this->parsed_url['path'], $external_host_rules['own_host_external']) !== false
            ) {
                $internal = false;
            }
        }

        return $internal;
    }

    private function is_host_same($host1, $host2)
    {
        $host1 = strtolower(preg_replace("/www\./", '', $host1));
        $host2 = strtolower(preg_replace("/www\./", '', $host2));

        if ($host1 == $host2) {
            return true;
        }

        return false;
    }

    /**
     * Sets url, parsed url and validates if parsed url is in the right format
     *
     * @param $url
     * @return bool
     */
    private function set_url($url)
    {
        $this->url = $url;
        $this->set_parsed_url($url);

        if (!is_array($this->parsed_url) || !array_key_exists('host', $this->parsed_url) || empty($this->parsed_url['host'])) {
            return false;
        }

        $this->host = $this->parsed_url['host'];
        return true;
    }

    /**
     * Sets the parsed url
     *
     * @param $parsed_url
     */
    private function set_parsed_url($parsed_url)
    {
        $this->parsed_url = $this->parse_url($parsed_url);
    }

    /**
     * Parses a given url using wp_parse_url
     *
     * @param $url
     * @return mixed
     */
    private function parse_url($url)
    {
        if (empty($url)) {
            return $url;
        }

        return wp_parse_url($url);
    }
}
