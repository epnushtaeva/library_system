<?php


namespace App\Constants;


interface MailConstants
{
   const BOOK_MAIL_THEME = 'Напоминание о забронированной книге';
   const BOOK_MAIL_TEXT = 'Здравствуйте, напоминаем вам, что вы забронировали книги: {bookNames}. Срок бронирования истекает через {daysCount} дней, пожалуйста, заберите книги в библиотеке, или бронь будет снята.';
}
