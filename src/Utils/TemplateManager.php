<?php declare(strict_types=1);

namespace App\Utils;

use App\Entity\Customer;

final class TemplateManager
{
    /**
     * Method signature can't be changed !
     */
    public function getTemplateComputed(Template $tpl, array $data)
    {
        if (!$tpl) {
            throw new \RuntimeException('no tpl given');
        }

        $replaced = clone($tpl);
        $replaced->subject = $this->computeText($replaced->subject, $data);
        $replaced->content = $this->computeText($replaced->content, $data);

        return $replaced;
    }

    private function computeText($text, array $data)
    {
        if(strpos($text, '[link:my-account]') !== false){
            $urlLink = getenv('URL_LINK_MY_ACCOUNT');
        }


        if ($data['customer'] && $data['customer'] instanceof Customer) {
            /** @var Customer $customer */
            $customer = $data['customer'];
            $containsFirstname = strpos($text, '[customer:first_name]');
            $containsLasttname = strpos($text, '[customer:last_name]');

            if ($containsFirstname !== false || $containsLasttname !== false) {
                if ($containsFirstname !== false) {
                    $text = str_replace(
                        '[customer:first_name]',
                        $customer->getFirstName(),
                        $text
                    );
                }
                if ($containsLasttname !== false) {
                    $text = str_replace(
                        '[customer:last_name]',
                        $customer->getLastName(),
                        $text
                    );
                }
            }

            (strpos($text, '[customer:gender]') !== false) and $text = str_replace('[customer:gender]',$customer->getGender(),$text);


            if (isset($urlLink)) {
                $text = str_replace('[link:my-account]', $urlLink . '/' . $customer->getId(), $text);
            } else
                $text = str_replace('[link:my-account]', '', $text);
        }

        return $text;
    }
}
