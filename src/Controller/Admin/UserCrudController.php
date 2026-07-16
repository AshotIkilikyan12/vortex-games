<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield EmailField::new('email', 'Էլ. հասցե');
        yield ArrayField::new('roles', 'Դերեր')->hideOnIndex(); // Թույլ է տալիս տեսնել օգտատիրոջ դերերը (օրինակ՝ ROLE_ADMIN)

        // Սա թույլ կտա ադմինին ավելացնել կամ ջնջել խաղեր օգտատիրոջ գրադարանից
        yield AssociationField::new('games', 'Գրադարանի Խաղերը')
            ->setFormTypeOptions([
                'by_reference' => false, // Պարտադիր է ManyToMany-ի ճիշտ պահպանման համար
            ])
            ->autocomplete(); // Օգտակար է, եթե խաղերը շատանան
    }
}