<?php
namespace App\Entity;

use App\Repository\LogEntryRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpKernel\Event\RequestEvent;

/**
 * @ORM\Entity(repositoryClass=LogEntryRepository::class)
 */
class LogEntry implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $uri;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $headers = [];

    /**
     * @ORM\Column(type="text")
     */
    private $bodyContent;

    /**
     * @ORM\Column(type="text")
     */
    private $requestContent;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $user;

    /**
     * @ORM\Column(name="created_at", type="datetimetz", nullable=false)
     */
    private $createdAt;

    public function __construct(RequestEvent $event)
    {
        $this->setUri($event->getRequest()->getUri());
        $this->setHeaders($event->getRequest()->headers->all());
        $this->setBodyContent($event->getRequest()->getContent());
        $this->setRequestContent($event->getRequest()->getContent());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUri(): ?string
    {
        return $this->uri;
    }

    public function setUri(string $uri): self
    {
        $this->uri = $uri;

        return $this;
    }

    public function getHeaders(): ?array
    {
        return $this->headers;
    }

    public function setHeaders(?array $headers): self
    {
        $this->headers = $headers;

        return $this;
    }

    public function getBodyContent(): ?string
    {
        return $this->bodyContent;
    }

    public function setBodyContent(string $bodyContent): self
    {
        $this->bodyContent = $bodyContent;

        return $this;
    }

    public function getRequestContent(): ?string
    {
        return $this->requestContent;
    }

    public function setRequestContent(string $requestContent): self
    {
        $this->requestContent = $requestContent;

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

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'uri' => $this->getUri(),
            'requestContent' => $this->getRequestContent(),
            'bodyContent' => $this->getBodyContent(),
            'userId' => $this->getUser() ? $this->getUser()->getId() : null
        ];
    }
}
