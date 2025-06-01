<?php

namespace App\Http\Controllers;

use App\Models\Preinstruction;
use App\Models\RecommendationLog;
use App\Models\AiShows;
use App\Models\Show;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class Aicontroller extends Controller{


function getAiRecommendation($model, $prompt, $temperature, $max_output_tokens, $top_p){
    // $openaiApiKey = env('OPENAI_API_KEY');

    $openaiApiKey = "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";


    // print_r($prompt);exit;
    // exit;
    $response = Http::withHeaders([
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer ' . $openaiApiKey,
    ])->post('https://api.openai.com/v1/responses', [
        'model' => $model,
        'input' => [
            [
                'role' => 'user',
                'content' => [
                    [
                        'type' => 'input_text',
                        'text' => $prompt,
                    ]
                ]
            ]
        ],
        'text' => [
            'format' => [
                'type' => 'text'
            ]
        ],
        'reasoning' => (object)[],
        // 'tools' => [
        //     [
        //         'type' => 'web_search_preview',
        //         'user_location' => [
        //             'type' => 'approximate'
        //         ],
        //         'search_context_size' => 'medium'
        //     ]
        // ],
        "tools" => [
            [
              "type" => "web_search_preview",
              "user_location" => [
                "type" => "approximate",
                "country" => "US"
                ],
              "search_context_size" => "high"
            ]
        ],
        "tool_choice" => "required",
        'temperature' => $temperature,
        'max_output_tokens' => $max_output_tokens,
        'top_p' => $top_p,
        'store' => true
    ]);

    $jsonResponse = $response->json();
   
    // dd($jsonResponse);
    // Extract the JSON string from 'content' and decode it into an array
    $content = $jsonResponse['output'][0]['content'][0]['text'] ?? null;
    if(!$content){
        $content = $jsonResponse['output'][1]['content'][0]['text'] ?? null;
    }
    // dd($jsonResponse['output'][1]['content'][0]['text']);
    $contentDecoded = json_decode($content, true);

   
    $imdbId = @$contentDecoded['IMDb_ID'] ?? null;
    $summary = $contentDecoded['Summary'] ?? null;
    $Show_Name = $contentDecoded['Show_Name'] ?? null;
    $premier_year = $contentDecoded['premier_year'] ?? null;

    return [
        'imdbId' => $imdbId,
        'summary' => $summary,
        'show_name' => $Show_Name,
        'premier_year' => $premier_year
    ];

    // return $response->json();
}



public function generateGeminiContent($prompt)
{
    $apiKey = "AIzaSyDtdte8_kHvMujozQWKEXQrQd43SxSSbvU";

    $response = Http::withHeaders([
        'Content-Type' => 'application/json',
    ])
    ->timeout(120) // Add this line: sets the timeout in seconds
    ->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$apiKey}", [
        'contents' => [
            [
                'parts' => [
                    ['text' => $prompt]
                ]
            ]
        ]
    ]);

    $jsonResponse = $response->json();

    // Extract the text
    $content = $jsonResponse['candidates'][0]['content']['parts'][0]['text'] ?? null;
    // dd($prompt, $jsonResponse);
    if($content){
            // Remove ```json ... ``` using regex
        if (preg_match('/```json\s*(.*?)\s*```/s', $content, $matches)) {
            $cleanJson = $matches[1];
        } else {
            $cleanJson = $content; 
        }
        
        if (json_decode($cleanJson) === null && json_last_error() !== JSON_ERROR_NONE) {
            // Handle JSON decoding error here if needed
            throw new Exception('Invalid JSON format detected.');
        }
        
        // Decode JSON content
        $contentDecoded = json_decode($cleanJson, true);
        
        // Access decoded JSON data
        $imdbId = $contentDecoded['IMDb_ID'] ?? null;
        $summary = $contentDecoded['Summary'] ?? null;
        $showName = $contentDecoded['Show_Name'] ?? null;
        $premierYear = $contentDecoded['premier_year'] ?? null;

        if(!$showName || !$summary){
            // dd($jsonResponse);
        }
        
        return [
            'imdbId' => $imdbId,
            'summary' => $summary,
            'show_name' => $showName,
            'premier_year' => $premierYear
        ];
    
    }else{
        // dd($jsonResponse);
        $this->generateGeminiContent($prompt);
    }
    

}



public function getallRecommendation(){
    $recommendations =  AiShows::where('user_id', auth()->id())->get();

    return response()->json([
     'recommendations' => $recommendations
     ]);
 }


public function getShowDetails($imdbId,$premier_year,$show_name)
    {
        // $response = Http::get('https://api.tvmaze.com/lookup/shows', [
        //     'imdb' => $imdbId,
        // ]);

          $response = Http::get('https://api.tvmaze.com/singlesearch/shows', [
            'q' => $show_name,
        ]);
    
        if ($response->successful()) {
            // dd($response->json());
            return $response->json(); 
        }
    
        return null;
}

