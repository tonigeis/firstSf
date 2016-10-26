<?php


// src/AppBundle/Controller/LuckyController.php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

//session_start();  
class LuckyController extends Controller
{
    /**
     * @Route("/lucky/number")
     */
    public function numberAction()
    {
        $session = $this->getRequest()->getSession();
        $number = mt_rand(0, 100);
        
        $session->set('intentos', $session->get('intentos') + 1);

        if (($session->get('intentos') % 2) == 0) {
            $this->get('session')->getFlashBag()->add(
            'notice',
            'Has efectuado '.$session->get('intentos').' intentos');
        }

        return $this->render('lucky/number.html.twig', array(
            'number' => $number,
        ));
    }
}

?>