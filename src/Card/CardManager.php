<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 24/07/2018
 * Time: 14:23
 */

namespace App\Card;


use App\Entity\Card;
use Doctrine\ORM\EntityManagerInterface;

class CardManager
{

    private $em;


    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    public function generateCode($centerCode)
    {
        //Génération des 9 premiers chiffres de la carte
        $cardCode = $this->generateCardCode();
        $code = $centerCode . $cardCode;

        //Génération du modulo
        $modulo = $this->checksum($code);

        //Code final
        $finalCode = $code . $modulo;

        //Vérification de l'unicité du code final
        if($this->checkCode($finalCode) === true){
            $this->generateCode($centerCode);
        }

        return $finalCode;
    }

    public function generateCardCode(){
        return rand(100000, 999999);
    }

    public function checksum(float $number){
        $sum = 0;

        $chars = str_split($number);
        foreach ($chars as $char) {
            $sum += $char;
        }

        $modulo = $sum % 9;

        return $modulo;
    }

    public function checkCode(float $code){
        $exists = false;
        $repository = $this->em->getRepository(Card::class);

        $code = $repository->findByCode($code);  /// implémenter findbycode().

        //Le code existe en BDD
        if ($code !== null) {
            $exists = true;
            return $exists;
        }

        return $exists;
    }

    public function testgenerateCode($centerCode)
    {

        $finalCode = $centerCode;

        $repository = $this->em->getRepository(Card::class);

        $code = $repository->findByCode($finalCode);  /// implémenter findbycode().

        if ($code !== null) {
            $finalCode = $this->generateCode('444');
        }

        return $finalCode;
    }

}