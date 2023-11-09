<?php

// src/Controller/Admin/PropertyCrudController.php

namespace App\Controller\Admin;

use App\Form\ImageType;
use App\Entity\Property;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class PropertyCrudController extends AbstractCrudController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public static function getEntityFqcn(): string
    {
        return Property::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $user = $this->security->getUser();

        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title'),
            TextEditorField::new('description'),
            TextField::new('adress'),
            TextField::new('type'),
            TextField::new('status'),
            NumberField::new('surface'),
            IntegerField::new('chambre'),
            IntegerField::new('bain'),
            IntegerField::new('garage'),
            MoneyField::new('price')->setCurrency('EUR'),
            // CollectionField::new('images')
            //     ->setEntryType(ImageType::class) // You need to create this form type
            //     ->setFormTypeOptions([
            //         'allow_add' => true,
            //         'allow_delete' => true,
            //     ]),
            AssociationField::new('images')->setCrudController(ImageCrudController::class)
                
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setPaginatorPageSize(9);
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        // Handle image uploads and associations
        if ($entityInstance instanceof Property) {
            $images = $entityInstance->getImages();
           
            foreach ($images as $image) {
                // Handle image upload
                $imageFile = $image->getImageFile();
                
                if ($imageFile instanceof UploadedFile) {
                    // Generate a unique name for the file and move it to the appropriate directory
                    $fileName = md5(uniqid()) . '.' . $imageFile->guessExtension();
                    
                    $imageFile->move(
                        $this->getParameter('image_directory'), // Adjust this path
                        $fileName
                    );

                    // Update the imageName property with the generated file name
                    $image->setImageName($fileName);
                    
                    // Set the associated property
                    $image->setProperty($entityInstance);
                    
                }
            }
        }

        // Persist the entity
        parent::persistEntity($entityManager, $entityInstance);
    }
}
