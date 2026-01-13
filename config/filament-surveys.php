<?php

return [
    // Prefijo para las rutas públicas (participantes)
    'public_prefix' => 'survey',

    // Remitente opcional para correos de invitación
    'invite_mail_from' => env('SURVEYS_MAIL_FROM', null),

    // Cola opcional para encolar los correos de invitación
    'invite_queue' => env('SURVEYS_INVITE_QUEUE', null),

    // Longitud del enlace único para participantes
    'link_length' => 32,

    /**
     * Enum que contiene los modelos disponibles para asociar a las encuestas.
     * El enum debe implementar un método cases() que retorne los modelos.
     */
    'models_enum' => null,

    'model_type' => 'App\Models\Survey',
];
