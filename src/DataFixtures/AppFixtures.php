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

		
		for ( $i=1; $i<=11; $i++){

			$taxon = new Taxon();

			$gName = $faker->word;
			$sName = $faker->word;
			$cName = $faker->word;
			$family = $faker->word;

			$coverImage = $faker->imageUrl(300, 300);
			$coverImage = \str_replace( 'https://', 'http://', $coverImage);

			$intro 		= $faker->paragraph(\mt_rand(1,3));
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
			

			for ($j=1; $j<=\mt_rand(2, 5); $j++){

				$image = new Image();

				$imageUrl = \str_replace( 'https://', 'http://', $faker->imageUrl());
				$image->setUrl($imageUrl)
						->setCaption( $faker->sentence())
						->setTaxon($taxon);

				$manager->persist($image);
			}
			
			$manager->persist($taxon);
		}

		$manager->flush();
    }
}
