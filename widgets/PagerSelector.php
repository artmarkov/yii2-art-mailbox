<?php
namespace artsoft\mailbox\widgets;

use Yii;
use yii\helpers\Html;

/**
 *  echo artsoft\widgets\PagerSelector::widget([
 *       'container' => ['class' => 'box-tools pull-right'],
 *       'ulOptions' => ['class' => 'pager'],
 *       'link' => 
 *       'option_link' => 
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
     
    public $container = ['class' => 'box-tools pull-right'];
     
    public $ulOptions = ['class' => 'pager'];
    public $liOptions = [];
    
    public $link = '/mailbox/default/view-sent';
    public $option_link = '/mailbox/default/index';
    
    public $next;
    public $previous;
    
    public $next_icon = '<i class="fa fa-chevron-right"></i>';
    public $previous_icon = '<i class="fa fa-chevron-left"></i>';
    
    public $next_options = ['class' => 'previous', 'data-toggle' => 'tooltip', 'data-container' => 'body', 'title' => '', 'data-original-title' => 'Next'];
    public $previous_options = ['class' => 'previous', 'data-toggle' => 'tooltip', 'data-container' => 'body', 'title' => '', 'data-original-title' => 'Previous'];


    public function run() {
        
    }

    public function buildPager() {
        $this->rawPagerHtml = '';
        $this->rawPagerHtml .= Html::beginTag('div', $this->container);
        $this->rawPagerHtml .= Html::beginTag('ul', $this->ulOptions);
            $this->rawPagerHtml .= Html::beginTag('li', $this->liOptions);
                $this->rawPagerHtml .= Html::tag('a', $this->previous_icon, [$this->link, 'id' => $this->previous], [$this->previous_options]);
            $this->rawPagerHtml .= Html::endTag('li');
            $this->rawPagerHtml .= Html::beginTag('li', $this->liOptions);
                $this->rawPagerHtml .= Html::tag('a', $this->next_icon, [$this->link, 'id' => $this->next], [$this->next_options]);
            $this->rawPagerHtml .= Html::endTag('li');        
        $this->rawPagerHtml .= Html::endTag('ul');
        $this->rawPagerHtml .= Html::endTag('div');
    }

}
