<?php


namespace App\Constants;


interface AuthorFormValidationMessages
{
  const BIRTH_DATE_MORE_THAN_NOW_MESSAGE = 'Дата рождения должна быть меньше текущей даты';
  const EXISTS_BOOK_ERROR_MESSAGE = 'Нельзя удалить данного автора, так как у него в системе есть книги.';
}
