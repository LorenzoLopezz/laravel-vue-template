<?php

return [
  /*
  |--------------------------------------------------------------------------
  | Mensajes de Validación
  |--------------------------------------------------------------------------
  |
  | Las siguientes líneas de idioma contienen los mensajes de error predeterminados utilizados por
  | la clase de validador. Algunas de estas reglas tienen varias versiones, como
  | las reglas de tamaño. Siéntete libre de ajustar cada uno de estos mensajes aquí.
  |
  */

  'accepted' => 'El campo :attribute debe ser aceptado',
  'accepted_if' => 'El campo :attribute debe ser aceptado cuando :other es :value',
  'active_url' => 'El campo :attribute no es una URL válida',
  'after' => 'El campo :attribute debe ser una fecha posterior a :date',
  'after_or_equal' => 'El campo :attribute debe ser una fecha posterior o igual a :date',
  'alpha' => 'El campo :attribute debe contener solo letras',
  'alpha_dash' => 'El campo :attribute debe contener solo letras, números, guiones y guiones bajos',
  'alpha_num' => 'El campo :attribute debe contener solo letras y números',
  'array' => 'El campo :attribute debe ser un arreglo',
  'before' => 'El campo :attribute debe ser una fecha anterior a :date',
  'before_or_equal' => 'El campo :attribute debe ser una fecha anterior o igual a :date',
  'between' => [
      'numeric' => 'El campo :attribute debe estar entre :min y :max',
      'file' => 'El campo :attribute debe estar entre :min y :max kilobytes',
      'string' => 'El campo :attribute debe tener entre :min y :max caracteres',
      'array' => 'El campo :attribute debe tener entre :min y :max elementos',
  ],
  'boolean' => 'El campo :attribute debe ser verdadero o falso',
  'confirmed' => 'La confirmación del campo :attribute no coincide',
  'current_password' => 'La contraseña es incorrecta',
  'date' => 'El campo :attribute no es una fecha válida',
  'date_equals' => 'El campo :attribute debe ser una fecha igual a :date',
  'date_format' => 'El campo :attribute no coincide con el formato :formato',
  'declined' => 'El campo :attribute debe ser declinado',
  'declined_if' => 'El campo :attribute debe ser declinado cuando :other es :value',
  'different' => 'El campo :attribute y :other deben ser diferentes',
  'digits' => 'El campo :attribute debe tener :digits dígitos',
  'digits_between' => 'El campo :attribute debe tener entre :min y :max dígitos',
  'dimensions' => 'El campo :attribute tiene dimensiones de imagen inválidas',
  'distinct' => 'El campo :attribute tiene un valor duplicado.',
  'email' => 'El campo :attribute debe ser una dirección de correo electrónico válida',
  'ends_with' => 'El campo :attribute debe terminar con uno de los siguientes: :values',
  'enum' => 'El :attribute seleccionado es inválido',
  'exists' => 'El :attribute seleccionado no existe',
  'file' => 'El campo :attribute debe ser un archivo',
  'filled' => 'El campo :attribute debe tener un valor',
  'gt' => [
      'numeric' => 'El campo :attribute debe ser mayor que :value',
      'file' => 'El campo :attribute debe ser mayor que :value kilobytes',
      'string' => 'El campo :attribute debe tener más de :value caracteres',
      'array' => 'El campo :attribute debe tener más de :value elementos',
  ],
  'gte' => [
      'numeric' => 'El campo :attribute debe ser mayor o igual que :value',
      'file' => 'El campo :attribute debe ser mayor o igual que :value kilobytes',
      'string' => 'El campo :attribute debe tener :value o más caracteres',
      'array' => 'El campo :attribute debe tener :value elementos o más',
  ],
  'image' => 'El campo :attribute debe ser una imagen',
  'in' => 'El :attribute seleccionado es inválido',
  'in_array' => 'El campo :attribute no existe en :other',
  'integer' => 'El campo :attribute debe ser un número entero',
  'ip' => 'El campo :attribute debe ser una dirección IP válida',
  'ipv4' => 'El campo :attribute debe ser una dirección IPv4 válida',
  'ipv6' => 'El campo :attribute debe ser una dirección IPv6 válida',
  'json' => 'El campo :attribute debe ser una cadena JSON válida',
  'lt' => [
      'numeric' => 'El campo :attribute debe ser menor que :value',
      'file' => 'El campo :attribute debe ser menor que :value kilobytes',
      'string' => 'El campo :attribute debe tener menos de :value caracteres',
      'array' => 'El campo :attribute debe tener menos de :value elementos',
  ],
  'lte' => [
      'numeric' => 'El campo :attribute debe ser menor o igual que :value',
      'file' => 'El campo :attribute debe ser menor o igual que :value kilobytes',
      'string' => 'El campo :attribute debe tener :value o menos caracteres',
      'array' => 'El campo :attribute no debe tener más de :value elementos',
  ],
  'mac_address' => 'El campo :attribute debe ser una dirección MAC válida',
  'max' => [
      'numeric' => 'El campo :attribute no debe ser mayor que :max',
      'file' => 'El campo :attribute no debe ser mayor que :max kilobytes',
      'string' => 'El campo :attribute no debe ser mayor que :max caracteres',
      'array' => 'El campo :attribute no debe tener más de :max elementos',
  ],
  'mimes' => 'El campo :attribute debe ser un archivo de tipo: :values',
  'mimetypes' => 'El campo :attribute debe ser un archivo de tipo: :values',
  'min' => [
      'numeric' => 'El campo :attribute debe ser al menos :min',
      'file' => 'El campo :attribute debe ser al menos :min kilobytes',
      'string' => 'El campo :attribute debe tener al menos :min caracteres',
      'array' => 'El campo :attribute debe tener al menos :min elementos',
  ],
  'multiple_of' => 'El campo :attribute debe ser un múltiplo de :value',
  'not_in' => 'El :attribute seleccionado es no ha sido encontrado',
  'not_regex' => 'El formato del campo :attribute es inválido',
  'numeric' => 'El campo :attribute debe ser un número',
  'password' => 'La contraseña es incorrecta',
  'present' => 'El campo :attribute debe estar presente',
  'prohibited' => 'El campo :attribute está prohibido',
  'prohibited_if' => 'El campo :attribute está prohibido cuando :other es :value',
  'prohibited_unless' => 'El campo :attribute está prohibido a menos que :other esté en :values',
  'prohibits' => 'El campo :attribute prohíbe que :other esté presente',
  'regex' => 'El formato del campo :attribute es inválido',
  'required' => 'El campo :attribute es obligatorio',
  'required_array_keys' => 'El campo :attribute debe contener entradas para: :values',
  'required_if' => 'El campo :attribute es obligatorio cuando :other es :value',
  'required_unless' => 'El campo :attribute es obligatorio a menos que :other esté en :values',
  'required_with' => 'El campo :attribute es obligatorio cuando :values está presente',
  'required_with_all' => 'El campo :attribute es obligatorio cuando :values están presentes',
  'required_without' => 'El campo :attribute es obligatorio cuando :values no está presente',
  'required_without_all' => 'El campo :attribute es obligatorio cuando ninguno de :values está presente',
  'same' => 'El campo :attribute y :other deben coincidir',
  'size' => [
      'numeric' => 'El campo :attribute debe ser :size',
      'file' => 'El campo :attribute debe ser :size kilobytes',
      'string' => 'El campo :attribute debe tener :size caracteres',
      'array' => 'El campo :attribute debe contener :size elementos',
  ],
  'starts_with' => 'El campo :attribute debe empezar con uno de los siguientes: :values',
  'string' => 'El campo :attribute debe ser una cadena de texto',
  'timezone' => 'El campo :attribute debe ser una zona horaria válida',
  'unique' => 'El campo :attribute ya ha sido registrado',
  'uploaded' => 'La carga del campo :attribute falló',
  'url' => 'El campo :attribute debe ser una URL válida',
  'uuid' => 'El campo :attribute debe ser un UUID válido',

  /*
  |--------------------------------------------------------------------------
  | Mensajes de Validación Personalizados
  |--------------------------------------------------------------------------
  |
  | Aquí puedes especificar mensajes de validación personalizados para atributos utilizando la
  | convención "nombre-atributo.regla" para nombrar las líneas. Esto facilita
  | especificar un mensaje de idioma específico para una regla de atributo dada.
  |
  */

  'custom' => [
      'nombre-atributo' => [
          'nombre-regla' => 'mensaje-personalizado',
      ],
  ],

  /*
  |--------------------------------------------------------------------------
  | Atributos de Validación Personalizados
  |--------------------------------------------------------------------------
  |
  | Las siguientes líneas de idioma se utilizan para cambiar nuestros marcadores de atributos
  | con algo más fácil de entender, como "Dirección de correo electrónico" en lugar
  | de "correo electrónico". Esto simplemente nos ayuda a hacer nuestro mensaje más expresivo.
  |
  */

  'attributes' => [],
];