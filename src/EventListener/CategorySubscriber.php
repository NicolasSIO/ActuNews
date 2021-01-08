<?php


namespace App\EventListener;


use App\Entity\Category;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategorySubscriber implements EventSubscriber
{

    /**
     * @var SluggerInterface
     */
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    /**
     * @inheritDoc
     */
    public function getSubscribedEvents()
    {
        return [
            Events::prePersist
        ];
    }

    public function prePersist(LifecycleEventArgs $args){
        $this->genereateSlug($args);
    }

    private function genereateSlug(LifecycleEventArgs $args)
    {
        # 1. Récupération de l'Objet concerné
        $entity = $args->getObject();

        # 2. Si mon objet n'est pas une instance de "User" on quitte.
        if (!$entity instanceof Category) {
            return;
        }

        $entity->setAlias(
            $this->slugger->slug(
                $entity->getTitle()
            )
        );
    }


}