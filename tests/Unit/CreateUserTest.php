<?php

uses(Tests\TestCase::class);

it('performs the creation of 1 or more users acording to the generated data from GPT API', function () {
    $response = $this->get('/api/users/generate/' . rand(1, 10));
    $response->assertStatus(200);
    $response->assertJson([
        "status" => "SUCCESS",
        "message" => "Datos generados correctamente",
        "data" => []
    ]);
});

it('throws an error due there\'s no parameter to specify the quantity of users wants to be created', function () {
    $response = $this->get('/api/users/generate/');
    $response->assertStatus(500);
    $response->assertJson([
        "status" => "ERROR"
    ]);
});
