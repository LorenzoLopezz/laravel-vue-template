<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

class SeguridadMenuOpcionDoc
{
  #[OA\Get(
    path: '/api/v1/admin/menu-opciones',
    tags: ['Menu'],
    summary: 'Lista los menu',
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
    path: '/api/v1/admin/menu-opciones/{id}',
    tags: ['Menu'],
    summary: 'Lista un menu',
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
    path: '/api/v1/admin/menu-opciones',
    tags: ['Menu'],
    summary: "Crea un menu",
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
          'icono',
          'uri',
          'id_menu_opcion_padre',
          'id_modulo',
          'id_estado',
        ],
        properties: [
          new OA\Property(
            type: 'string',
            property: 'nombre',
            example: 'Usuario',
          ),
          new OA\Property(
            type: 'string',
            property: 'icono',
            example: 'home',
          ),
          new OA\Property(
            type: 'string',
            property: 'uri',
            example: '/home',
          ),
          new OA\Property(
            type: 'integer',
            property: 'id_menu_opcion_padre',
            example: 1,
          ),
          new OA\Property(
            type: 'integer',
            property: 'id_modulo',
            example: 1,
          ),
          new OA\Property(
            type: 'integer',
            property: 'id_estado',
            example: 1,
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
    path: '/api/v1/admin/menu-opciones/{id}',
    tags: ['Menu'],
    summary: 'Actualiza un menu',
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
          'icono',
          'uri',
          'id_menu_opcion_padre',
          'id_modulo',
          'id_estado',
        ],
        properties: [
          new OA\Property(
            type: 'string',
            property: 'nombre',
            example: 'Usuario',
          ),
          new OA\Property(
            type: 'string',
            property: 'icono',
            example: 'home',
          ),
          new OA\Property(
            type: 'string',
            property: 'uri',
            example: '/home',
          ),
          new OA\Property(
            type: 'integer',
            property: 'id_menu_opcion_padre',
            example: 1,
          ),
          new OA\Property(
            type: 'integer',
            property: 'id_modulo',
            example: 1,
          ),
          new OA\Property(
            type: 'integer',
            property: 'id_estado',
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

  #[OA\Put(
    path: '/api/v1/admin/menu-opciones/{id}/estado/{id_estado}',
    tags: ['Menu'],
    summary: 'Actualiza el estado de un menu',
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
