<?php


namespace App\Form;

use App\Constants\BookFormVialotionMessages;
use App\Constants\CommonConstants;
use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Janre;
use App\Form\DataTransformers\BookCellDataTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class BookType extends AbstractType
{
    public function __construct(
        private BookCellDataTransformer $cellDataTransformer
    )
    {

    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextareaType::class, ['required' => true, 'label' => 'Название', 'attr' => ['class' => 'form-control']])
            ->add('description', TextareaType::class, ['required' => true, 'label' => 'Описание' , 'attr' => ['class' => 'form-control']])
            ->add('allCount', NumberType::class, ['required' => false, 'label' => 'Количество экземпляров',  'attr' => ['min' => 1, 'class' => 'form-control']])
            ->add('author', EntityType::class, [
                'class' => Author::class,
                'choice_label' => 'fullName',
                'required' => true,
                'empty_data' => '',
                'label' => 'Автор',
                'attr' => ['class' => 'form-control']
            ])
            ->add('janre', EntityType::class, [
                'class' => Janre::class,
                'choice_label' => 'name',
                'required' => true,
                'empty_data' => '',
                'label' => 'Жанр',
                'attr' => ['class' => 'form-control']
            ]);


        $builder->add('cells', TextareaType::class, [
            'label' => 'Укажите через запятую названия ячеек, где хранятся соответствующие экземпляры книг',
            'required' => true,
            'attr' => ['class' => 'form-control']
        ]);

        $builder->get('cells')->addModelTransformer($this->cellDataTransformer);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
            'csrf_protection' => false,
            'constraints' => array(
                    new Callback([$this, 'validateBooksAndCellsCount'])
                )
        ]);
    }

    public function validateBooksAndCellsCount($data, ExecutionContextInterface $context)
    {
        $formData = $context->getRoot()->getData();

        if($formData->getAllCount() <= 0){
            $context->buildViolation(BookFormVialotionMessages::ZERO_BOOKS_COUNT_MESSAGE)->addViolation();
            return;
        }

        $cells = $formData->getCells();

        if($formData->getAllCount() != sizeof($cells)){
            $context->buildViolation( BookFormVialotionMessages::BOOKS_COUNT_NOT_EQUAL_CELLS_COUNT_MESSAGE)->addViolation();
            return;
        }

        $rightSymbols = str_split(CommonConstants::CELL_NAME_RIGHT_SYMBOLS);

        foreach($cells as $cell){
            if(!$cell || !$cell->getCell() || !$cell->getCell()->getCellNumber() || strlen($cell->getCell()->getCellNumber())==0){
                $context->buildViolation(BookFormVialotionMessages::EMPTY_CELL_NAME_MESSAGE)->addViolation();
                return;
            }

            $s = str_split($cell->getCell()->getCellNumber());

            foreach($s as $char){
                if(!in_array($char, $rightSymbols)){
                        $context->buildViolation(BookFormVialotionMessages::NOT_RIGHT_SYMBOLS_IN_CELL_NAME_MESSAGE)->addViolation();
                        return;
                }
            }
        }
    }
}
