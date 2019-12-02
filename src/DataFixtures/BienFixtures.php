<?php

namespace App\DataFixtures;

use App\Entity\Bien;
use App\Entity\Tipe;
use App\Entity\Categorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class BienFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $faker = \Faker\Factory::create();

        
        for($i = 1; $i <= 3; $i++) {
            $tipe = new Tipe();
            if($i == 1) {
                $libelle = "location";
            }
            else if($i == 2) {
                $libelle = "vente";
            }
            else if($i == 3) {
                $libelle = "achat";
            }
            $tipe->setLibelle($libelle);
            
            $manager->persist($tipe);
        }
      
        for($i = 1; $i <= 3; $i++) {
            
            if($i == 1) {
                $libelle = "appartement";
            }
            else if($i == 2) {
                $libelle = "terrain";
            }
            else if($i == 3) {
                $libelle = "pavillon";
            }
            $categorie=new Categorie();
            $categorie->setLibelle($libelle);

            $manager->persist($categorie);

            for($j=1; $j <= 10; $j++) {
                
                $bien = new Bien();
                if($i == 2) {
                    $etage = 0;
                    $chambre = 0;
                }
                else {
                    $etage=mt_rand(1,10);
                    $chambre=mt_rand(1,10);
                }
                if($j < 5 || $j > 6) {
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
                     ->setCategorie($categorie);

                     $manager->persist($bien);

            }
        }
        $manager->flush();
    }
}
