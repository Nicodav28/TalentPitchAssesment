<?php

namespace App\Http\Controllers;

use App\Helpers\GptAI;
use App\Models\Challenge;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChallengeController extends Controller
{
    /**
     * Genera conjuntos de datos de desafíos automáticamente y los guarda en la base de datos.
     *
     * @param  string $dataQuantity Cantidad de datos a generar.
     * @return JsonResponse
     */
    public function storeAutoGeneratedData(string $dataQuantity)
    {
        try {
            // Genera el prompt para la inteligencia artificial.
            $prompt = $this->promptDefinition($dataQuantity);

            // Inicializa la instancia de GptAI.
            $gpt = new GptAI();

            // Obtiene la respuesta de la inteligencia artificial.
            $response = $gpt->openAI($prompt);

            // Maneja errores si hay algún problema en la generación de datos.
            if ($response['error']) {
                throw new \Exception($response['message']);
            }

            // Decodifica y guarda los datos generados en la base de datos.
            $firstChoice = $response['response']->choices[0]->message->content;
            $firstChoice = json_decode($firstChoice, true);
            foreach ($firstChoice as $generatedChallenge) {
                $challenge = new Challenge();
                $challenge->title = $generatedChallenge["titulo"];
                $challenge->description = $generatedChallenge["descripcion"];
                $challenge->difficulty = $generatedChallenge["dificultad"];
                $challenge->user_id = $generatedChallenge["usuarioId"];
                $challenge->save();
            }

            // Retorna una respuesta JSON con los datos generados.
            return response()->json([
                "status" => "SUCCESS",
                "message" => "Datos generados correctamente",
                "data" => $firstChoice
            ], 200);
        } catch (\Throwable $th) {
            // Retorna una respuesta JSON en caso de error.
            return response()->json([
                "status" => "ERROR",
                "message" => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Define el prompt para la generación de datos de desafíos.
     *
     * @param  string $dataQuantity Cantidad de datos a generar.
     * @return string El prompt generado.
     */
    private function promptDefinition(string $dataQuantity): string
    {
        $prompt = "Genera $dataQuantity conjuntos de datos aleatorios donde cada conjunto debe tener un título de un desafío de una vacante laboral ficticia de ámbito profesional, una descripción de ese desafío, una dificultad del 1 al 10 de ese desafío
        y alguno de los siguientes identificadores de usuarios debe ser asociado a ese desafío:";

        // Obtiene todos los usuarios existentes en la base de datos.
        $users = User::all();

        foreach ($users as $user) {
            $prompt .= " - $user->id - ";
        }

        // Describe el formato esperado de la salida.
        $prompt .= "La salida no debe tener nada mas que un JSON que contenga un array de conjuntos de datos, donde cada conjunto de datos esté representado como un objeto con las claves 'titulo', 'descripcion', 'dificultad' y 'usuarioId'.";

        return $prompt;
    }

    /**
     * Obtiene todos los desafíos de la base de datos.
     *
     * @return JsonResponse
     */
    public function indexAll()
    {
        $challenges =  Challenge::paginate(10);

        return response()->json([
            "status" => "SUCCESS",
            "message" => "Datos obtenidos correctamente",
            "data" => $challenges
        ], 200);
    }

    /**
     * Obtiene un desafío específico de la base de datos por su ID.
     *
     * @param  mixed $id ID del desafío.
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            $challenge = Challenge::findOrFail($id);

            return response()->json([
                "status" => "SUCCESS",
                "message" => "Desafío obtenido correctamente",
                "data" => $challenge
            ], 200);
        } catch (\Throwable $th) {
            // Retorna una respuesta JSON en caso de error.
            return response()->json([
                "status" => "ERROR",
                "message" => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Actualiza un desafío específico en la base de datos por su ID.
     *
     * @param  Request $request Datos de actualización del desafío.
     * @param  mixed $id ID del desafío.
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $challenge = Challenge::findOrFail($id);
            $challenge->update($request->all());

            return response()->json([
                "status" => "SUCCESS",
                "message" => "Desafío actualizado correctamente",
                "data" => $challenge
            ], 200);
        } catch (\Throwable $th) {
            // Retorna una respuesta JSON en caso de error.
            return response()->json([
                "status" => "ERROR",
                "message" => $th->getMessage()
            ], 500);
        }
    }

    public function delete($id): JsonResponse
    {
        try {
            $challenge = Challenge::findOrFail($id);
            $challenge->delete();

            return response()->json([
                "status" => "SUCCESS",
                "message" => "Desafío eliminado correctamente"
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "ERROR",
                "message" => $th->getMessage()
            ], 500);
        }
    }
}
