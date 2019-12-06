<?php

namespace App\DataFixtures;

use App\Entity\Bien;
use App\Entity\Tipe;
use App\Entity\Categorie;
use Doctrine\DBAL\Types\IntegerType;
use Doctrine\Bundle\FixturesBundle\Fixture;
//use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ObjectManager;

class BienFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $faker = \Faker\Factory::create();

                $libelle = "";
                for($i = 1; $i <= 2; $i++) {
                    $tipe = new Tipe();
                    if($i == 1) {
                        $libelle = "location";
                    }
                    else if($i == 2) {
                        $libelle = "vente";
                    }
                
                        $tipe->setLibelle($libelle);
                    
                        $manager->persist($tipe);
                }
            
                $libelle2 = "";
                for($j = 1; $j <= 4; $j++) {
                    $categorie=new Categorie();
                    if($j == 1) {
                        $libelle2 = "appartement";
                    }
                    else if($j == 2) {
                        $libelle2 = "terrain";
                    }
                    else if($j == 3) {
                        $libelle2 = "pavillon";
                    }
                    else if($j == 4) {
                        $libelle2 = "hml";
                    }
                
                    $categorie->setLibelle($libelle2);

                    $manager->persist($categorie);
                    for($k=1; $k <= 10; $k++) {
                        $bien = new Bien();
                        if($j == 2) {
                            $etage = 0;
                            $chambre = 0;
                        }
                        else {
                            $etage=mt_rand(1,10);
                            $chambre=mt_rand(1,10);
                        }
                        if($j > 4 || $j < 6) {
                            $statut = "archivé";
                        }
                        else {
                            $statut = "en cours";
                        }
                        $bien->setDescription($faker->sentence($nbWords = 6, $variableNbWords = true))
                            ->setSurface(mt_rand(10,100))
                            ->setEtage($etage)
                            ->setChambre($chambre)
                            ->setImage($faker->imageUrl($width = 300, $height = 200))
                            ->setStatut($statut)
                            ->setCreatedAt($faker->dateTimeBetween($startDate = '-3days', $endDate = 'now', $timezone = null))
                            ->setCategorie($categorie)
                            ->setTipe($tipe);

                        $manager->persist($bien);
                    }
                }
        
        
        $manager->flush();
    }
}
