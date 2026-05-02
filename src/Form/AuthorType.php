<?php


namespace App\Form;


use App\Constants\AuthorFormValidationMessages;
use App\Entity\Author;
use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class AuthorType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('fullName', TextareaType::class, ['required' => true, 'label' => 'ФИО', 'attr' => ['class' => 'form-control']])
            ->add('address', TextareaType::class, ['required' => true, 'label' => 'Место рождения/город проживания', 'attr' => ['class' => 'form-control']])
            ->add('birthDate', DateType::class, ['required' => true, 'label' => 'Дата рождения', 'attr' => ['class' => 'form-control']]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Author::class,
            'csrf_protection' => false,
            'constraints' => [new Callback([$this, 'validateBirthDate'])]
        ]);
    }

    public function validateBirthDate($data, ExecutionContextInterface $context)
    {
        $formData = $context->getRoot()->getData();

        if(intval((new DateTime())->diff($formData->getBirthDate())->format('%a'))<=0){
            $context->buildViolation(AuthorFormValidationMessages::BIRTH_DATE_MORE_THAN_NOW_MESSAGE)->addViolation();
            return;
        }
    }
}
