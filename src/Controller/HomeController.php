<?php

namespace App\Controller;

use App\Model\Home;
use App\Model\Repository\HomeRepository;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

/**
 * Cette classe permet d'afficher la page d'accueil
 *
 * @author marion
 */
class HomeController extends AbstractController
{
    private function getHomeModel()
    {
        return new Home(NULL);
    }

    private function getHomeRepository()
    {
        return new HomeRepository();
    }

    public function goHome()
    {
        $aTwig = $this->getATwig([
            'home' => new Home($this->getHomeRepository()->getHomeData())
        ]);
        return $this->twig()->render('/frontend/homeView.twig', $aTwig);
    }

    public function getHomeAdmin()
    {
        if ($this->getIdConnect()) {
            $aTwig = $this->getATwig([
                'home' => new Home($this->getHomeRepository()->getHomeData()),
                'collapse' => 'show'
            ]);

            return $this->twig()->render('/backend/admin/homeManager.twig', $aTwig);
        }
            header('Location: /connect/login');

    }

    public function updateHomeAdmin()
    {
        if ($this->getIdConnect()) {
        $values = [];
        foreach ($this->request()->post as $key => $value) {
            $values [$key] = $value;
        }
        $this->getHomeRepository()->updateHomeData($values);
        return $this->getHomeAdmin();
        } else {
            header('Location: /connect/login');
        }

    }

    public function sendEmail()
    {
        $body = $this->request()->post['content'];
        $from = $this->request()->post['email'];
        $nameEmail = 'Message de ' . $this->request()->post['fname'] . ' ' . $this->request()->post['lname'];
        $subject = $this->request()->post['subject'] ?: 'J\'ai un nouveau message !';

        $transport = (new Swift_SmtpTransport('smtp.googlemail.com', 465, 'ssl'))
            ->setUsername($this->getHomeRepository()->getEmailLogin()['username'])
            ->setPassword($this->getHomeRepository()->getEmailLogin()['password']);

        $mailer = new Swift_Mailer($transport);

        $message = (new Swift_Message($subject))
            ->setFrom([(new Home($this->getHomeRepository()->getHomeData()))->getEmail() => 'Moi'])
            ->setTo([$from => $nameEmail])
            ->setBody($body);

        $mailer->send($message);

        header('Location: /');
    }


}