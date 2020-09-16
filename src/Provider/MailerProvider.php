<?php declare(strict_types=1);

namespace App\Provider;

use App\Client\MailerInterface;
use App\Utils\Template;
use App\Utils\TemplateManager;

final class MailerProvider implements MailerProviderInterface
{
    private MailerInterface $client;

    public function __construct(MailerInterface $client)
    {
        $this->client = $client;
    }

    public function sendEmail(string $templateId, string $destination, array $data)
    {
        $templateManager = new TemplateManager();
        if ($templateId === 'confirmation_001') {
            $tpl = $templateManager->getTemplateComputed($this->confirmTemplate(), $data);
            $subject = $tpl->subject;
            $message = $tpl->content;
        } else {
            $subject = '';
            $message = '';
        }

        $this->client->sendEmail(
            $templateId,
            $destination,
            ['subject' => $subject, 'message' => $message]
        );
    }

    private function confirmTemplate(): Template
    {
        return new Template(
            1,
            '[customer:first_name] [customer:last_name] Votre compte a bien été crée',
            "
Bonjour [customer:first_name],

Merci d'avoir créé votre compte sur notre site vous pouvez maintenant accéder à votre espace en cliquant ici:
[link:my-account]

Bien cordialement,
");
    }
}