public function getRecommendation(){
        $count = AiShows::where('user_id',auth()->id())->count();
        if($count >= 5){
            return response()->json(['error' => true,'message' => 'You can only have 5 AI recs at once. Move or remove one and try again.'
            ],409);
        }else{
        
            try {
                $preinstruction = Preinstruction::first();
                if (!$preinstruction) {
                    return response()->json(['error' => true,'message' =>  'Preinstruction not found.'], 404);
                }

                $shows = Show::select('name', 'genres')->where('type', 'shows-love')->where('user_id', auth()->id())->get();
                $notRecommend =  RecommendationLog::where('user_id', auth()->id())->pluck('name')->toArray();
                if ($shows->isEmpty()) {
                    return response()->json(['error' => true,'message' =>  'Please add shows you love first.'],409);
                }

                $showNames = [];

                foreach ($shows as $show) {
                    $genres = array_map('trim', explode(',', $show->genres));
                    $year = preg_match('/^\d{4}$/', $genres[0]) ? array_shift($genres) : null;
                    // $showNames[] = trim($show->name . ' ' . $year);
                    $showNames[] = trim($show->name . '');
                }

              
                $cleaned_not_Recommend = array_map(function ($notRecommend) {
                    // This regex removes the 4-digit year followed by an optional comma at the end
                    return preg_replace('/\s\d{4},?$/', '', $notRecommend);
                }, $notRecommend);



                $excluded_shows_array = $cleaned_not_Recommend;
                $excluded_shows = " \n";
                
               
                foreach($excluded_shows_array as $key => $excluded_show_item){
                    $show_number = $key + 1;
                    $excluded_shows.=  "$show_number. $excluded_show_item \n";
                }
                // dd($excluded_shows);
                // dd($excluded_shows);
                $formattedShows = implode(",  \n ", $showNames);
                $formattednotRecommend = implode(', \n ', $cleaned_not_Recommend);

                // dd($formattednotRecommend, $formattedShows);
               
                $replaceShows = str_replace('[shows]', $formattedShows, $preinstruction->description);
                $prompt = str_replace('[not recommend]', $excluded_shows, $replaceShows);
                
                $method = Setting::first();

            
            
                    if($method->type = 'gemini'){
                        $recsFromAi = $this->generateGeminiContent($prompt);
                    }else{
                        $recsFromAi = $this->getAiRecommendation(
                            $preinstruction->model_name,
                            $prompt,
                            (float) $preinstruction->temperature,
                            (int) $preinstruction->maximum_length,
                            (float) $preinstruction->top_p
                        );
                    }
                    // dd($recsFromAi);
 

                // ?? 'tt1043813'
             
                $imdbId = @$recsFromAi['imdbId'] ;
                $summary = @$recsFromAi['summary'] ?? 'No summary available.';
               
                

              
               
                $recs = $this->getShowDetails($imdbId,@$recsFromAi['premier_year'], @$recsFromAi['show_name']);
                
                if (!$recs || !isset($recs['name'])) {
                    // dd($recsFromAi, $recs);
                    // return response()->json(['error' => true,'message' =>  'Show details could not be retrieved.'], 500);

                    return response()->json([
                        'recommendation' => [],
                        'summary' => null,
                        'isAlreadyExists' => true
                    ]);

                }
               
                $image = isset($recs['image']['medium']) ? $recs['image']['medium'] : 'https://pinyotta.com/assets/img/No Image.png'; 
                $genres = (isset($recs['premiered']) ? explode('-', $recs['premiered'])[0] . ', ' : 'N/A') . (isset($recs['genres']) ? implode(', ', $recs['genres']) : '');
            
                $isAlreadyExists = true;
                if (!AiShows::where('show_id', $recs['id'])->where('user_id', auth()->id())->where('type','ai-recs')->exists()) {
                    $isAlreadyExists = false;
                    $show = new AiShows;
                    $show->user_id = auth()->id();
                    $show->show_id = $recs['id'];
                    $show->name = $recs['name'];
                    $show->genres = $genres;
                    $show->image = $image;
                    $show->type = 'ai-recs';
                    $show->summary = $summary;
                    $show->save();
                    // 
                    $log = new RecommendationLog;
                    $log->user_id = auth()->id();
                    // $log->name = $recs['name'] . (isset($recs['premiered']) ? ' '.explode('-', $recs['premiered'])[0] .',' : '');
                    $log->name = $recs['name'];
                    $log->ai_response = json_encode($recsFromAi);
                    $log->year = (isset($recs['premiered']) ? ' '.explode('-', $recs['premiered'])[0] .',' : '');
                    $log->imdb_id = $imdbId;

                    $log->save();

                }
 
                $recommendation = [
                    'name' => $recs['name'],
                    'id' => $recs['id'],
                    'image' => $image,
                    'genres' => $genres,

                ];
                return response()->json([
                    'recommendation' => $recommendation,
                    'summary' => $summary,
                    'isAlreadyExists' => $isAlreadyExists
                ]);

            } catch (\Throwable $e) {
                \Log::error('Error generating recommendation: ' . $e->getMessage(), [
                    'trace' => $e->getTraceAsString()
                ]);

                return response()->json([
                    'recommendation' => [],
                    'summary' => null,
                    'isAlreadyExists' => true
                ]);

                // return response()->json(['error' => true,'message' => 'An error occurred while generating recommendations. Please try again.'], 500);
            }
        
        }

}


  

    }

   
