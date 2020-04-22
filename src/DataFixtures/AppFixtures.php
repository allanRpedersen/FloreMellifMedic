<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Image;
use App\Entity\Taxon;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
		$faker = Factory::create('fr-FR');
		
		
		for ( $i=1; $i<=11; $i++){}

			$taxon = new Taxon();

			$gName = 'Aconit napel';
			$sName = 'aconitum';
			$cName = 'napellus';
			$family = 'Ranunculaceae';

			$coverImage = 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/2a/Aconitum_napellus_-_K%C3%B6hler%E2%80%93s_Medizinal-Pflanzen-151.jpg/290px-Aconitum_napellus_-_K%C3%B6hler%E2%80%93s_Medizinal-Pflanzen-151.jpg';
			
			//$coverImage = \str_replace( 'https://', 'http://', $coverImage);

			$intro 		= 'Casque de Jupiter, ';
			$desc 		= '<p>' . join('<p></p>', $faker->paragraphs(5)) . '</p>';
			$used 		= $faker->word;

			$taxon->setGenericName($gName)
					->setSpecificName($sName)
					->setCommonName($cName)
					->setCoverImage($coverImage)
					->setDescription($desc)
					->setIntroduction($intro)
					->setToxicity(\mt_rand(0,4))
					->setUsedTo($used)
					->setFlowering("jfMAMJJASOnd")
					->setFamily($family);
			

			for ($j=1; $j<=\mt_rand(2, 5); $j++){}

				$image = new Image();

				// $coverImage = 'https://upload.wikimedia.org/wikipedia/commons/b/ba/Aconitum_napellus00.jpg';
				$imageUrl = \str_replace( 'https://', 'http://', $faker->imageUrl());
				$image->setUrl('https://upload.wikimedia.org/wikipedia/commons/b/ba/Aconitum_napellus00.jpg')
						->setCaption( $faker->sentence())
						->setTaxon($taxon);

				$manager->persist($image);
			
			
		$manager->persist($taxon);
		$manager->flush();
    }
}