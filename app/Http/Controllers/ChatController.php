<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponder;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Spatie\PdfToText\Pdf;


class ChatController extends Controller
{
    use ApiResponder;

    protected $openAIKey;
    protected $openAIEndpoint;

    public function __construct()
    {
        $this->openAIKey = env('OPENAI_API_KEY');
        $this->openAIEndpoint = 'https://api.openai.com/v1/chat/completions';
    }

    public function uploadToChatgpt(Request $request)
    {

        $request->validate([
            'file' => 'required|file',
        ]);

        $filePath = $request->file('file')->getRealPath();
        $fileName = $request->file('file')->getClientOriginalName();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
        ])->attach(
            'file', file_get_contents($filePath), $fileName
        )->timeout(150)->post('https://api.openai.com/v1/files', [
            'purpose' => 'assistants'
        ]);

        return $response->json();
    }

    public function analysis(Request $request)
    {
        $request->validate([
            'file_id' => 'required|string',
        ]);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
            'Content-Type' => 'application/json',
            'OpenAI-Beta' => 'assistants=v2',
        ])->post('https://api.openai.com/v1/assistants', [
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
        ]);

        return $response->json();
    }

    public function chat($text)
    {
        $openaiApiKey = env('OPENAI_API_KEY');
        $url = 'https://api.openai.com/v1/images/generations';

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $openaiApiKey,
        ])->post($url, [
            'model' => 'dall-e-3',
            'prompt' => 'Draw person with long beard  and white face ',
            'n' => 1,
            'size' => '1024x1024',
        ]);

        return $response->json();
    }

    public function old(Request $request)
    {
        $client = new Client();

        $response = $client->post($this->openAIEndpoint, [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->openAIKey,
            ],
            'json' => [
                'model' => 'text-davinci-003',
                'prompt' => $request->input('prompt'),
                'max_tokens' => 150,
                'temperature' => 0.7,
                'stop' => ['\n']
            ],
        ]);

        return $response->getBody()->getContents();
    }

}
