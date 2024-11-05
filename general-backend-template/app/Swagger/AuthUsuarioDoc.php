<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

class AuthUsuarioDoc
{
  #[OA\Get(
    path: '/api/v1/admin/usuarios',
    tags: ['Usuarios'],
    summary: 'Lista los usuarios',
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
    path: '/api/v1/admin/usuarios/info',
    tags: ['Usuarios'],
    summary: 'Ver informacion del usuario',
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
    path: '/api/v1/auth/menu',
    tags: ['Usuarios'],
    summary: 'Ver el menu del usuario',
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
    path: '/api/v1/admin/usuarios/{id}',
    tags: ['Usuarios'],
    summary: 'Lista un usuario',
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
    path: '/api/v1/admin/usuarios',
    tags: ['Usuarios'],
    summary: "Crea un usuario",
    security: [
      [
        'bearerAuth' => []
      ]
    ],
    requestBody: new OA\RequestBody(
      required: true,
      content: new OA\JsonContent(
        required: [
          'email',
          'password',
          'password_repeat',
          'perfiles',
          'permisos',
        ],
        properties: [
          new OA\Property(
            type: 'email',
            property: 'email',
            example: 'usuario@gmail.com',
          ),
          new OA\Property(
            type: 'password',
            property: 'password',
            example: 'admin123',
          ),
          new OA\Property(
            type: 'password',
            property: 'password_repeat',
            example: 'admin123',
          ),
          new OA\Property(
            type: 'array',
            property: 'perfiles',
            example: [],
            items: new OA\Items() // Add this line to define the items for the array
          ),
          new OA\Property(
            type: 'array',
            property: 'permisos',
            example: [],
            items: new OA\Items() // Add this line to define the items for the array
          ),
        ],
      )
    ),
    responses: [
      new OA\Response(
        response: '201',
        description: 'Usuario creado exitosamente',
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
    path: '/api/v1/admin/usuarios/{id}',
    tags: ['Usuarios'],
    summary: 'Actualiza un usuario',
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
          'id_estado',
          'perfiles',
          'permisos',
        ],
        properties: [
          new OA\Property(
            type: 'integer',
            property: 'id_estado',
            example: 'admin123',
          ),
          new OA\Property(
            type: 'array',
            property: 'perfiles',
            example: [],
            items: new OA\Items() // Add this line to define the items for the array
          ),
          new OA\Property(
            type: 'array',
            property: 'permisos',
            example: [],
            items: new OA\Items() // Add this line to define the items for the array
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
    path: '/api/v1/admin/usuarios/{id}/password',
    tags: ['Usuarios'],
    summary: 'Actualiza la contraseña del usuario',
    security: [
      [
        'bearerAuth' => []
      ]
    ],
    parameters: [
      new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
    ],
    requestBody: new OA\RequestBody(
      required: true,
      content: new OA\JsonContent(
        required: [
          'password',
          'password_repeat',
        ],
        properties: [
          new OA\Property(
            type: 'password',
            property: 'password',
            example: 'admin123',
          ),
          new OA\Property(
            type: 'password',
            property: 'password_repeat',
            example: 'admin123',
          ),
        ],
      )
    ),
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
