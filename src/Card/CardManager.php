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

    /**
     * Génération du code de la carte, à 10 chiffres
     * @param $centerCode CODE_CENTRE
     * @return string
     */
    public function generateCode($centerCode)
    {
        //Génération des 9 premiers chiffres de la carte
        //CODE_CENTRE + CODE_CARTE
        $cardCode = $this->generateCardCode();
        $code = $centerCode . $cardCode;

        //Génération du modulo
        //CHECKSUM
        $modulo = $this->checksum($code);

        //Code final
        $finalCode = $code . $modulo;

        //Vérification de l'unicité du code final
        if($this->checkCode($finalCode) === true){
            $this->generateCode($centerCode);
        }

        return $finalCode;
    }

    /**
     * Génération du CODE_CARTE
     * @return int
     */
    public function generateCardCode(){
        //Génère un nombre aléatoire entre 100000 et 999999, afin d'être certains d'obtenir un nombre à 6 chiffres
        return rand(100000, 999999);
    }

    /**
     * Génération du CHECKSUM
     * @param float $number CODE_CENTRE + CODE_CARTE
     * @return int
     */
    public function checksum(float $number){
        $sum = 0;

        // On convertit le code en tableau avec chaque caractère dedans
        $chars = str_split($number);

        //On parcourt caractère par caractère et on les somme
        foreach ($chars as $char) {
            $sum += $char;
        }

        //On obtient le CHECKSUM, en faisant la somme trouvée modulo 9
        $modulo = $sum % 9;

        return $modulo;
    }

    /**
     * Vérifie si le code existe ou pas en BDD
     * @param float $code
     * @return bool
     */
    public function checkCode(float $code){
        $exists = false;
        $repository = $this->em->getRepository(Card::class);

        // On cherche si le code existe en BDD
        $code = $repository->findByCode($code);

        //Si le code existe en BDD
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