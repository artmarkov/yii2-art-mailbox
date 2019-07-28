<?php
namespace artsoft\mailbox\widgets;

use Yii;
use yii\helpers\Html;

/**
 *  echo artsoft\widgets\PagerSelector::widget([
 *       'container' => ['class' => 'box-tools pull-right'],
 *       'ulOptions' => ['class' => 'pager'],
 *       'next' => $next,
 *       'previous' => $previous,
 *   ]); 
 */

/**
 * Description of PagerSelector
 *
 * @author markov-av
 */
class PagerSelector extends \yii\base\Widget
{
     // default from docs
    public $container = ['class' => 'box-tools pull-right'];
    // none by default
    public $ulOptions = ['class' => 'pager'];
    
    public $link;


    public function run() {
        
    }

    public function buildPager() {
        $this->rawPagerHtml = '';
        $this->rawPagerHtml .= Html::beginTag('div', $this->container);
        $this->rawPagerHtml .= Html::beginTag('ul', $this->ulOptions);

        $this->rawPagerHtml .= $this->renderItems();

        $this->rawPagerHtml .= Html::endTag('ul');
        $this->rawPagerHtml .= Html::endTag('div');
    }

}
