<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{


    /**
     * @Route("/", name="frontpage")
     */
    public function frontpage()
    {
		$species = [ 'bee' => 'honey', 'wasp' => 'meat', 'hornet' => 'meat' ];

        return $this->render('front/index.html.twig', [
			'title' => 'Front Page',
			'user' => 'Ducky',
			'species' => $species
        ]);
    }
}
