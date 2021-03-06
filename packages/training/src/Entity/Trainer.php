<?php

declare(strict_types=1);

namespace Pehapkari\Training\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;
use Pehapkari\BetterEasyAdmin\Entity\UploadableImageTrait;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity
 * @Vich\Uploadable
 */
class Trainer implements TimestampableInterface
{
    use UploadableImageTrait;
    use TimestampableTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $email = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $phone = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $position = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $company = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $website = null;

    /**
     * @ORM\Column(type="text")
     */
    private ?string $bio = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $twitterName = null;

    /**
     * @ORM\OneToMany(targetEntity="Pehapkari\Training\Entity\Training", mappedBy="trainer")
     * @var Collection&Trainer[]
     */
    private Collection $trainings;

    public function __construct()
    {
        $this->trainings = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function getTwitterName(): ?string
    {
        return $this->twitterName;
    }

    public function setTwitterName(?string $twitterName): void
    {
        $this->twitterName = $twitterName;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @param Training[]&Collection $collection
     */
    public function setTrainings(Collection $collection): void
    {
        $this->trainings = $collection;
    }

    /**
     * @return Collection&Training[]
     */
    public function getTrainings(): Collection
    {
        return $this->trainings;
    }

    public function getTrainingTermCount(): int
    {
        $trainingTermCount = 0;

        foreach ($this->getTrainings() as $training) {
            foreach ($training->getTrainingTerms() as $trainingTerm) {
                if (! $trainingTerm->isProvisionPaid()) {
                    continue;
                }

                ++$trainingTermCount;
            }
        }

        return $trainingTermCount;
    }

    public function getTrainingsParticipantCount(): int
    {
        $trainingsParticipantCount = 0;

        foreach ($this->getTrainings() as $training) {
            foreach ($training->getTrainingTerms() as $trainingTerm) {
                if (! $trainingTerm->isProvisionPaid()) {
                    continue;
                }

                $trainingsParticipantCount += $trainingTerm->getParticipantCount();
            }
        }

        return $trainingsParticipantCount;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(string $website): void
    {
        $this->website = $website;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(string $bio): void
    {
        $this->bio = $bio;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(string $position): void
    {
        $this->position = $position;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(string $company): void
    {
        $this->company = $company;
    }
}
