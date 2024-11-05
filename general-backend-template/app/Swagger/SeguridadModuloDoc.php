<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

class SeguridadModuloDoc
{
  #[OA\Get(
    path: '/api/v1/admin/modulos',
    tags: ['Módulos'],
    summary: 'Lista los módulos',
    security: [
      [
        'bearerAuth' => []
      ]
    ],
    responses: [
      new OA\Response(
        response: '200',
        description: 'Petición realizada con éxito',
      ),
      new OA\Response(
        response: '401',
        description: 'Sin sesión iniciada',
      ),
      new OA\Response(
        response: '500',
        description: 'Error interno en el servidor',
      )
    ]
  )]

  #[OA\Get(
    path: '/api/v1/admin/modulos/{id}',
    tags: ['Módulos'],
    summary: 'Lista un módulo',
    security: [
      [
        'bearerAuth' => []
      ]
    ],
    parameters: [
      new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
    ],
    responses: [
      new OA\Response(
        response: '200',
        description: 'Petición realizada con éxito',
      ),
      new OA\Response(
        response: '401',
        description: 'Sin sesión iniciada',
      ),
      new OA\Response(
        response: '500',
        description: 'Error interno en el servidor',
      ),
      new OA\Response(
        response: '404',
        description: 'Registro no encontrado',
      )
    ]
  )]

  #[OA\Post(
    path: '/api/v1/admin/modulos',
    tags: ['Módulos'],
    summary: "Crea un módulo",
    security: [
      [
        'bearerAuth' => []
      ]
    ],
    requestBody: new OA\RequestBody(
      required: true,
      content: new OA\JsonContent(
        required: [
          'nombre',
          'acronimo',
        ],
        properties: [
          new OA\Property(
            type: 'string',
            property: 'nombre',
            example: 'Usuario',
          ),
          new OA\Property(
            type: 'string',
            property: 'acronimo',
            example: 'USER',
          ),
        ],
      )
    ),
    responses: [
      new OA\Response(
        response: '201',
        description: 'Módulo creado exitosamente',
      ),
      new OA\Response(
        response: '400',
        description: 'Petición erronea',
      ),
      new OA\Response(
        response: '401',
        description: 'Sin sesión iniciada',
      ),
      new OA\Response(
        response: '500',
        description: 'Error interno en el servidor',
      )
    ]
  )]

  #[OA\PUT(
    path: '/api/v1/admin/modulos/{id}',
    tags: ['Módulos'],
    summary: 'Actualiza un módulo',
    security: [
      [
        'bearerAuth' => []
      ]
    ],
    parameters: [
      new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
    ],
    requestBody: new OA\RequestBody(
      required: true,
      content: new OA\JsonContent(
        required: [
          'nombre',
          'acronimo',
        ],
        properties: [
          new OA\Property(
            type: 'string',
            property: 'nombre',
            example: 'Testing',
          ),
          new OA\Property(
            type: 'string',
            property: 'acronimo',
            example: 'TEST',
          ),
        ],
      )
    ),
    responses: [
      new OA\Response(
        response: '200',
        description: 'Actualización realizada con éxito',
      ),
      new OA\Response(
        response: '400',
        description: 'Petición erronea',
      ),
      new OA\Response(
        response: '401',
        description: 'Sin sesión iniciada',
      ),
      new OA\Response(
        response: '500',
        description: 'Error interno en el servidor',
      )
    ]
  )]

  #[OA\Put(
    path: '/api/v1/admin/modulos/{id}/estado/{id_estado}',
    tags: ['Módulos'],
    summary: 'Actualiza el estado de un módulo',
    security: [
      [
        'bearerAuth' => []
      ]
    ],
    parameters: [
      new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
      new OA\Parameter(name: 'id_estado', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
    ],
    responses: [
      new OA\Response(
        response: '200',
        description: 'Estado actualizado con éxito',
      ),
      new OA\Response(
        response: '400',
        description: 'Petición erronea',
      ),
      new OA\Response(
        response: '401',
        description: 'Sin sesión iniciada',
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
