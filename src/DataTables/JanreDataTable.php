<?php


namespace App\DataTables;


use App\Entity\Janre;
use Pentiminax\UX\DataTables\Attribute\AsDataTable;
use Pentiminax\UX\DataTables\Column\TextColumn;
use Pentiminax\UX\DataTables\Model\AbstractDataTable;
use Pentiminax\UX\DataTables\Model\DataTable;

#[AsDataTable(Janre::class)]
class JanreDataTable extends AbstractDataTable
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
        $table->ajax('/janre')
            ->serverSide(true)
            ->processing(true);
        return $table;
    }

    public function configureColumns(): iterable
    {
        yield TextColumn::new('name', 'Название')->setOrderable(true)->setSearchable(true);
        yield TextColumn::new('actions', ' ')->setOrderable(false);
    }

    public function mapRow(mixed $row): array
    {
        return [
            'name' => $row->getName(),
            'actions' => $this->getActions($row)
        ];
    }

    public function getActions(Janre $janre):string
    {
        return  '<a class="btn btn-primary mr-2 mb-2" href="/edit_janre/'.strval($janre->getId()).'">Редактировать</a>
                 <a class="btn btn-danger mr-2 mb-2" onclick="javascript: showRemoveModal('.$janre->getId().', \'remove_janre\', \'жанр\', \'жанр\')">Удалить</a>';
    }
}
