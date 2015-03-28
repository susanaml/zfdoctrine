<?php

namespace Album\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{

    public function indexAction()
    {
        $em = $this->getServiceLocator()
            ->get('doctrine.entitymanager.orm_default');
        $data = $em->getRepository('Album\Entity\Track')->findAll();
        foreach($data as $key=>$row)
        {
            echo $row->getAlbum()->getArtist().' :: '.$row->getTrackTitle();
            echo '<br />';
        }
    }

}

