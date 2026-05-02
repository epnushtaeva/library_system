<?php


namespace App\Service;


use App\Constants\MailConstants;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailService
{

    public function __construct(
        private MailerInterface $mailer
    )
    {
    }

    public function sendBookEmail(string $emailTo, string $booksNames, int $daysCount)
    {
        $email = (new Email())
            ->from('concordia.com@mail.ru')
            ->to($emailTo)
            ->subject(MailConstants::BOOK_MAIL_THEME)
            ->html(str_replace('{daysCount}', $daysCount, str_replace('{bookNames}', $booksNames, MailConstants::BOOK_MAIL_TEXT)));

        $this->mailer->send($email);
    }
}
