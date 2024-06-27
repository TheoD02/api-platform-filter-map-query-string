<?php

declare(strict_types=1);

namespace Theod02\ApiPlatformFilterMapQueryString\Transformer;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\RequestPayloadValueResolver;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class RequestToDTOTransformer
{
    public function __construct(
        private readonly HttpKernelInterface         $httpKernel,
        #[Autowire(service: 'argument_resolver.request_payload')]
        private readonly RequestPayloadValueResolver $requestPayloadValueResolver,
    )
    {
    }

    /**
     * @template T of class-string
     *
     * @param T $type
     *
     * @return object<T>
     */
    public function transformPayload(Request $request, string $type): object
    {
        $mapRequestPayload = new MapRequestPayload();
        $mapRequestPayload->metadata = new ArgumentMetadata('RequestPayloadMetadata', $type, false, false, false);
        $event = new ControllerArgumentsEvent(
            $this->httpKernel,
            static fn(): null => null,
            [$mapRequestPayload],
            $request,
            HttpKernelInterface::SUB_REQUEST,
        );
        $this->requestPayloadValueResolver->onKernelControllerArguments($event);

        return $event->getArguments()[0];
    }

    /**
     * @template T of class-string
     *
     * @param T $type
     *
     * @return ?object<T>
     */
    public function transformQueryString(Request $request, string $type): ?object
    {
        $mapQueryString = new MapQueryString();
        $mapQueryString->metadata = new ArgumentMetadata('QueryStringMetadata', $type, false, false, false);
        $event = new ControllerArgumentsEvent(
            $this->httpKernel,
            static fn(): null => null,
            [$mapQueryString],
            $request,
            HttpKernelInterface::SUB_REQUEST,
        );
        try {
            $this->requestPayloadValueResolver->onKernelControllerArguments($event);
        } catch (HttpException $httpException) {
            if ($httpException->getStatusCode() === 404) {
                return null;
            }

            throw $httpException;
        }

        return $event->getArguments()[0];
    }
}
