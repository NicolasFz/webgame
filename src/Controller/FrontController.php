<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Party;
use App\Entity\Player;
use App\Form\PartyType;

class FrontController extends Controller
{
    
    /**
     * @Route("/home", name="home")
     */
    public function home()
    {      
        $currentParties = $this->getDoctrine()
           ->getRepository(Party::class)
           ->findAll();
        
        return $this->render('webgame/home.html.twig',
                [
                    'current_parties'=> $currentParties,
                ]);
    }
    
}