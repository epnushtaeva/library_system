<?php


namespace App\Constants;


interface BookFormVialotionMessages
{
  const ZERO_BOOKS_COUNT_MESSAGE = 'Ошибка: Количество экземпляров должно быть больше нуля';
  const BOOKS_COUNT_NOT_EQUAL_CELLS_COUNT_MESSAGE = 'Ошибка: Количество экземпляров должно быть равно количеству ячеек, указанных через запятую в соответствующем поле';
  const EMPTY_CELL_NAME_MESSAGE = 'Ошибка: Названия ячейки, где хранится экземпляр не может быть пустым';
  const NOT_RIGHT_SYMBOLS_IN_CELL_NAME_MESSAGE = 'Ошибка: Названия ячейки, где хранится экземпляр, может содержать только заглавные буквы латинского алфавита и цифры';
  const NO_FREE_BOOK_FOR_BRONE = 'Невозможно забронировать данную книгу, так как нет свободных экземпляров для бронирования';
  const BRONE_SUCCESS = 'Выбранная книга была успешно забронирована, вы можете её забрать в течение 10 дней в библиотеке';
  const MORE_FIVE_BOOKS = 'Вы не можете забронировать больше 5 книг';
}
