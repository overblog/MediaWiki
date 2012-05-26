<?php

namespace Overblog\MediaWiki\Converter;

/**
 * Shared methods
 */
class Base
{
    public function clean()
    {
        foreach(get_object_vars($this) as $property => $value)
        {
            if(is_null($value) || (is_array($value) && count($value) === 0))
            {
                unset($this->{$property});
            }
            elseif(is_object($value))
            {
                $this->{$property}->clean();
            }
            elseif(is_array($value))
            {
                //var_dump($value);
                foreach($value as $p => $v)
                {
                    if(is_object($v))
                    {
                        $this->{$property}[$p]->clean();
                    }
                }
            }
        }
    }
}