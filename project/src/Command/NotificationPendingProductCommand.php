<?php

namespace App\Command;

use App\Enum\StatusEnum;
use App\Enum\StatusEnumType;
use App\Repository\ProductRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class NotificationPendingProductCommand extends Command
{
    protected static $defaultName = 'notif:pending-product';

    protected $productRepository;

    protected $mailer;

    public function __construct(ProductRepository $productRepository, MailerInterface $mailer)
    {
        parent::__construct();

        $this->productRepository = $productRepository;
        $this->mailer = $mailer;
    }

    protected function configure()
    {
        $this->setDescription('Look for products on “pending” status and expired');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $products = $this->productRepository->findByStatusAndCreatedAtLessWeek(StatusEnum::STATUS_PENDING, new \Datetime('-7 days'));
        $email = new TemplatedEmail();

        $email->to('you@epcvip.com');
        $email->from('me@epcvip.com');
        $email->subject('Products on pending status expired');
        $email->htmlTemplate('product_pending_expired.txt.twig');
        $email->context(['products' => $products]);

        $this->mailer->send($email);

        $io->success('Total products pending:' . count($products));

        return 1;
    }
}
