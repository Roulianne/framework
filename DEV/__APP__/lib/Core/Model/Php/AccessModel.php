<?php

namespace Core\Model\Php;

interface AccessModel
{
    /**
     * [getDatas retourne l'enseemble des infos du model en array]
     * @return Array [description]
     */
    public function getData();

    /**
     * [getType retourn le type de model]
     * @return [type] [description]
     */
    public function getType();

    /**
     * [create]
     * @return boolean [description]
     */
    public function create();

    /**
     * [read ]
     * @return [type] [description]
     */
    public function read( $mValue, $sChamp);

    /**
     * [delete suppression de l'element]
     * @return boolean [description]
     */
    public function delete();

    /**
     * [save fait une mise à jour des donnée aDatas]
     * @return [type] [description]
     */
    public function save();

    /**
     * [screen recupere les model repondant au conditions]
     * @param  Array $aConditions [description]
     * @return Array [description]
     */
    public function screen( $aConditions = array());

    /**
     * [Get retourne la valeur de model dans Datas]
     * @param string $sParam [description]
     */
    public function get( $sParam = '');

    /**
     * [Set mets a jour la valeur de Model dasn Datas]
     * @param string $sParam [description]
     * @param [type] $mValue [description]
     */
    public function set( $sParam = '', $mValue);
}
