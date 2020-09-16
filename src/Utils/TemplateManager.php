<?php

declare(strict_types=1);

namespace App\Utils;

use App\Entity\Customer;

final class TemplateManager
{
    private string $urlLinkMyAccount;

    public function __construct(string $urlLinkMyAccount)
    {
        $this->urlLinkMyAccount = $urlLinkMyAccount;
    }

    /**
     * Method signature can't be changed !
     */
    public function getTemplateComputed(Template $tpl, array $data)
    {
        if (!$tpl) {
            throw new \RuntimeException('no tpl given');
        }

        $replaced = clone $tpl;
        $replaced->subject = $this->computeText($replaced->subject, $data['customer'] ?? null);
        $replaced->content = $this->computeText($replaced->content, $data['customer'] ?? null);

        return $replaced;
    }

    private function computeText($text, Customer $customer = null)
    {
        if (null === $customer) {
            return $text;
        }

        if (false !== strpos($text, '[customer:first_name]')) {
            $text = str_replace('[customer:first_name]', $customer->getFirstName(), $text);
        }
        if (false !== strpos($text, '[customer:last_name]')) {
            $text = str_replace('[customer:last_name]', $customer->getLastName(), $text);
        }

        if (false !== strpos($text, '[customer:gender]')) {
            $text = str_replace('[customer:gender]', $customer->getGender(), $text);
        }

        if (false !== strpos($text, '[link:my-account]')) {
            $text = str_replace('[link:my-account]', $this->urlLinkMyAccount.'/'.$customer->getId(), $text);
        }

        return $text;
    }
}
