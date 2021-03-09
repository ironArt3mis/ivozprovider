<?php

namespace Controller\Provider;

use ApiPlatform\Core\Exception\ResourceClassNotFoundException;
use Ivoz\Core\Application\DataTransferObjectInterface;
use Ivoz\Provider\Application\Service\User\SyncFromCsv;
use Ivoz\Provider\Domain\Model\Administrator\AdministratorInterface;
use Ivoz\Provider\Domain\Model\Company\CompanyRepository;
use Model\UsersMassImport;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;

class PostUsersMassImportAction
{
    protected $tokenStorage;
    protected $serializer;
    protected $requestStack;
    protected $companyRepository;
    protected $syncFromCsv;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        SerializerInterface $serializer,
        RequestStack $requestStack,
        CompanyRepository $companyRepository,
        SyncFromCsv $syncFromCsv
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->serializer = $serializer;
        $this->requestStack = $requestStack;
        $this->companyRepository = $companyRepository;
        $this->syncFromCsv = $syncFromCsv;
    }

    public function __invoke()
    {
        $token = $this->tokenStorage->getToken();

        if (!$token || !$token->getUser()) {
            throw new ResourceClassNotFoundException('User not found');
        }

        /** @var AdministratorInterface $user */
        $user = $token->getUser();

        $brand = $user->getBrand();
        if (!$brand) {
            throw new ResourceClassNotFoundException('User brand not found');
        }

        $request = $this->requestStack->getCurrentRequest();
        $companyId = $request->request->get('company');
        $company = $this->companyRepository->find($companyId);
        if (!$company) {
            throw new ResourceClassNotFoundException('Company not found');
        }

        if ($company->getBrand() !== $brand) {
            throw new \DomainException(
                'Forbidden company',
                403
            );
        }

        $csv = file_get_contents(
            $request->files->get('csv')
        );

        $errorMsg = '';
        $rowsFailed = 0;
        try {
            $this->syncFromCsv->execute(
                $company,
                $csv
            );
        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            $rowsFailed = $e->getCode();
        }

        $success = $rowsFailed === 0;
        $response = new UsersMassImport(
            $success,
            $errorMsg,
            $rowsFailed
        );

        return $this->serializer->denormalize(
            [],
            UsersMassImport::class,
            $request->getRequestFormat(),
            [
                'object_to_populate' => $response,
                'operation_normalization_context' => DataTransferObjectInterface::CONTEXT_SIMPLE
            ]
        );
    }
}
