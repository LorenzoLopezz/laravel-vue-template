<?php

namespace App\Swagger;

use OpenApi\Annotations\JsonContent;
use OpenApi\Attributes as OA;

class SeguridadPermisoDoc
{
  #[OA\Get(
    path: '/api/v1/admin/permisos',
    tags: ['Permisos'],
    summary: 'Lista los permisos',
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
    path: '/api/v1/admin/permisos/{id}',
    tags: ['Permisos'],
    summary: 'Lista un permiso',
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
    path: '/api/v1/admin/permisos',
    tags: ['Permisos'],
    summary: "Crea un permiso",
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
          'id_modulo',
        ],
        properties: [
          new OA\Property(
            type: 'string',
            property: 'nombre',
            example: 'Crear usuario',
          ),
          new OA\Property(
            type: 'string',
            property: 'descripcion',
            example: 'Descripción del permiso',
          ),
          new OA\Property(
            type: 'integer',
            property: 'id_modulo',
            example: 1,
          ),
        ],
      )
    ),
    responses: [
      new OA\Response(
        response: '201',
        description: 'Permiso creado exitosamente',
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
    path: '/api/v1/admin/permisos/{id}',
    tags: ['Permisos'],
    summary: 'Actualiza un permiso',
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
          'descripcion',
          'id_modulo',
        ],
        properties: [
          new OA\Property(
            type: 'string',
            property: 'descripcion',
            example: 'Descripción del permiso',
          ),
          new OA\Property(
            type: 'integer',
            property: 'id_modulo',
            example: 1,
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

  #[OA\Delete(
    path: '/api/v1/admin/permisos/{id}',
    tags: ['Permisos'],
    summary: 'Elimina un permiso',
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
        description: 'Eliminación del registro realizada con éxito',
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
