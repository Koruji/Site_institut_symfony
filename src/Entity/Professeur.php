<?php

namespace App\Entity;

use App\Repository\ProfesseurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;

#[ORM\Entity(repositoryClass: ProfesseurRepository::class)]
class Professeur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 6, unique: true)]
    private ?string $matricule = null;

    #[ORM\Column()]
    #[Length(min: 2, max: 20, minMessage: "Il faut plus de 2 caractères.", maxMessage: "Pas plus de 20 caractères")]
    private ?string $nom = null;

    #[ORM\Column()]
    #[Length(min: 2, max: 20, minMessage: "Il faut plus de 2 caractères.", maxMessage: "Pas plus de 20 caractères")]
    private ?string $prenom = null;

    #[ORM\Column(length: 60, unique: true)]
    #[Email]
    private ?string $email = null;

    #[ORM\ManyToOne(inversedBy: 'professeurs')]
    private ?Matiere $matiere = null;

    /**
     * @var Collection<int, Stage>
     */
    #[ORM\ManyToMany(targetEntity: Stage::class, mappedBy: 'professeurs')]
    private Collection $stages;

    public function __construct()
    {
        $this->stages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(string $matricule): static
    {
        $this->matricule = $matricule;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getMatiere(): ?Matiere
    {
        return $this->matiere;
    }

    public function setMatiere(?Matiere $matiere): static
    {
        $this->matiere = $matiere;

        return $this;
    }

    /**
     * @return Collection<int, Stage>
     */
    public function getStages(): Collection
    {
        return $this->stages;
    }

    public function addStage(Stage $stage): self
    {
        if (!$this->stages->contains($stage)) {
            $this->stages->add($stage);
            $stage->addProfesseur($this);
        }

        return $this;
    }

    public function removeStage(Stage $stage): self
    {
        if ($this->stages->removeElement($stage)) {
            $stage->removeProfesseur($this);
        }

        return $this;
    }

}
