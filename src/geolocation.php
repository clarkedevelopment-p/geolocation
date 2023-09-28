<?php
namespace clarkedevelopment\Geolocation;

/**
 * Geolocation Class
 * @author William Clarke
 */
class GeoLocate
{
    // Set variables
    var $ip           = null;
    var $country_code = null;
    var $country_name = null;
    var $host         = 'https://freeipapi.com/api/json/{IP}';
    var $data         = null;


    /**
     * Locate the user from IP 
     * 
     * @param string $ip The users ip
     * @author William Clarke
     */
    public function locate($ip = null)
    {

        // Check IP exists
        if (is_null($ip)) {
            global $_SERVER;
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        // Make IP accessible 
        $this->ip = $ip;

        // If IP is localhost default to US, If not get country data 
        if ($this->ip == '::1' || $this->ip == '127.0.0.1') {

            // Default values
            $this->country_code = 'US';
            $this->country_name = 'United States of America';
        } else {

            // Add IP onto host URL
            $host = str_replace('{IP}', $this->ip, $this->host);

            // Make request
            $response = $this->request($host);

            // Decode response
            $data = json_decode($response);

            // Put response into accessible format
            $this->data = $data;
            $this->country_code = $data->{'countryCode'};
            $this->country_name = $data->{'countryName'};
        }
    }

    protected function request($host)
    {
        // Make request per docs (https://docs.freeipapi.com/sample-codes.html#php)
        $json = file_get_contents($host);

        // Return
        return $json;
    }
}