<?php

namespace App\Command;

use App\Service\BookService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:send_book_email',
    description: 'Send email about booking books',
)]
class SendBookEmailCommand extends Command
{
    public function __construct(
        private BookService $bookService
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
      $this->bookService->sendBookEmail();
      return Command::SUCCESS;
    }
}
