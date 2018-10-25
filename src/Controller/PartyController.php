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
use App\Form\QuestionsType;
use App\Service\PartyManager;

class PartyController extends Controller
{
    
    /**
     * @Route("/new", name="party_new")
     */
    public function new(Request $request,PartyManager $partyManager)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $newParty = new Party();
        $form = $this->createForm(PartyType::class, $newParty);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $newParty = $form->getData();

            $newParty = $partyManager->saveNewParty($newParty);

            return $this->redirect($this->generateUrl('party_show', ['id'=>$newParty->getId()]));
        }
    
        return $this->render('webgame/party/new_party.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    /**
    * @Route("/party/rejoin", name="party_rejoin")
    */
   public function rejoin(Request $request,PartyManager $partyManager)
   {
        $form = $this->createForm(RejoinType::class);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            if(array_key_exists('code', $data)){
                $code = $data['code'];
                //TODO : Add this into a service
                $party = $this->getDoctrine()
                    ->getRepository(Party::class)
                    ->findOneByCode($code);
                
                if(!empty($party)){
                    $partyManager->connectToParty($party);
                    return $this->redirect($this->generateUrl('party_show', ['id'=>$party->getId()]));
                }else{
                    $this->addFlash(
                        'error',
                        'Party not found!'
                    );
                }
            }else{
                $this->addFlash(
                    'error',
                    'Code not found!'
                );
            }
        }
    
        return $this->render('webgame/party/rejoin_party.html.twig', [
            'form' => $form->createView(),
        ]);
   }
   
    /**
    * @Route("/party/{id}", name="party_show")
    */
   public function show(Request $request,$id,PartyManager $partyManager)
   {
       $party = $this->getDoctrine()
           ->getRepository(Party::class)
           ->find($id);

        if (!$party) {
            throw $this->createNotFoundException(
                'No party found for id '.$id
            );
        }

        //Add the new question form on the Party Display page
        $form = $this->createForm(QuestionsType::class, $party);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {    
            $party = $form->getData();
            $partyManager->updateParty($party);
        }
    
        return $this->render('webgame/party/show.html.twig', [
            'name' => $party->getName(),
            'id' => $party->getId(),
            'code' => $party->getCode(),
            'players'=> $party->getPlayers(),
            'form' => $form->createView(),
        ]);
   }
   
}