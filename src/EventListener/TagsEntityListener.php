<?php


namespace App\EventListener;


use App\Entity\Tags;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class TagsEntityListener
{
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function prePersist(Tags $tags, LifecycleEventArgs $event)
    {
        $tags->computeSlug($this->slugger);
    }

    public function preUpdate(Tags $tags, LifecycleEventArgs $event)
    {
        $tags->computeSlug($this->slugger);
    }
}