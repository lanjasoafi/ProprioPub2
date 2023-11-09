<?php

namespace App\Controller\Admin;

use App\Entity\Image;
use App\Form\ImageType;
use App\Entity\Property;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ImageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Image::class;
    }

    
    public function configureFields(string $pageName): iterable
{
    // ... autres champs

    // $images = CollectionField::new('image')
    //     ->setEntryType(ImageType::class)
    //     ->setFormTypeOptions([
    //         'required' => false,
    //         'mapped' => true,
    //         'by_reference' => false, // Important pour la manipulation des images
    //     ])
    //     ->onlyOnForms();
    $images = ImageField::new('image')
    ->setBasePath('upload/images/')
    ->setUploadDir('public/upload/images/');
    $property = AssociationField::new('Property');

    return [
        // ... autres champs
        $images,
        $property
    ];
}
    
}
