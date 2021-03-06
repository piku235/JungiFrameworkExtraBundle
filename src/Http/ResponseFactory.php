<?php

namespace Jungi\FrameworkExtraBundle\Http;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Piotr Kugla <piku235@gmail.com>
 *
 * @final
 * @internal
 */
class ResponseFactory
{
    private const MEDIA_TYPE_ALL = '*/*';

    private $defaultContentType;
    private $messageBodyMapperManager;

    public function __construct(string $defaultContentType, MessageBodyMapperManager $messageBodyMapperManager)
    {
        $defaultContentType = MediaTypeDescriptor::parse($defaultContentType);
        if (!$defaultContentType->isSpecific()) {
            throw new \InvalidArgumentException(sprintf('Default content type must be specific eg. application/json, got "%s".', $defaultContentType->toString()));
        }

        $this->defaultContentType = $defaultContentType;
        $this->messageBodyMapperManager = $messageBodyMapperManager;
    }

    /**
     * @param mixed $entity
     *
     * @throws NotAcceptableMediaTypeException
     * @throws \LogicException
     */
    public function createEntityResponse(Request $request, $entity, int $status = 200, array $headers = []): Response
    {
        $acceptableMediaTypes = $this->resolveAcceptableMediaTypes($request);
        $supportedMediaTypes = MediaTypeDescriptor::parseList($this->messageBodyMapperManager->getSupportedMediaTypes());

        if (!$supportedMediaTypes) {
            throw new \LogicException('You need to register at least one message body mapper for an entity response. For a JSON content type, you can use the built-in message body mapper by running "composer require symfony/serializer".');
        }

        $contentType = $this->selectResponseContentType($acceptableMediaTypes, $supportedMediaTypes);
        if (!$contentType) {
            throw new NotAcceptableMediaTypeException(MediaTypeDescriptor::listToString($acceptableMediaTypes), MediaTypeDescriptor::listToString($supportedMediaTypes), 'Could not select any content type for response.');
        }

        $headers['Content-Type'] = $contentType->toString();

        return new Response(
            $this->messageBodyMapperManager->mapTo($entity, $contentType->toString()),
            $status,
            $headers
        );
    }

    /**
     * @param MediaTypeDescriptor[] $acceptableMediaTypes
     * @param MediaTypeDescriptor[] $supportedMediaTypes
     */
    private function selectResponseContentType(array $acceptableMediaTypes, array $supportedMediaTypes): ?MediaTypeDescriptor
    {
        foreach ($acceptableMediaTypes as $acceptableMediaTypeDescriptor) {
            foreach ($supportedMediaTypes as $supportedMediaTypeDescriptor) {
                if ($acceptableMediaTypeDescriptor->inRange($supportedMediaTypeDescriptor)) {
                    return $supportedMediaTypeDescriptor;
                }
            }
        }

        return null;
    }

    /**
     * Content negotiation.
     *
     * a. request format
     * b. Accept header
     * c. otherwise default content type
     *
     * @return MediaTypeDescriptor[]
     */
    private function resolveAcceptableMediaTypes(Request $request): array
    {
        $format = $request->getRequestFormat(null);
        $mediaType = null !== $format ? $request->getMimeType($format) : null;

        if (null !== $format && null !== $mediaType && null !== $descriptor = MediaTypeDescriptor::parseOrNull($mediaType)) {
            return [$descriptor];
        }

        if ($acceptableContentTypes = $request->getAcceptableContentTypes()) {
            // acceptable content types are already sorted
            $descriptors = [];
            foreach ($acceptableContentTypes as $contentType) {
                // [ignored] Accept: */*
                if (self::MEDIA_TYPE_ALL !== $contentType && null !== $descriptor = MediaTypeDescriptor::parseOrNull($contentType)) {
                    $descriptors[] = $descriptor;
                }
            }

            if ($descriptors) {
                return $descriptors;
            }
        }

        return [$this->defaultContentType];
    }
}
