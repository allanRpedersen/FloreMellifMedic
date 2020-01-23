<?php

namespace App\Form;

use App\Entity\Taxon;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TaxonType extends AbstractType
{

	/**
	 * Crée un configuration de base pour les champs du formaulaire
	 *
	 * @param string $label
	 * @param string $placeholder
	 * @return array
	 */
	private function mkBasics( $label, $placeholder )
	{
		return [
			'label' => $label,
			'attr' => [
				'placeholder'=> $placeholder
			]
		];
	}

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
		->add('commonName', TextType::class, $this->mkBasics("nom normalisé", "nom commun") )
            ->add('genericName', TextType::class, $this->mkBasics("nom générique", "genre") )
            ->add('specificName', TextType::class, $this->mkBasics("nom spécifique", "espèce") )
            ->add('family', TextType::class, $this->mkBasics("famille", "famille") )
            ->add('coverImage', UrlType::class, $this->mkBasics("image principale", "url de l'image") )
            ->add('introduction', TextType::class, $this->mkBasics("introduction", "présentation du taxon") )
            ->add('description', TextareaType::class, $this->mkBasics("description", "la description compléte du taxon"))
            ->add('flowering', TextType::class, $this->mkBasics("floraison", "jfmamjjasond") )
            ->add('usedTo', TextType::class, $this->mkBasics("utilisation", "utilisation") )
            ->add('toxicity', IntegerType::class )
			->add('save', SubmitType::class, [
				'label' => 'Ajouter cette entrée à l\' index',
				'attr' => [
					'class' => 'btn btn-primary'
				]
			]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Taxon::class,
        ]);
    }
}
