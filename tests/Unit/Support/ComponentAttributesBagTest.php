<?php

test('it should parse boolean attributes', function () {
    $attributes = [
        'foo'    => true,
        'bar'    => false,
        'baz'    => 0,
        'docker' => 'container',
        'sail'   => 'laravel',
    ];

    $bag = new WireUi\Support\ComponentAttributesBag($attributes);

    expect($bag->getAttributes())->toBe([
        'foo'    => 'true',
        'docker' => 'container',
        'sail'   => 'laravel',
    ]);
});
