<?php declare(strict_types=1);

namespace App\Converter;

use ApiPlatform\Core\Api\IriConverterInterface;
use ApiPlatform\Core\Api\UrlGeneratorInterface;
use ApiPlatform\Core\Metadata\Resource\Factory\ResourceMetadataFactoryInterface;
use ApiPlatform\Core\Util\ClassInfoTrait;

/**
 * @codeCoverageIgnore
 * Test/ReadOnly
 */
final class IriConverter implements IriConverterInterface
{
    use ClassInfoTrait;

    private $decorated;
    private $resourceMetadataFactory;
    private $urlGenerator;

    public function __construct(
        ResourceMetadataFactoryInterface $resourceMetadataFactory,
        IriConverterInterface $decorated
    ) {
        $this->resourceMetadataFactory = $resourceMetadataFactory;
        $this->decorated = $decorated;
    }

    public function getItemFromIri(string $iri, array $context = [])
    {
        return $this->decorated->getItemFromIri($iri, $context);
    }

    public function getIriFromItem($item, int $referenceType = UrlGeneratorInterface::ABS_PATH): string
    {
        $resourceClass = $this->getObjectClass($item);
        $metadata = $this->resourceMetadataFactory->create($resourceClass);
        if (\is_string($metadata->getAttribute('iri'))) {
            return $metadata->getAttribute('iri');
        }
        return $this->decorated->getIriFromItem($item, $referenceType);
    }

    public function getIriFromResourceClass(string $resourceClass, int $referenceType = UrlGeneratorInterface::ABS_PATH): string
    {
        return $this->decorated->getIriFromResourceClass($resourceClass, $referenceType);
    }

    public function getItemIriFromResourceClass(string $resourceClass, array $identifiers, int $referenceType = UrlGeneratorInterface::ABS_PATH): string
    {
        return $this->decorated->getItemIriFromResourceClass($resourceClass, $identifiers, $referenceType);
    }

    public function getSubresourceIriFromResourceClass(string $resourceClass, array $context, int $referenceType = UrlGeneratorInterface::ABS_PATH): string
    {
        return $this->decorated->getSubresourceIriFromResourceClass($resourceClass, $context, $referenceType);
    }
}
