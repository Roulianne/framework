<?php
namespace Core\Dao\Php;

interface Accessible
{
    public function setCondition( array $aWhere );

    public function options( $oElement);

    public function read( $aSelect);

    public function create( array $aWhat );

    public function update( array $aWhat);

    public function delete();
}
