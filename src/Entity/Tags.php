<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TagsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TagsRepository::class)
 * @ApiResource()
 * @UniqueEntity("alias")
 */
class Tags
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     * @Assert\Length(max="150", maxMessage="Attention, pas plus de 150 caractères.")
     * @Assert\NotBlank(message="N'oubliez pas le nom de la catégorie.")
     * @Groups({"post:read"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=150)
     * @Assert\Length(max="150", maxMessage="Attention, pas plus de 150 caractères.")
     * @Assert\NotBlank(message="N'oubliez pas le nom de la catégorie.")
     * @Groups({"post:read"})
     */
    private $alias;

    /**
     * @ORM\ManyToMany(targetEntity=Post::class, inversedBy="tags")
     */
    private $posts;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function setAlias(string $alias): self
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * @return Collection|Post[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        $this->posts->removeElement($post);

        return $this;
    }

    public function computeSlug(SluggerInterface $slugger)
    {
        if (!$this->alias || '-' === $this->alias) {
            $this->alias = (string) $slugger->slug((string) $this)->lower();
        }
    }
}
