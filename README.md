# This is my package filament-surveys

[![Latest Version on Packagist](https://img.shields.io/packagist/v/elmudo-dev/filament-surveys.svg?style=flat-square)](https://packagist.org/packages/elmudo-dev/filament-surveys)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/elmudo-dev/filament-surveys/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/elmudometal/filament-surveys/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/elmudo-dev/filament-surveys/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/elmudometal/filament-surveys/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/elmudo-dev/filament-surveys.svg?style=flat-square)](https://packagist.org/packages/elmudo-dev/filament-surveys)



This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require elmudo-dev/filament-surveys
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="filament-surveys-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-surveys-config"
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="filament-surveys-views"
```

This is the contents of the published config file:

```php
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
     * Si usas Filament, puedes simplemente pasar la clase del Enum.
     */
    'models_enum' => null,

    'model_type' => 'App\Models\Survey',
];
```

## Usage

### Asociación de Modelos mediante Enum

Puedes configurar un Enum para listar los modelos que pueden tener encuestas asociadas. Esto permite seleccionar el modelo directamente desde el administrador de Filament al crear una encuesta.

1. Crea un Enum que implemente las opciones:

```php
namespace App\Enums;

enum SurveyModels: string {
    case Course = \App\Models\Course::class;
    case Event = \App\Models\Event::class;
}
```

2. Configura el Enum en `config/filament-surveys.php`:

```php
'models_enum' => \App\Enums\SurveyModels::class,
```

Al hacer esto, en el formulario de creación de encuestas aparecerá un selector para elegir a qué modelo pertenece la encuesta. El sistema utilizará este valor para filtrar y guardar las respuestas.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [elmudometal](https://github.com/elmudometal)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
