<?php

namespace App\Controller;

use App\Entity\Taxon;
use App\Form\TaxonType;
use App\Repository\TaxonRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
// use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



/**
 * @Route("/taxon")
 */
class TaxonController extends AbstractController
{
	private $ascendantOrder = true;


    /**
     * @Route("/", name="taxon_index", methods={"GET"})
     */
    public function index(TaxonRepository $repo )
    {
		// $repo = $this->getDoctrine()->getRepository(Taxon::class);
		// le repo est obtenu par injection de dépendances !!!

		// $taxons = $repo->findAll();

		$orderBy = ($this->ascendantOrder ? 'ASC' : 'DESC');
		$taxons = $repo->findByCommonName($orderBy);

			// not working, no toggle !!  :-#
		$this->ascendantOrder = !($this->ascendantOrder);
		// dump($this->ascendantOrder);

        return $this->render('taxon/index.html.twig', [
            'taxons' => $taxons
        ]);
	}
	
    /**
     * @Route("/generic", name="taxon_index_generic", methods={"GET"})
     */
    public function index_generic(TaxonRepository $repo )
    {
		// $repo = $this->getDoctrine()->getRepository(Taxon::class);
		// le repo est obtenu par injection de dépendances !!!

		// $taxons = $repo->findAll();

		$orderBy = ($this->ascendantOrder ? 'ASC' : 'DESC');
		$taxons = $repo->findByGenericName($orderBy);

        return $this->render('taxon/index.html.twig', [
            'taxons' => $taxons
        ]);
	}
	
	/**
	 * Modified version of `filter_var`.
	 *
	 * @param  mixed $url Could be a URL or possibly much more.
	 * @return bool
	 */
	private function validate_url( $url )
	{
		$url = trim( $url );

		return (
			( strpos( $url, 'http://' ) === 0 || strpos( $url, 'https://' ) === 0 ) &&
			filter_var(
				$url,
				FILTER_VALIDATE_URL,
				FILTER_FLAG_SCHEME_REQUIRED || FILTER_FLAG_HOST_REQUIRED
			) !== false
		);
	}

	/**
	 * Permet la création d'une entrée dans l'index
	 * 
	 * @Route( "/new", name="taxon_new" )
	 * @IsGranted("ROLE_USER")
	 *
	 * @param Request $request
	 * 
	 * @return Response
	 */
	public function new( Request $request, UploaderHelper $helper /*, ObjectManager $manager */) : Response
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
			
			// CoverImage file upload (automatic, thanks to VichUploaderBundle !-)
			// $coverImageFileInfo = $form['coverImageFile']->getData();
			
			// dump($taxon, $helper->asset($taxon));
			$isUpload = $taxon->getCoverImageFile();
			
			// l'injection de dépendance ne fonctionne pas pour récupérer le $manager !!! ?????
			// pb update doctrine2.0 ??
			$manager = $this->getDoctrine()->getManager();
			
			// dd($_SERVER);
			
			//
			// gestion des illustrations supplémentaires
			foreach($taxon->getImages() as $image){
				$image->setTaxon($taxon);
				$manager->persist($image);
			}
			
			$manager->persist($taxon); // to move and name the uploaded file ..

			
			if ($isUpload) {
				// update~add the path where is stored the uploaded file
				$taxon->setCoverImageName($helper->asset($taxon));
				$manager->persist($taxon);
			}
			
			$manager->flush();
			
			$this->addFlash(
				'success',
				"L'entrée <strong>{$taxon->getCommonName()}</strong> a bien été enregistrée !"
			);

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
	 * @IsGranted("ROLE_USER")
     */
    public function edit(Request $request, Taxon $taxon, UploaderHelper $helper): Response
    {
        $form = $this->createForm(TaxonType::class, $taxon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
			
			$manager = $this->getDoctrine()->getManager();
			
			// dump($taxon);
			$isUpload = $taxon->getCoverImageFile();
			
			//
			// gestion des illustrations supplémentaires
			foreach($taxon->getImages() as $image){
				$image->setTaxon($taxon);
				$manager->persist($image);
			}
			
			$manager->persist($taxon);
			$manager->flush();

			if ($isUpload){
				// update~add the path where is stored the uploaded file
				$taxon->setCoverImageName($helper->asset($taxon));
				$manager->persist($taxon);
				$manager->flush();
			}

		
			$this->addFlash(
				'success',
				"Les modifications de l'entrée <strong>{$taxon->getCommonName()}</strong> ont bien été enregistrées !"
			);
			
            return $this->redirectToRoute('taxon_show',[
				'slug' => $taxon->getSlug()
			]);
        }

        return $this->render('taxon/edit.html.twig', [
            'taxon' => $taxon,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}", name="taxon_delete", methods={"DELETE"})
	 * @IsGranted("ROLE_USER")
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
