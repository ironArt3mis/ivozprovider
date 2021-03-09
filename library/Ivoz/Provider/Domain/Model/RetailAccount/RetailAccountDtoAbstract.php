<?php

namespace Ivoz\Provider\Domain\Model\RetailAccount;

use Ivoz\Core\Application\DataTransferObjectInterface;
use Ivoz\Core\Application\Model\DtoNormalizer;
use Ivoz\Provider\Domain\Model\Brand\BrandDto;
use Ivoz\Provider\Domain\Model\Domain\DomainDto;
use Ivoz\Provider\Domain\Model\Company\CompanyDto;
use Ivoz\Provider\Domain\Model\TransformationRuleSet\TransformationRuleSetDto;
use Ivoz\Provider\Domain\Model\Ddi\DdiDto;
use Ivoz\Ast\Domain\Model\PsEndpoint\PsEndpointDto;
use Ivoz\Provider\Domain\Model\CallForwardSetting\CallForwardSettingDto;

/**
* RetailAccountDtoAbstract
* @codeCoverageIgnore
*/
abstract class RetailAccountDtoAbstract implements DataTransferObjectInterface
{
    use DtoNormalizer;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description = '';

    /**
     * @var string | null
     */
    private $transport;

    /**
     * @var string | null
     */
    private $ip;

    /**
     * @var int | null
     */
    private $port;

    /**
     * @var string | null
     */
    private $password;

    /**
     * @var string | null
     */
    private $fromDomain;

    /**
     * @var string
     */
    private $directConnectivity = 'yes';

    /**
     * @var string
     */
    private $ddiIn = 'yes';

    /**
     * @var string
     */
    private $t38Passthrough = 'no';

    /**
     * @var bool
     */
    private $rtpEncryption = false;

    /**
     * @var bool
     */
    private $multiContact = true;

    /**
     * @var int
     */
    private $id;

    /**
     * @var BrandDto | null
     */
    private $brand;

    /**
     * @var DomainDto | null
     */
    private $domain;

    /**
     * @var CompanyDto | null
     */
    private $company;

    /**
     * @var TransformationRuleSetDto | null
     */
    private $transformationRuleSet;

    /**
     * @var DdiDto | null
     */
    private $outgoingDdi;

    /**
     * @var PsEndpointDto[] | null
     */
    private $psEndpoints;

    /**
     * @var DdiDto[] | null
     */
    private $ddis;

    /**
     * @var CallForwardSettingDto[] | null
     */
    private $callForwardSettings;

    public function __construct($id = null)
    {
        $this->setId($id);
    }

    /**
    * @inheritdoc
    */
    public static function getPropertyMap(string $context = '', string $role = null)
    {
        if ($context === self::CONTEXT_COLLECTION) {
            return ['id' => 'id'];
        }

        return [
            'name' => 'name',
            'description' => 'description',
            'transport' => 'transport',
            'ip' => 'ip',
            'port' => 'port',
            'password' => 'password',
            'fromDomain' => 'fromDomain',
            'directConnectivity' => 'directConnectivity',
            'ddiIn' => 'ddiIn',
            't38Passthrough' => 't38Passthrough',
            'rtpEncryption' => 'rtpEncryption',
            'multiContact' => 'multiContact',
            'id' => 'id',
            'brandId' => 'brand',
            'domainId' => 'domain',
            'companyId' => 'company',
            'transformationRuleSetId' => 'transformationRuleSet',
            'outgoingDdiId' => 'outgoingDdi'
        ];
    }

    /**
    * @return array
    */
    public function toArray($hideSensitiveData = false)
    {
        $response = [
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'transport' => $this->getTransport(),
            'ip' => $this->getIp(),
            'port' => $this->getPort(),
            'password' => $this->getPassword(),
            'fromDomain' => $this->getFromDomain(),
            'directConnectivity' => $this->getDirectConnectivity(),
            'ddiIn' => $this->getDdiIn(),
            't38Passthrough' => $this->getT38Passthrough(),
            'rtpEncryption' => $this->getRtpEncryption(),
            'multiContact' => $this->getMultiContact(),
            'id' => $this->getId(),
            'brand' => $this->getBrand(),
            'domain' => $this->getDomain(),
            'company' => $this->getCompany(),
            'transformationRuleSet' => $this->getTransformationRuleSet(),
            'outgoingDdi' => $this->getOutgoingDdi(),
            'psEndpoints' => $this->getPsEndpoints(),
            'ddis' => $this->getDdis(),
            'callForwardSettings' => $this->getCallForwardSettings()
        ];

        if (!$hideSensitiveData) {
            return $response;
        }

        foreach ($this->sensitiveFields as $sensitiveField) {
            if (!array_key_exists($sensitiveField, $response)) {
                throw new \Exception($sensitiveField . ' field was not found');
            }
            $response[$sensitiveField] = '*****';
        }

        return $response;
    }

