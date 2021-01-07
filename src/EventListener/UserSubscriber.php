<?php


namespace App\EventListener;

use App\Entity\User;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserSubscriber implements EventSubscriber
{

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * @var MailerInterface
     */
    private $mailer;

    public function __construct(UserPasswordEncoderInterface $encoder, MailerInterface $mailer)
    {
        $this->encoder = $encoder;
        $this->mailer = $mailer;
    }

    /**
     * @inheritDoc
     */
    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
            Events::postPersist
        ];
    }

    /**
     * Cette fonction se déclenche juste avant l'insertion
     * d'un élément dans la BDD
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $this->encodePassword($args);
    }

    /**
     * Permet d'encoder un mot de passe dans la BDD
     * juste avant l'insertion d'un User.
     * @param LifecycleEventArgs $args
     */
    public function encodePassword(LifecycleEventArgs $args)
    {
        # 1. Récupération de l'Objet concerné
        $entity = $args->getObject();

        # 2. Si mon objet n'est pas une instance de "User" on quitte.
        if (!$entity instanceof User) {
            return;
        }

        # 3. Sinon, on encode le mot de passe
        $entity->setPassword(
            $this->encoder->encodePassword(
                $entity,
                $entity->getPassword()
            )
        );

    }

    /**
     * Cette fonction se déclenche juste après l'insertion
     * d'un élément dans la BDD
     * @param LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $this->sendWelcomeEmail($args);
    }

    public function sendWelcomeEmail(LifecycleEventArgs $args)
    {
        # 1. Récupération de l'Objet concerné
        $entity = $args->getObject();

        # 2. Si mon objet n'est pas une instance de "User" on quitte.
        if (!$entity instanceof User) {
            return;
        }

        $email = (new Email())
            ->from('noreply@actu.news')
            ->to($entity->getEmail())
            ->subject('Bienvenue sur notre site Actunews')
            ->html('<p>Bonjour, Bienvenue chez Actunews !</p>');

        $this->mailer->send($email);
    }
}