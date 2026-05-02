<?php


namespace App\DataTables;
use App\Entity\Author;
use Pentiminax\UX\DataTables\Attribute\AsDataTable;
use Pentiminax\UX\DataTables\Column\TextColumn;
use Pentiminax\UX\DataTables\Model\AbstractDataTable;
use Pentiminax\UX\DataTables\Model\DataTable;

#[AsDataTable(Author::class)]
class AuthorsDataTable extends AbstractDataTable
{
    public function configureDataTable(DataTable $table): DataTable
    {
        $table =  parent::configureDataTable($table);
        $table->order([
            [0, 'desc']
        ]);
        $table->pageLength(50);
        $table->searching(true);
        $table->columnControl();
        $table->ajax('/authors')
            ->serverSide(true)
            ->processing(true);
        return $table;
    }

    public function configureColumns(): iterable
    {
        yield TextColumn::new('fullName', 'ФИО')->setOrderable(true)->setSearchable(true);
        yield TextColumn::new('birthDate', 'Дата рождения')->setOrderable(false)->setSearchable(false);
        yield TextColumn::new('address', 'Место проживания/рождения')->setOrderable(false)->setSearchable(false);
        yield TextColumn::new('actions', ' ')->setOrderable(false);
    }

    public function mapRow(mixed $row): array
    {
        return [
            'fullName' => $row->getFullName(),
            'birthDate' => $row->getBirthDate()->format('d.m.Y'),
            'address' => $row->getAddress(),
            'actions' => $this->getActions($row)
        ];
    }

    public function getActions(Author $author):string
    {
        return  '<a class="btn btn-primary mr-2 mb-2" href="/edit_author/'.strval($author->getId()).'">Редактировать</a>
         <a class="btn btn-danger mr-2 mb-2" onclick="javascript: showRemoveModal('.$author->getId().', \'remove_author\', \'автора\', \'автора\')">Удалить</a>';
    }
}