    /**
     * @param string $name | null
     *
     * @return static
     */
    public function setName(?string $name = null): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string | null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $description | null
     *
     * @return static
     */
    public function setDescription(?string $description = null): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string | null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $transport | null
     *
     * @return static
     */
    public function setTransport(?string $transport = null): self
    {
        $this->transport = $transport;

        return $this;
    }

    /**
     * @return string | null
     */
    public function getTransport(): ?string
    {
        return $this->transport;
    }

    /**
     * @param string $ip | null
     *
     * @return static
     */
    public function setIp(?string $ip = null): self
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * @return string | null
     */
    public function getIp(): ?string
    {
        return $this->ip;
    }

    /**
     * @param int $port | null
     *
     * @return static
     */
    public function setPort(?int $port = null): self
    {
        $this->port = $port;

        return $this;
    }

    /**
     * @return int | null
     */
    public function getPort(): ?int
    {
        return $this->port;
    }

    /**
     * @param string $password | null
     *
     * @return static
     */
    public function setPassword(?string $password = null): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string | null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $fromDomain | null
     *
     * @return static
     */
    public function setFromDomain(?string $fromDomain = null): self
    {
        $this->fromDomain = $fromDomain;

        return $this;
    }

    /**
     * @return string | null
     */
    public function getFromDomain(): ?string
    {
        return $this->fromDomain;
    }

    /**
     * @param string $directConnectivity | null
     *
     * @return static
     */
    public function setDirectConnectivity(?string $directConnectivity = null): self
    {
        $this->directConnectivity = $directConnectivity;

        return $this;
    }

    /**
     * @return string | null
     */
    public function getDirectConnectivity(): ?string
    {
        return $this->directConnectivity;
    }

    /**
     * @param string $ddiIn | null
     *
     * @return static
     */
    public function setDdiIn(?string $ddiIn = null): self
    {
        $this->ddiIn = $ddiIn;

        return $this;
    }

    /**
     * @return string | null
     */
    public function getDdiIn(): ?string
    {
        return $this->ddiIn;
    }

    /**
     * @param string $t38Passthrough | null
     *
     * @return static
     */
    public function setT38Passthrough(?string $t38Passthrough = null): self
    {
        $this->t38Passthrough = $t38Passthrough;

        return $this;
    }

    /**
     * @return string | null
     */
    public function getT38Passthrough(): ?string
    {
        return $this->t38Passthrough;
    }

    /**
     * @param bool $rtpEncryption | null
     *
     * @return static
     */
    public function setRtpEncryption(?bool $rtpEncryption = null): self
    {
        $this->rtpEncryption = $rtpEncryption;

        return $this;
    }

    /**
     * @return bool | null
     */
    public function getRtpEncryption(): ?bool
    {
        return $this->rtpEncryption;
    }

    /**
     * @param bool $multiContact | null
     *
     * @return static
     */
    public function setMultiContact(?bool $multiContact = null): self
    {
        $this->multiContact = $multiContact;

        return $this;
    }

    /**
     * @return bool | null
     */
    public function getMultiContact(): ?bool
    {
        return $this->multiContact;
    }

    /**
     * @param int $id | null
     *
     * @return static
     */
    public function setId(?int $id = null): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int | null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param BrandDto | null
     *
     * @return static
     */
    public function setBrand(?BrandDto $brand = null): self
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * @return BrandDto | null
     */
    public function getBrand(): ?BrandDto
    {
        return $this->brand;
    }

    /**
     * @return static
     */
    public function setBrandId($id): self
    {
        $value = !is_null($id)
            ? new BrandDto($id)
            : null;

        return $this->setBrand($value);
    }

