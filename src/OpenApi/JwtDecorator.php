<?php

declare(strict_types=1);

namespace App\OpenApi;

use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\Core\OpenApi\OpenApi;
use ApiPlatform\Core\OpenApi\Model;

final class JwtDecorator implements OpenApiFactoryInterface
{
    public function __construct(
        private OpenApiFactoryInterface $decorated
    ) {}

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = ($this->decorated)($context);
        $schemas = $openApi->getComponents()->getSchemas();

        $this->addTokenSchema($schemas);
        $this->addCredentialsSchema($schemas);

        $this->addLoginEndpoint($openApi);

        return $openApi;
    }

    private function addTokenSchema(\ArrayObject $schemas): void {

        $schemas['Token'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'id' => [
                    'type' => 'integer',
                    'readOnly' => true,
                ],
                'email' => [
                    'type' => 'string',
                    'readOnly' => true,
                ],
                'roles' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'string'
                    ],
                    'readOnly' => true,
                ],
                'token' => [
                    'type' => 'string',
                    'readOnly' => true,
                ],
            ],
        ]);
        $schemas['Credentials'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'username' => [
                    'type' => 'string',
                    'example' => 'test@test.test',
                ],
                'password' => [
                    'type' => 'string',
                    'example' => 'test',
                ],
            ],
        ]);
    }

    private function addCredentialsSchema(\ArrayObject $schemas): void {
        $schemas['Credentials'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'username' => [
                    'type' => 'string',
                    'example' => 'test@test.test',
                ],
                'password' => [
                    'type' => 'string',
                    'example' => 'test',
                ],
            ],
        ]);
    }

    private function addLoginEndpoint(OpenApi $openApi): void {
        $pathItem = new Model\PathItem(
            ref: 'JWT Token',
            post: new Model\Operation(
                operationId: 'postCredentialsItem',
                tags: ['Token'],
                responses: [
                    '200' => [
                        'description' => 'Get JWT token',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/Token',
                                ],
                            ],
                        ],
                    ],
                ],
                summary: 'Get JWT token to login.',
                requestBody: new Model\RequestBody(
                    description: 'Generate new JWT Token',
                    content: new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                '$ref' => '#/components/schemas/Credentials',
                            ],
                        ],
                    ]),
                ),
            ),
        );
        $openApi->getPaths()->addPath('/api/login_check', $pathItem);
    }
}