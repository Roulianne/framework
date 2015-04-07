<?php
namespace Core\Loader\Php;

use Core\Loader\Loader      as Loader,
    Core\Loader\Php\Library as Library,
    \Exception              as Exception,
    \ReflectionClass        as ReflectionClass,
    \ReflectionException    as ReflectionException;

class Instance extends Library
{
    /**
     * [load description]
     * @param  [type] $sIncompleteName [description]
     * @param  array  $aArguments      [description]
     * @return [type] [description]
     */
    public function load( $sIncompleteName, array $aArguments = array( ))
    {
        if ($sClass = parent::load( $sIncompleteName)) {

            try {
                $oRClass = new ReflectionClass( $sClass);

                if (NULL === ($oRMethod = $oRClass->getConstructor( ))) {
                    return new $sClass;
                }

                if (sizeOf( $aArguments) < $oRMethod->getNumberOfRequiredParameters( )) {
                    throw new Exception;
                }

                return $oRClass->newInstanceArgs( $aArguments);

            } catch (ReflectionException $e) {
                throw new Exception;
            }
        }
    }
}
