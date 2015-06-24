<?php

namespace TusIO_Server;

use Predis\Client as PredisClient;
use TusIO_Server\Methods as Method;

class Server {

    /**
     * Method::POST
     * Method::HEAD
     * Method::PATCH
     * Method::OPTIONS
     * Method::GET
     */
    
    private $directory;
    private $path;

    private $redis;
    private $redis_options;

    public function __construct( $directory, $path, $redis_options = null ) {

        //
        // Directory and Path functions here
        // 
        $this->setRedisOptions( $redis_options );
    }

    private function setRedisOptions( $redis_options = null ) {
        
        if ( is_null( $redis_options ) ) {

            $this->redis_options  = array(
                'prefix'    => 'php-tus-',
                'scheme'    => 'tcp',
                'host'      => '127.0.0.1',
                'port'      => '6379',
            );

        } 
        else
            $this->redis_options = $this->doRedisCheck( $redis_options );
    }

    private function doRedisCheck( array $redis_options ) {

        foreach ($redis_options as $key => $value) {
            
            switch ($key) {
                case 'prefix':
                case 'scheme':
                case 'host':
                case 'port':
                    continue;
                
                default:
                    throw new Exception\Redis('Redis Array is configured incorrectly.');
                    break;
            }
        }

        return $redis_options;
    }

    private function getRedis() {
        
        if ( $this->redis === null )
            $this->redis = new PredisClient( $this->redis_options );

        return $this->redis;
    }
}