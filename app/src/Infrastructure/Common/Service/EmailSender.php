<?php

declare(strict_types=1);

namespace App\Infrastructure\Common\Service;

use App\Domain\Common\Service\SenderInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class EmailSender implements SenderInterface
{
    /**
     * @var MailerInterface
     */
    private MailerInterface $mailer;

    /**
     * @var string
     */
    private string $from;

    /**
     * @var Environment
     */
    private Environment $twig;

    public function __construct(MailerInterface $mailer, string $from, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->from = $from;
        $this->twig = $twig;
    }

    /**
     * @param string $template
     * @param array $data
     * @return string
     */
    public function generateBody(string $template, array $data): string
    {
        return $this->twig->render($template, $data);
    }

    /**
     * @param string $to
     * @param string $subject
     * @param string $body
     */
    public function send(string $to, string $subject, string $body): void
    {
        $email = (new Email())
            ->from($this->from)
            ->to($to)
            ->subject($subject)
            ->html($body);

        $this->mailer->send($email);
    }
}
