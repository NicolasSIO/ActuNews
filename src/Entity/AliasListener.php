<?php


namespace App\Entity;


use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class AliasListener
{
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function prePersistPost(Post $post, LifecycleEventArgs $event)
    {
        $post->computeSlug($this->slugger);
    }

    public function preUpdatePost(Post $post, LifecycleEventArgs $event)
    {
        $post->computeSlug($this->slugger);
    }

    public function prePersistCategory(Category $category, LifecycleEventArgs $event)
    {
        $category->computeSlug($this->slugger);
    }

    public function preUpdateCategory(Category $category, LifecycleEventArgs $event)
    {
        $category->computeSlug($this->slugger);
    }
}