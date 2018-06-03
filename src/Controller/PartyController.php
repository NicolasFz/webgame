<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Party;
use App\Entity\Player;
use App\Form\PartyType;
use App\Form\RejoinType;

class PartyController extends Controller
{
    
    
    /**
     * @Route("/new", name="party_new")
     */
    public function new(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        
        //TO-DO : Generate a random and unique name for each party
        $newParty = new Party();
        $form = $this->createForm(PartyType::class, $newParty);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $newParty = $form->getData();

            $entityManager->persist($newParty);

            $entityManager->flush();

            return $this->render('webgame/party/new_party_success.html.twig', array(
                'name' => $newParty->getName(),
                'id' => $newParty->getId(),
            ));
        }
    
        return $this->render('webgame/party/new_party.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    /**
    * @Route("/party/rejoin", name="party_rejoin")
    */
   public function rejoin(Request $request)
   {
        
        $form = $this->createForm(RejoinType::class);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            if(array_key_exists('name', $data)){
                $name = $data['name'];
                //TODO : Add this into a service
                $party = $this->getDoctrine()
                    ->getRepository(Party::class)
                    ->findOneByName($name);
                
                if(!empty($party)){
                    //TO-DO : Use a service instead of a controler : Avoid a redirect
                    return $this->redirect($this->generateUrl('party_connect', ['id'=>$party->getId()]));
                }else{
                     // TODO : Add error here
                }
            }else{
                // TODO : Add error here
            }
        }
    
        return $this->render('webgame/party/rejoin_party.html.twig', [
            'form' => $form->createView(),
        ]);
   }
   
    /**
    * @Route("/party/{id}", name="party_show")
    */
   public function show($id)
   {
       $party = $this->getDoctrine()
           ->getRepository(Party::class)
           ->find($id);

       if (!$party) {
           throw $this->createNotFoundException(
               'No party found for id '.$id
           );
       }

        return $this->render('webgame/party/show.html.twig', [
            'name' => $party->getName(),
            'id' => $party->getId(),
            'players'=> $party->getPlayers(),
        ]);
   }
   


   
   /**
    * 
    * @Route("/party/{id}/connect", name="party_connect")
    */
   public function connect(Party $party)
   {
        $entityManager = $this->getDoctrine()->getManager();
        $player = new Player("New_Player");
        $entityManager->persist($player);
        $party->addPlayer($player);
        $entityManager->flush();
        return $this->render('webgame/connect.html.twig', [
            'party_name' => $party->getName(),
            'party_id' => $party->getId(),
            'player_name' => $player->getName(),
            'player_id' => $player->getId(),
        ]);
   }

}