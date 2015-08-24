<?php

use Core\Model\Order\Order as Order,
    Main\Conf\Conf         as Conf,
    Main\Route\Route       as Route;

class ArticleOrder extends Order
{
    /**
     * [preview description]
     * @return [type] [description]
     */
    public function image()
    {
        $sPreview = $this->getModel()->get('preview');
        $sTitre = urlencode($this->getModel()->get('titre'));

        return 'http://placehold.it/'.$sPreview.'&text='.$sTitre;
    }

    /**
     * [date_array description]
     * @return [type] [description]
     */
    public function date_array()
    {
        $sDateTime = $this->getModel()->get('date');
        $sDate = current( explode(' ', $sDateTime));
        $aDate = array_combine( array( 'annee', 'mois', 'jour'), explode( '-', $sDate));

        return $aDate;
    }

    /**
     * [all description]
     * @return [type] [description]
     */
    public function all()
    {
        $oModel  = $this->getModel();
        $aCond   = array();
        // $aCond   = array(
        // 			'where' => array('id'=>'>=5'),
        // 			'order' => array('id'=>'DESC'),
        // 			'limit' => array('3','2'),
        // 	);
        $aResult = $oModel->screen( $aCond);

        return $aResult;
    }

    /**
     * [resume description]
     * @return [type] [description]
     */
    public function resume()
    {
        $sText = $this->getModel()->get('text');

        return $this->_texte_resume_brut( $sText, 120);
    }

    /**
     * [self_url description]
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
     * [_texte_resume_brut description]
     * @param  [type] $sText  [description]
     * @param  [type] $iNbCar [description]
     * @return [type] [description]
     */
    private function _texte_resume_brut($sText, $iNbCar)
    {
       $sText       = strip_tags($sText);
       $sText      .= ' ';

       $iSizeBefore = strlen($sText);

       $sText = substr($sText, 0, strpos($sText, ' ', $iSizeBefore > $iNbCar ? $iNbCar : $iSizeBefore));

       $sCloseString    = '...';

       if ($sCloseString!='' && $iSizeBefore > $iNbCar) {
           $sText                 .= $sCloseString;
       }

       return $sText;
    }
}
