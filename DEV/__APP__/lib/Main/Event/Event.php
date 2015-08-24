<?php
namespace Main\Event;

Final Class Event {

    /** @var array [description] */
    private static $_aEvents = array();

    /**
     * [_trace description]
     * @return [type] [description]
     */
    private static function _trace()
    {
        $aInfo = debug_backtrace( true, 2);

        if ( count($aInfo)>1) {
            array_shift( $aInfo);
        }

        return current( $aInfo);
    }

    /**
     * [_launch description]
     * @param  [type] $sString [description]
     * @param  array  $aInfo   [description]
     * @return [type] [description]
     */
    private static function _launch( $sString, $mValue = NULL, $aInfo = array())
    {
        if ( array_key_exists( $sString, self::$_aEvents)) {
            foreach (self::$_aEvents[$sString] as $fCallback) {
                $fCallback( $mValue, $aInfo);
            }

            return true;
        }

        return false;
    }

    /**
     * [addListener description]
     * @param [type] $sString   [description]
     * @param [type] $fCallback [description]
     */
    public static function addListener($sString, $fCallback)
    {
        self::$_aEvents[$sString][] = $fCallback;
    }

    /**
     * [get description]
     * @return [type] [description]
     */
    public static function trigger($sEvent, $mValue = NULL)
    {
        $aInfoEvent = array();
        $aInfo      = self::_trace();

        $aInfoEvent['target'] = $aInfo['file'];
        $aInfoEvent['event']  = explode( ',', $sEvent) + array(1=>NULL);

        $aEvents = $aInfoEvent['event'];

        sort( $aInfoEvent['event']);

        foreach ($aEvents as $sEvent) {
            if( !is_null( $sEvent))
                self::_launch( trim( $sEvent), $mValue, $aInfoEvent);
        }

    }

}
