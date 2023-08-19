<?php

namespace TND\ElasticSearch;

use SilverStripe\Core\Environment;

/**
 * Class ElasticSearchConfig
 * @package TND\ElasticSearch
 */
class ElasticSearchConfig
{
    protected $host;

    protected $username;

    protected $password;

    /**
     * FbConfig constructor.
     */
    public function __construct()
    {

        $this->setHost(Environment::getEnv('ELASTIC_HOST'));
        $this->setUsername(Environment::getEnv('ELASTIC_USERNAME'));
        $this->setPassword(Environment::getEnv('ELASTIC_PASSWORD'));

    }

    public function getConfig()
    {
        $username = $this->getUsername();
        $password = $this->getPassword();
        $host = $this->getHost();

        if (strpos($host, 'http') !== false) {
            list($protocol, $link) = explode('://', $host);
            return [
                'hosts' => [
                    sprintf("%s://%s:%s@%s", $protocol, $username, $password, $link)
                ]
            ];

        } else {
            return [
                'hosts' => [
                    sprintf("http://%s:%s@%s", $username, $password, $host)
                ]
            ];
        }
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param string $host
     */
    public function setHost($host): void
    {
        $this->host = $host;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }
}
