<?php
namespace App\Controller;

use App\Model\Home;
use App\Model\Repository\HomeRepository;
use App\Model\Repository\UsersRepository;
use App\Model\User;

require_once '../vendor/autoload.php';

/**
 * Cette classe permet d'afficher la page d'accueil
 *
 * @author marion
 */
class HomeController extends AbstractController {
    private function getHomeModel() {
        return new Home(NULL);
    }

    private function getHomeRepository() {
        return new HomeRepository();
    }

    public function goHome() {
        $aTwig = [
            'home' => new Home($this->getHomeRepository()->getHomeData())
        ];
        include '../config/includeTwig.php';
        echo $twig->render('/frontend/homeView.twig', $aTwig);
    }

    public function getHomeAdmin() {
        $aTwig = [
            'home' => new Home($this->getHomeRepository()->getHomeData()),
            'collapse' => 'show'
        ];

        include '../config/includeTwig.php';
        echo $twig->render('/backend/admin/homeManager.twig', $aTwig);
    }

    public function updateHomeAdmin() {
        $values = [];
        foreach ($this->request()->post as $key => $value) {
            $values [$key] = $value;
        }
        $this->getHomeRepository()->updateHomeData($values);
        $this->getHomeAdmin();
    }

    public function sendEmail(){
        $transport = (new \Swift_SmtpTransport('smtp.gmail.com', 465))
//            ->setUsername('mlancien1@gmail.com')
//            ->setPassword('Marion84')
        ;

        // Create the Mailer using your created Transport
        $mailer = new \Swift_Mailer($transport);

        // Create a message
        $message = (new \Swift_Message('Wonderful Subject'))
            ->setFrom(['mlancien1@gmail.com' => 'Marion Lancien'])
            ->setTo(['lancien.marion@hotmail.com' => 'Recipient'])
            ->setBody('Here is the message itself')
        ;

        // Send the message
        $result = $mailer->send($message);

        header('Location: /');
    }


}