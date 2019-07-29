<?php
namespace artsoft\mailbox\widgets;

use Yii;
use yii\helpers\Html;

/**
 *  echo artsoft\widgets\PagerSelector::widget([
 *       'ulOptions' => ['class' => 'pager'],
 *       'link' => '/mailbox/default/view-sent',
 *       'next_id' => $next,
 *       'prev_id' => $prev,
 *   ]); 
 */

/**
 * Description of PagerSelector
 *
 * @author markov-av
 */
class PagerSelector extends \yii\base\Widget
{
     
    public $primaryKey = 'id';
    
    public $next_id;
    public $prev_id;
    
    public $path;
     
    public $ulOptions = ['class' => 'pager'];
    
    public $liOptionsNext = [];
    public $liOptionsPrev = [];
    
    public $next_icon = '<i class="fa fa-chevron-right"></i>';
    public $prev_icon = '<i class="fa fa-chevron-left"></i>';
    
    
    public $next_options = ['data-toggle' => 'tooltip', 'data-container' => 'body', 'data-placement' => 'top', 'data-original-title' => 'Next'];
    public $prev_options = ['data-toggle' => 'tooltip', 'data-container' => 'body', 'data-placement' => 'top', 'data-original-title' => 'Previous'];


    private $rawPagerHtml;
    
    public function run() {
         $this->buildPager();
         
         return $this->getPagerHtml();
    }

    private function buildPager() {
       
         if (!isset($this->next_id)) {
            Html::addCssClass($this->liOptionsNext, 'disabled');
        }
        if (!isset($this->prev_id)) {
            Html::addCssClass($this->liOptionsPrev, 'disabled');
        }
        
        $this->rawPagerHtml = '';
        $this->rawPagerHtml .= Html::beginTag('ul', $this->ulOptions);
        $this->rawPagerHtml .= Html::beginTag('li', $this->liOptionsPrev);
        $this->rawPagerHtml .= isset($this->prev_id) ? Html::a($this->prev_icon, [$this->path, $this->primaryKey => $this->prev_id], $this->prev_options) : Html::a($this->prev_icon, null);
        $this->rawPagerHtml .= Html::endTag('li');
        $this->rawPagerHtml .= Html::beginTag('li', $this->liOptionsNext);
        $this->rawPagerHtml .= isset($this->next_id) ? Html::a($this->next_icon, [$this->path, $this->primaryKey => $this->next_id], $this->next_options) : Html::a($this->next_icon, null);
        $this->rawPagerHtml .= Html::endTag('li');
        $this->rawPagerHtml .= Html::endTag('ul');
    }

    public function getPagerHtml() {
        
        return $this->rawPagerHtml;
    }
}
