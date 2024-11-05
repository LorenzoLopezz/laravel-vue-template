<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

class AuthDoc
{
  #[OA\Post(
    path: '/public/auth/iniciar-sesion',
    tags: ['Login'],
    summary: "Realiza el inicio de sesión para los usuarios existentes",
    requestBody: new OA\RequestBody(
      required: true,
      content: new OA\JsonContent(
        required: [
          'email',
          'password',
        ],
        properties: [
          new OA\Property(
            type: 'string',
            property: 'email',
            example: 'admin@mail.com',
          ),
          new OA\Property(
            type: 'password',
            property: 'password',
            example: 'admin',
          ),
        ],
      )
    ),
    responses: [
      new OA\Response(
        response: '200',
        description: 'Permiso creado exitosamente',
        content: new OA\JsonContent(
          properties: [
            new OA\Property(
              property: 'status',
              example: 200
            ),
            new OA\Property(
              property: 'data',
              example: ['message' => 'Sesión iniciada éxitosamente']
            ),
            new OA\Property(
              property: 'errors',
              example: []
            )
          ]
        )
      ),
      new OA\Response(
        response: '422',
        description: 'Entidad no procesable',
      ),
      new OA\Response(
        response: '500',
        description: 'Error interno en el servidor',
      )
    ]
  )]
  public function store()
  {
    // this method are empty because is just for the documentation
  }
}
