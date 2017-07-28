<?php

namespace MoySklad\Components\Specs;

use MoySklad\Exceptions\UnknownSpecException;

/**
 * Specs are used as parameters
 * Class AbstractSpecs
 * @package MoySklad\Components\Specs
 */
abstract class AbstractSpecs{
    protected static $cachedDefaultSpecs = null;

    protected function __construct($specs = [])
    {
        $defaults = $this->getDefaults();
        foreach ( $defaults as $k=>$v ) {
            $this->{$k} = $v;
        }
        foreach ( $specs as $specName=>$spec ){
            if ( !array_key_exists($specName, $defaults) ){
                throw new UnknownSpecException($specName);
            }
            $this->{$specName} = $spec;
        }
        if ( empty($specs) ){
            static::$cachedDefaultSpecs = $this;
        }
    }

    /**
     * Should be used to construct specs. Returns cached copy if used with empty array
     * @param array $specs
     * @return static
     */
    public static function create($specs = []){
        $cl = get_called_class();
        if ( empty($specs) && $cl::$cachedDefaultSpecs !== null){
            return $cl::$cachedDefaultSpecs;
        }
        return new static($specs);
    }

    /**
     * Converts itself to array
     * @return array
     */
    public function toArray(){
        return (array)$this;
    }

    /**
     * Specs should be strict, so that's it
     * @param $name
     * @throws UnknownSpecException
     */
    public function __get($name)
    {
        throw new UnknownSpecException($name);
    }

    abstract public function getDefaults();
}
