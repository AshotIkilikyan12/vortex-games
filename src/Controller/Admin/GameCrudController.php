<?php

namespace App\Controller\Admin;

use App\Entity\Game;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField; // Ավելացրեք այս տողը

class GameCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Game::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield TextField::new('title', 'Վերնագիր');
        yield TextEditorField::new('description', 'Նկարագրություն');
        yield IntegerField::new('price', 'Գին (Դրամ)');

        yield ImageField::new('image', 'Խաղի Նկար')
            ->setBasePath('uploads/games/')
            ->setUploadDir('public/uploads/games/')
            ->setUploadedFileNamePattern('[randomhash].[extension]')
            ->setRequired(false);

        // Սա թույլ կտա տեսնել և կառավարել այն օգտատերերին, ովքեր ունեն այս խաղը
        yield AssociationField::new('users', 'Գնորդներ (Օգտատերեր)')
            ->setFormTypeOptions([
                'by_reference' => false,
            ])
            ->autocomplete()
            ->hideOnIndex(); // Ցուցակի էջում թաքցնում ենք՝ տեսքը մաքուր պահելու համար
    }
}