<?php

namespace App\Http\Controllers;

use App\Helpers\GptAI;
use App\Models\Program;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    /**
     * Genera conjuntos de datos de programas automáticamente y los guarda en la base de datos.
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
            $gpt = new GptAI;

            // Obtiene la respuesta de la inteligencia artificial.
            $response = $gpt->openAI($prompt);

            // Maneja errores si hay algún problema en la generación de datos.
            if ($response['error']) {
                throw new \Exception($response['message']);
            }

            // Decodifica y guarda los datos generados en la base de datos.
            $firstChoice = $response['response']->choices[0]->message->content;
            $firstChoice = json_decode($firstChoice, true);
            foreach ($firstChoice as $generatedProgram) {
                $program = new Program();
                $program->title = $generatedProgram["titulo"];
                $program->description = $generatedProgram["descripcion"];
                $program->start_date = $generatedProgram["fecha_inicio"];
                $program->end_date = $generatedProgram["fecha_fin"];
                $program->user_id = $generatedProgram["usuarioId"];
                $program->save();
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
     * Define el prompt para la generación de datos de programas.
     *
     * @param  string $dataQuantity Cantidad de datos a generar.
     * @return string El prompt generado.
     */
    private function promptDefinition(string $dataQuantity): string
    {
        $prompt = "Genera $dataQuantity conjuntos de datos aleatorios donde cada conjunto debe tener un nombre ficticio de una vacante laboral de ambito profesional, la descripcion de esa vacante laboral, la fecha de inicio de postulacion de candidatos,
        la fecha final de postulacion de candidatos y alguno de los siguientes identificadores de usuarios debe ser asociado a esa vacante laboral:";

        $users = User::all();

        foreach ($users as $user) {
            $prompt .= " - $user->id - ";
        }

        $prompt .= "La salida no debe tener nada mas que un JSON que contenga un array de conjuntos de datos, donde cada conjunto de datos esté representado como un objeto con las claves 'titulo', 'descripcion', 'fecha_inicio', 'fecha_fin' y 'usuarioId'.";

        return $prompt;
    }

    /**
     * Obtiene todos los programas de la base de datos.
     *
     * @return JsonResponse
     */
    public function indexAll()
    {
        $program =  Program::paginate(10);

        return response()->json([
            "status" => "SUCCESS",
            "message" => "Datos obtenidos correctamente",
            "data" => $program
        ], 200);
    }

    /**
     * Actualiza un programa específico en la base de datos por su ID.
     *
     * @param  Request $request Datos de actualización del programa.
     * @param  mixed $id ID del programa.
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $program = Program::findOrFail($id);
            $program->update($request->all());

            return response()->json([
                "status" => "SUCCESS",
                "message" => "Programa actualizado correctamente",
                "data" => $program
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "ERROR",
                "message" => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Elimina un programa específico de la base de datos por su ID.
     *
     * @param  mixed $id ID del programa.
     * @return JsonResponse
     */
    public function delete($id): JsonResponse
    {
        try {
            $program = Program::findOrFail($id);
            $program->delete();

            return response()->json([
                "status" => "SUCCESS",
                "message" => "Programa eliminado correctamente"
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "ERROR",
                "message" => $th->getMessage()
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $program = Program::findOrFail($id);

            return response()->json([
                "status" => "SUCCESS",
                "message" => "Programa obtenido correctamente",
                "data" => $program
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "ERROR",
                "message" => $th->getMessage()
            ], 500);
        }
    }
}
