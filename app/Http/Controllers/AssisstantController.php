<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AssisstantController extends Controller
{
    use ApiResponder;

    public function createAssistant(Request $request)
    {
        $request->validate([
            'file_id' => 'required|string',
        ]);

        $response = Http::withToken(env('OPENAI_API_KEY'))
            ->acceptJson()
            ->withHeaders([
                'OpenAI-Beta' => 'assistants=v2',
            ])->post('https://api.openai.com/v1/assistants',
                [
                    'name' => 'Data visualizer',
                    'description' => 'You are great at creating beautiful data visualizations I want you to give me all product in the file please and specify the details and the name and others specs ',
                    'model' => 'gpt-4o',
                    'tools' => [
                        [
                            'type' => 'code_interpreter'
                        ]
                    ],
                    'tool_resources' => [
                        'code_interpreter' => [
                            'file_ids' => [$request->file_id]
                        ]
                    ]
                ]
            );

        return $response->json();
    }
}
