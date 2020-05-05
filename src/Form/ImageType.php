<?php

namespace App\Form;

use App\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ImageType extends AbstractType
{
		/**
	 * Crée un configuration de base pour les champs du formulaire
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
			->add('url', UrlType::class, $this->mkBasics("url de l'image", "vous pouvez indiquer une url pour l'image ..", false))
			->add('uploadedImageFile', VichImageType::class, $this->mkBasics("(image à télécharger)", "ou télécharger un fichier !", false))
            ->add('caption', TextType::class, $this->mkBasics("Titre de l'image", "veuillez donner un titre à cette image .."))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
        ]);
    }
}
