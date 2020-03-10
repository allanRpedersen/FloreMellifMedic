<?php

namespace App\Controller;

use App\Entity\Taxon;
use App\Form\TaxonType;
use App\Repository\TaxonRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Persistence\ObjectManager;
// use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/taxon")
 */
class TaxonController extends AbstractController
{
    /**
     * @Route("/", name="taxon_index", methods={"GET"})
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
	 * @Route( "/new", name="taxon_new" )
	 *
	 * @param Request $request
	 * 
	 * @return Response
	 */
	public function new( Request $request /*, ObjectManager $manager */) : Response
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

			return $this->redirectToRoute( 'taxon_show', [
				'slug' => $taxon->getSlug()
			] );
		}

		return $this->render( 'taxon/new.html.twig', [
			'form' => $form->createView()
		] );
	}


	/**
	 * Permet l'affichage d'une fiche de l'index
	 * 
	 * @Route( "/{slug}", name="taxon_show" )
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

    /**
     * @Route("/{slug}/edit", name="taxon_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Taxon $taxon): Response
    {
        $form = $this->createForm(TaxonType::class, $taxon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('taxon_index');
        }

        return $this->render('taxon/edit.html.twig', [
            'taxon' => $taxon,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}", name="taxon_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Taxon $taxon): Response
    {
        if ($this->isCsrfTokenValid('delete'.$taxon->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($taxon);
            $entityManager->flush();
        }

        return $this->redirectToRoute('taxon_index');
    }

}
