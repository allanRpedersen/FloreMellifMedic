<?php

namespace App\Controller;

use App\Entity\Taxon;
use App\Form\TaxonType;
use App\Repository\TaxonRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
// use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TaxonController extends AbstractController
{
    /**
     * @Route("/taxons", name="taxons_index")
     */
    public function index(TaxonRepository $repo )
    {
		// $repo = $this->getDoctrine()->getRepository(Taxon::class);
		// le repo est obtenu par injection de dépendances !!!

		$taxons = $repo->findAll();

        return $this->render('taxon/index.html.twig', [
            'taxons' => $taxons
        ]);
	}
	
	/**
	 * Permet la création d'une entrée dans l'index
	 * 
	 * @Route( "/taxons/create", name="taxons_create" )
	 *
	 * @param Request $request
	 * 
	 * @return Response
	 */
	public function create( Request $request /*, ObjectManager $manager */)
	{
		$taxon = new Taxon;

		// $form = $this->createFormBuilder($taxon)
		// 			->add('commonName')
		// 			->add('genericName')
		// 			->add('specificName')
		//				...
		// 			->add('description')
		// 			->add('toxicity')
		// 			->add('save', SubmitType::class, [
		// 				'label' => 'Ajouter cette entrée à l\' index',
		// 				'attr' => [
		// 					'class' => 'btn btn-primary'
		// 				]
		// 			])
		// 			->getForm();

		$form = $this->createForm( TaxonType::class, $taxon );

		$form->handleRequest( $request );

		if ( $form->isSubmitted() && $form->isValid() ) {

			// l'injection de dépendance ne fonctionne pas pour récupérer le $manager !!! ?????
			// pb update doctrine2.0 ??
			$manager = $this->getDoctrine()->getManager();

			$manager->persist($taxon);
			$manager->flush();

			return $this->redirectToRoute( 'taxons_show', [
				'slug' => $taxon->getSlug()
			] );
		}

		return $this->render( 'taxon/create.html.twig', [
			'form' => $form->createView()
		] );
	}


	/**
	 * Permet l'affichage d'une fiche de l'index
	 * 
	 * @Route( "/taxons/{slug}", name="taxons_show" )
	 *
	 * @return Response
	 */
	public function show( /*$slug,*/ Taxon $taxon )
	{
		// on récupère l'annonce qui correspond au slug
		// $taxon = $repo->findOneBySlug( $slug );
		// La variable $taxon est initialisée par la "conversion de paramètres" qui trouve l'entité correspondant
		// au paramètre $slug fournit par la route ... $slug qui peut être supprimé des param. de la fonction

		return $this->render( 'taxon/show.html.twig', [
			'taxon' => $taxon
		]);

	}

}
