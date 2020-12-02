<?php

namespace App\Form;

use App\Entity\Taxon;
use App\Form\ImageType;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class TaxonType extends AbstractType
{

	/**
	 * Crée une configuration de base pour les champs du formulaire
	 *
	 * @param string $label
	 * @param string $placeholder
	 * @param boolean $required
	 * @return array
	 */
	private function mkBasics( $label, $placeholder, $required=true, $options=[] )
	{
		return array_merge([
			'label' => $label,
			'attr' => [
				'placeholder'=> $placeholder
			],
			'required' => $required,
		], $options);
	}

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
			->add('commonName', TextType::class, $this->mkBasics("nom normalisé", "nom normalisé") )
            ->add('genericName', TextType::class, $this->mkBasics("nom générique", "genre") )
            ->add('specificName', TextType::class, $this->mkBasics("nom spécifique", "espèce") )
            ->add('family', TextType::class, $this->mkBasics("famille", "famille") )
			->add('mainImage', ImageType::class, $this->mkBasics("image principale", "donnez une url pour l'image", false))
            ->add('vernacularNames', TextType::class, $this->mkBasics("noms vernaculaires", "noms usuels ..", false))
            ->add('description', CKEditorType::class, $this->mkBasics("description", "la description compléte du taxon", false))
			->add('flowering', TextType::class, $this->mkBasics("floraison", "jfmamjjasond", false))
            ->add('usedTo', TextType::class, $this->mkBasics("utilisation", "utilisation", false))
			->add('toxicity', IntegerType::class, $this->mkBasics("toxicité", "0:pas .. 7:mortelle", false) )
			->add( 'images', CollectionType::class, $this->mkBasics("images supplémentaires", "bzzz", false, [
				'entry_type' => ImageType::class,
				'allow_add' => true,
				'allow_delete' => true
			] ))
			// ->add('save', SubmitType::class, [
			// 	'label' => 'Ajouter cette entrée à l\' index',
			// 	'attr' => [
			// 		'class' => 'btn btn-primary'
			// 	]
			// ])
			;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Taxon::class,
        ]);
    }
}
