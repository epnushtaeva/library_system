<?php


namespace App\DataTables;


use App\Entity\Book;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pentiminax\UX\DataTables\Attribute\AsDataTable;
use Pentiminax\UX\DataTables\Column\AttributeColumnReader;
use Pentiminax\UX\DataTables\Contracts\ApiResourceCollectionUrlResolverInterface;
use Pentiminax\UX\DataTables\Contracts\ColumnAutoDetectorInterface;
use Pentiminax\UX\DataTables\Model\AbstractDataTable;
use Pentiminax\UX\DataTables\Column\TextColumn;
use Pentiminax\UX\DataTables\Column\NumberColumn;
use Pentiminax\UX\DataTables\Model\DataTable;
use Symfony\Bundle\SecurityBundle\Security;

#[AsDataTable(Book::class)]
class BooksDataTable extends AbstractDataTable
{
    private $security;
    private $userRepository;

    public function __construct(ColumnAutoDetectorInterface $columnAutoDetector = null,
                                EntityManagerInterface $em = null,
                                ApiResourceCollectionUrlResolverInterface $apiResourceCollectionUrlResolver = null,
                                AttributeColumnReader $attributeColumnReader = null,
                                Security $security,
                                UserRepository $userRepository)
    {
        $this->security = $security;
        $this->userRepository = $userRepository;
        parent::__construct($columnAutoDetector, $em, $apiResourceCollectionUrlResolver, $attributeColumnReader);
    }


    public function configureDataTable(DataTable $table): DataTable
    {
        $table =  parent::configureDataTable($table);
        $table->order([
            [0, 'desc']
        ]);
        $table->pageLength(50);
        $table->searching(true);
        $table->columnControl();
        $table->ajax('/books')
        ->serverSide(true)
        ->processing(true);
        return $table;
    }

    public function configureColumns(): iterable
    {
        yield NumberColumn::new('id', 'ID')->setOrderable(true)->setVisible(false);
        yield TextColumn::new('name', 'Название')->setOrderable(false)->setSearchable(true);
        yield TextColumn::new('description', 'Описание')->setOrderable(false);
        yield TextColumn::new('author', 'Автор')->setOrderable(false)->setSearchable(true)->setField('author.fullName');
        yield TextColumn::new('janre', 'Жанр')->setOrderable(false)->setSearchable(true)->setField('janre.name');
        yield NumberColumn::new('freeCount', 'Количество доступных экземпляров')->setOrderable(false);
        yield TextColumn::new('actions', ' ')->setOrderable(false);
    }

    protected function mapRow(mixed $item): array
    {
        return [
            'id' => $item->getId(),
            'name' => $item->getName(),
            'description' => $item->getDescription(),
            'author' => $item->getAuthor()->getFullName(),
            'janre' => $item->getJanre()->getName(),
            'freeCount' => $item->getFreeCount(),
            'actions' => $this->getActionButtons($item)
        ];
    }

    protected function getActionButtons(Book $item):string
    {
        $buttons = '';

        if($this->security->isGranted('ROLE_ADMIN')){
            $buttons = '<a class="btn btn-primary mr-2 mb-2" href="/edit_book/'.strval($item->getId()).'">Редактировать</a>
                <a class="btn btn-danger mr-2 mb-2" onclick="javascript: showRemoveModal('.$item->getId().', \'remove_book\', \'книги\', \'книгу\')">Удалить</a>
                <a class="btn btn-info mr-2 mb-2" href="/book_cells/'.strval($item->getId()).'">Посмотреть ячейки</a>';

        }

        if($item->getFreeCount() > 0){
            if(!$this->userRepository->isBroned($this->security->getUser()->getUserIdentifier(), $item->getId())) {
                $buttons .= '<a class="btn btn-info mr-2 mb-2" onclick="javascript: showBroneModal(' . $item->getId() . ')">Забронировать</a>';
            }
        }

        return $buttons;
    }
}
