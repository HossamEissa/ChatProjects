<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ThreadController extends Controller
{
    use ApiResponder;

    public function createThreads(Request $request)
    {
        $response = Http::withToken(env('OPENAI_API_KEY'))
            ->acceptJson()
            ->withHeaders([
                'OpenAI-Beta' => 'assistants=v2',
            ])
            ->post('https://api.openai.com/v1/threads',
                [
                    'messages' => [
                        [
                            'role' => 'user',
                            'content' => 'Retrieve the product and related data in the files but in details.',
                            'attachments' => [
                                [
                                    'file_id' => $request->file_id,
                                    'tools' => [['type' => 'code_interpreter']]
                                ]
                            ]
                        ]
                    ]
                ]
            );

        return $response->json();
    }

    public function runThread(Request $request)
    {
        $threadId = $request->threadId;
        $assistantId = $request->assistantId;

        $response = Http::withToken(env('OPENAI_API_KEY'))
            ->withHeaders([
                'Content-Type' => 'application/json',
                'OpenAI-Beta' => 'assistants=v2',
            ])
            ->post("https://api.openai.com/v1/threads/{$threadId}/runs",
                [
                    'assistant_id' => $assistantId
                ]);

        return $response->json();
    }

    function interactWithAssistant(Request $request)
    {
        $assistantId = $request->assistantId;
        $response = Http::withToken(env('OPENAI_API_KEY'))
            ->acceptJson()
            ->withHeaders([
                'OpenAI-Beta' => 'assistants=v2',
            ])->post('https://api.openai.com/v1/threads', [
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => 'Analyze the data in the uploaded file and return data about each product.'
                    ]
                ],
            ]);

        return $response->json();
    }

    public function outPut()
    {
        $response = Http::withToken(env('OPENAI_API_KEY'))
            ->withHeaders([
                'Content-Type' => 'application/json',
                'OpenAI-Beta' => 'assistants=v2',
            ])
            ->post('https://api.openai.com/v1/threads/thread_skNdCvLZz1rEBJc4sK32fdjt/runs/run_A900CxvYcARGtNXt57yujpql/submit_tool_outputs', [
                'tool_outputs' => [
                    [
                        'tool_call_id' => 'call_001',
                        'output' => 'Retrieve the product and their details ',
                    ],
                ],
            ]);

        return $response->json();
    }
}
