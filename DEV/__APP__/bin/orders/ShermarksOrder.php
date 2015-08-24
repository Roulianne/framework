<?php

use Core\Model\Order\Order as Order,
	Main\Conf\Conf         as Conf,
	Main\Route\Route       as Route;

class ShermarksOrder extends Order{

	private $_sUrlTmp = "https://fabrique.sherfi.fr/shermarks/_tmp/large/";

	/**
	 * [image description]
	 * @return [type] [description]
	 */
	public function image(){
		$sImage = $this->getModel()->get('image');
		return ($sImage!= '')? $this->_sUrlTmp.$sImage : '';
	}

	/**
	 * [title description]
	 * @return [type] [description]
	 */
	public function title(){
		$sTitle = $this->getModel()->get('title');
		if( $sTitle == ''){
			return "- no title -";
		}
		$iMax = 70;
		if( strlen($sTitle) > $iMax){
			$sTitle = substr($sTitle, 0, $iMax);
		    $iLastSpace = strrpos($sTitle, " ");
		    $sTitle = substr($sTitle, 0, $iLastSpace)."...";
		}

		return $sTitle;
	}

	/**
	 * [url description]
	 * @return [type] [description]
	 */
	public function self_url(){

		$sRoot    = rtrim( Conf::get('app.http_root'), '/');
		$sModel   = $this->getModel()->getType();
		$sReferer = $this->getModel()->getRefererValue();
		$sDisplay = Route::get()->get('display');

		return $sRoot.'/'.$sModel.'/'.$sReferer.'.'.$sDisplay;
	}

	/**
	 * [title description]
	 * @return [type] [description]
	 */
	public function title_tiny(){
		$sTitle = $this->getModel()->get('title');
		if( $sTitle == ''){
			return "- no title -";
		}
		$iMax = 25;
		if( strlen($sTitle) > $iMax){
			$sTitle = substr($sTitle, 0, $iMax);
		    $iLastSpace = strrpos($sTitle, " ");
		    $sTitle = substr($sTitle, 0, $iLastSpace)."...";
		}

		return $sTitle;
	}

}
