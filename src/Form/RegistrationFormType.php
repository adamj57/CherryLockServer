<?php
namespace App\Form;

use App\Entity\SchoolClass;
use App\Entity\User;
use App\Validator\RegisterCodeValid;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("email", EmailType::class, [
                "constraints" => [
                    new Email([
                        "mode" => "html5"
                    ])
                ]
            ])
            ->add("username")
            ->add("name")
            ->add("surname")
            ->add("schoolClass", EntityType::class, [
                "class" => SchoolClass::class,
                "choice_label" => function(SchoolClass $schoolClass) {
                    return $schoolClass->getLabel();
                },
                "group_by" => function(SchoolClass $choice, $key, $value) {
                    switch ($choice->getYear()) {
                        case 1:
                            return "Pierwsze";
                        case 2:
                            return "Drugie";
                        case 3:
                            return "Trzecie";
                        case 4:
                            return "Czwarte";
                        default:
                            return null;
                    }
                },
                "placeholder" => "Klasa:"
            ])
            ->add("plainPassword", PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                "mapped" => false,
                "constraints" => [
                    new NotBlank([
                        "message" => "Wprowadź hasło",
                    ]),
                    new Length([
                        "min" => 8,
                        "minMessage" => "Twoje hasło powinno mieć conajmniej {{ limit }} znaków",
                        "max" => 4096,
                    ]),
                ],
            ])
            ->add("save", SubmitType::class, ['label' => "Zarejestruj"])
            ->add("inviteCode", TextType::class, [
                "mapped" => false,
                "constraints" => [
                    new RegisterCodeValid()
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "data_class" => User::class,
        ]);
    }
}