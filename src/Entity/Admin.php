<?php

namespace App\Entity;

use App\Repository\AdminRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdminRepository::class)]
class Admin extends Membre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public ?int $id = null;

   // #[ORM\OneToMany(mappedBy: 'admin', targetEntity: Post::class)]
    //private Collection $posts;

   /* public function __construct()
    {
        parent::__construct();
        $this->posts = new ArrayCollection();
    }*/

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Post>
     */
  /*  public function getPosts(): Collection
    {
        return $this->posts;
    }*/

  /*  public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->setAdmin($this);
        }

        return $this;
    }*/

   /* public function removePost(Post $post): self
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getAdmin() === $this) {
                $post->setAdmin(null);
            }
        }

        return $this;
    }
   */
}
