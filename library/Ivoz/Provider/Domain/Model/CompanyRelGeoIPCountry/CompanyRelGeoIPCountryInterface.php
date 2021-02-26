<?php

namespace Ivoz\Provider\Domain\Model\CompanyRelGeoIPCountry;

use Ivoz\Provider\Domain\Model\Company\CompanyInterface;
use Ivoz\Provider\Domain\Model\Country\CountryInterface;
use Ivoz\Core\Domain\Model\LoggableEntityInterface;

/**
* CompanyRelGeoIPCountryInterface
*/
interface CompanyRelGeoIPCountryInterface extends LoggableEntityInterface
{
    /**
     * @codeCoverageIgnore
     * @return array
     */
    public function getChangeSet();

    /**
     * Set company
     *
     * @param CompanyInterface | null
     *
     * @return static
     */
    public function setCompany(?CompanyInterface $company = null): CompanyRelGeoIPCountryInterface;

    /**
     * Get company
     *
     * @return CompanyInterface | null
     */
    public function getCompany(): ?CompanyInterface;

    /**
     * Get country
     *
     * @return CountryInterface
     */
    public function getCountry(): CountryInterface;

    /**
     * @return bool
     */
    public function isInitialized(): bool;

}
