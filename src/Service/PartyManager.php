<?php
namespace App\Service;

use App\Entity\Party;
use App\Entity\Player;
use Doctrine\ORM\EntityManagerInterface;

class PartyManager{
    
    private $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function connectToParty(Party $party,Player $player = null){
        if(empty($player)){
            $player = new Player("New_Player");
        }
        $this->entityManager->persist($player);
        $party->addPlayer($player);
        $this->entityManager->flush();
        return $party;
    }

    /**
     * Init and save a new party
     */
    public function saveNewParty(Party $newParty){
        $code = $this->generateUniqueCode();
        $newParty->setCode($code);
        $this->entityManager->persist($newParty);
        $this->entityManager->flush();
        return $newParty;
    }

    /**
     * Update an existing party
     */
    public function updateParty(Party $party){
        $this->entityManager->persist($party);
        $this->entityManager->flush();
        return $party;
    }

    /**
     * Generate an unique 8 character code
     */
    private function generateUniqueCode(){
        $code = '';
        do{
            $code = $this->generateCode();
            $existingParty = $this->entityManager->getRepository(Party::class)
            ->findByCode($code);
        }while(!empty($existingParty));
        return $code;
    }

    /**
     * generate a simple 8 character code
     */
    private function generateCode(){
        $codeLength = 8;
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $code = '';
        for ($i = 0; $i < $codeLength; $i++) {
            $code .= $characters[rand(0, $charactersLength - 1)];
        }
        return $code;
    }
    
}