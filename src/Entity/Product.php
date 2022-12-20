<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;



#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message: 'Veuillez remplir ce champ')]
    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[Assert\NotBlank(message: 'Veuillez remplir ce champ')]
    #[ORM\Column(length: 255)]
    private ?string $category = null;

    #[Assert\NotBlank(message: 'Veuillez remplir ce champ')]
    #[ORM\Column(length: 255)]
    private ?string $photo = null;

    #[Assert\NotBlank(message: 'Veuillez remplir ce champ')]
    #[ORM\Column]
    private ?int $prix = null;

    public $editPhoto;

    #[Assert\NotBlank(message: 'Veuillez remplir ce champ')]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;


    #[ORM\ManyToOne(inversedBy: 'product')]
    private ?User $user = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateDebut = null;
    #[Assert\NotBlank(message: 'Veuillez remplir ce champ')]

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateFin = null;
    #[Assert\NotBlank(message: 'Veuillez remplir ce champ')]

    #[ORM\Column(length: 255)]
    private ?string $ville = null;
    #[Assert\NotBlank(message: 'Veuillez remplir ce champ')]

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Avis::class)]
    private Collection $avis;

    #[ORM\OneToMany(mappedBy: 'produit', targetEntity: Achat::class)]
    private Collection $achats;

    #[ORM\Column]
    private ?int $participant = null;

    public function __construct()
    {
        $this->avis = new ArrayCollection();
        $this->achats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }



    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }



    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }



    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * @return Collection<int, Avis>
     */
    public function getAvis(): Collection
    {
        return $this->avis;
    }

    public function addAvi(Avis $avi): self
    {
        if (!$this->avis->contains($avi)) {
            $this->avis->add($avi);
            $avi->setProduct($this);
        }

        return $this;
    }

    public function removeAvi(Avis $avi): self
    {
        if ($this->avis->removeElement($avi)) {
            // set the owning side to null (unless already changed)
            if ($avi->getProduct() === $this) {
                $avi->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Achat>
     */
    public function getAchats(): Collection
    {
        return $this->achats;
    }

    public function addAchat(Achat $achat): self
    {
        if (!$this->achats->contains($achat)) {
            $this->achats->add($achat);
            $achat->setProduit($this);
        }

        return $this;
    }

    public function removeAchat(Achat $achat): self
    {
        if ($this->achats->removeElement($achat)) {
            // set the owning side to null (unless already changed)
            if ($achat->getProduit() === $this) {
                $achat->setProduit(null);
            }
        }

        return $this;
    }

    public function getParticipant(): ?int
    {
        return $this->participant;
    }

    public function setParticipant(int $participant): self
    {
        $this->participant = $participant;

        return $this;
    }
}