    /**
     * @return mixed | null
     */
    public function getBrandId()
    {
        if ($dto = $this->getBrand()) {
            return $dto->getId();
        }

        return null;
    }

    /**
     * @param DomainDto | null
     *
     * @return static
     */
    public function setDomain(?DomainDto $domain = null): self
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * @return DomainDto | null
     */
    public function getDomain(): ?DomainDto
    {
        return $this->domain;
    }

    /**
     * @return static
     */
    public function setDomainId($id): self
    {
        $value = !is_null($id)
            ? new DomainDto($id)
            : null;

        return $this->setDomain($value);
    }

    /**
     * @return mixed | null
     */
    public function getDomainId()
    {
        if ($dto = $this->getDomain()) {
            return $dto->getId();
        }

        return null;
    }

    /**
     * @param CompanyDto | null
     *
     * @return static
     */
    public function setCompany(?CompanyDto $company = null): self
    {
        $this->company = $company;

        return $this;
    }

    /**
     * @return CompanyDto | null
     */
    public function getCompany(): ?CompanyDto
    {
        return $this->company;
    }

    /**
     * @return static
     */
    public function setCompanyId($id): self
    {
        $value = !is_null($id)
            ? new CompanyDto($id)
            : null;

        return $this->setCompany($value);
    }

    /**
     * @return mixed | null
     */
    public function getCompanyId()
    {
        if ($dto = $this->getCompany()) {
            return $dto->getId();
        }

        return null;
    }

    /**
     * @param TransformationRuleSetDto | null
     *
     * @return static
     */
    public function setTransformationRuleSet(?TransformationRuleSetDto $transformationRuleSet = null): self
    {
        $this->transformationRuleSet = $transformationRuleSet;

        return $this;
    }

    /**
     * @return TransformationRuleSetDto | null
     */
    public function getTransformationRuleSet(): ?TransformationRuleSetDto
    {
        return $this->transformationRuleSet;
    }

    /**
     * @return static
     */
    public function setTransformationRuleSetId($id): self
    {
        $value = !is_null($id)
            ? new TransformationRuleSetDto($id)
            : null;

        return $this->setTransformationRuleSet($value);
    }

    /**
     * @return mixed | null
     */
    public function getTransformationRuleSetId()
    {
        if ($dto = $this->getTransformationRuleSet()) {
            return $dto->getId();
        }

        return null;
    }

    /**
     * @param DdiDto | null
     *
     * @return static
     */
    public function setOutgoingDdi(?DdiDto $outgoingDdi = null): self
    {
        $this->outgoingDdi = $outgoingDdi;

        return $this;
    }

    /**
     * @return DdiDto | null
     */
    public function getOutgoingDdi(): ?DdiDto
    {
        return $this->outgoingDdi;
    }

    /**
     * @return static
     */
    public function setOutgoingDdiId($id): self
    {
        $value = !is_null($id)
            ? new DdiDto($id)
            : null;

        return $this->setOutgoingDdi($value);
    }

    /**
     * @return mixed | null
     */
    public function getOutgoingDdiId()
    {
        if ($dto = $this->getOutgoingDdi()) {
            return $dto->getId();
        }

        return null;
    }

    /**
     * @param PsEndpointDto[] | null
     *
     * @return static
     */
    public function setPsEndpoints(?array $psEndpoints = null): self
    {
        $this->psEndpoints = $psEndpoints;

        return $this;
    }

    /**
     * @return PsEndpointDto[] | null
     */
    public function getPsEndpoints(): ?array
    {
        return $this->psEndpoints;
    }

    /**
     * @param DdiDto[] | null
     *
     * @return static
     */
    public function setDdis(?array $ddis = null): self
    {
        $this->ddis = $ddis;

        return $this;
    }

    /**
     * @return DdiDto[] | null
     */
    public function getDdis(): ?array
    {
        return $this->ddis;
    }

    /**
     * @param CallForwardSettingDto[] | null
     *
     * @return static
     */
    public function setCallForwardSettings(?array $callForwardSettings = null): self
    {
        $this->callForwardSettings = $callForwardSettings;

        return $this;
    }

    /**
     * @return CallForwardSettingDto[] | null
     */
    public function getCallForwardSettings(): ?array
    {
        return $this->callForwardSettings;
    }

}
