<?php

namespace App\DataFixtures;

use App\Entity;
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
    
        //Categorie
        $datas = [
            '1' => 'Appartement',
            '2' => 'terrain',
            '3' => 'Maison',
            '4' => 'igloo'
        ];
        foreach ($datas as $data) {
            $item = new Entity\Categorie();
            $item->setLibelle($data);
            $manager->persist($item);
        }
        $manager->flush();

        $categorieRepo = $manager->getRepository('App:Categorie');

        //Types
        $datas2 = [
            '1' => 'location',
            '2' => 'ventes',
        ];

        foreach ($datas2 as $data) {
            $item = new Entity\Tipe();
            $item->setLibelle($data);
            $manager->persist($item);
        }

        $manager->flush();

        $tipeRepo = $manager->getRepository('App:Tipe');

        //Biens
            $cat = 0;
            $tip = 0;
            for($k=1; $k <= 20; $k++) {
                
                $bien = new Entity\Bien();

                $statut = ($k > 4 && $k < 6) ? "archivé" : "en cours";
                $tip = ($tip >= 2) ?  1 : $tip+1;
                $cat = ($cat >= 4) ?  1 :  $cat+1;
                
                if($cat == 2) {
                    $etage = 0;
                    $chambre = 0;
                    $surface = mt_rand(100,1000);
                }
                else {
                    $etage=mt_rand(1,10);
                    $chambre=mt_rand(1,10);
                    $surface = mt_rand(10,100);
                }    
                $bien->setDescription($faker->sentence($nbWords = 6, $variableNbWords = true))
                    ->setSurface($surface)
                    ->setEtage($etage)
                    ->setChambre($chambre)
                    ->setImage($faker->imageUrl($width = 300, $height = 200))
                    ->setStatut($statut)
                    ->setCreatedAt($faker->dateTimeBetween($startDate = '-3days', $endDate = 'now', $timezone = null))
                    ->setCategorie($categorieRepo->find($cat))
                    ->setTipe($tipeRepo->find($tip));

                $manager->persist($bien);
                
            }
                
 
        $manager->flush();
    }
}

        // Reset datas

        // Supprimer la base de donnée
        // php bin/console doctrine:database:drop --force
        // php bin/console d:d:d --force

        // Créer la basez de donnée
        // php bin/console doctrine:database:create
        // php bin/console d:d:c

        // Regarder les requête SQL
        // php bin/console doctrine:schema:updfate --dump-sql

        // Mettre à jour la base de donnée
        // php bin/console doctrine:schema:update --force
        // php bin/console d:s:u -f

        // Categories