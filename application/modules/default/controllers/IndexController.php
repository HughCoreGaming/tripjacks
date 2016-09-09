<?php
class IndexController extends Zend_Controller_Action
{
  public function init()
  {
      /* Initialize action controller here */
  }

  public function indexAction()
  {
      
       
      
        $league_info = new Tripjacks_Model_League;
        $leagues = $league_info->getLeagues();
        $this->view->leagues = $leagues;   
      
        $main_info = new Tripjacks_Model_Main;
        $main = $main_info->getMain();
        $this->view->main = $main;   
        
        $news_info = new Tripjacks_Model_News;
        $news = $news_info->getNews();
        $this->view->news = $news;  
             
  }
}

