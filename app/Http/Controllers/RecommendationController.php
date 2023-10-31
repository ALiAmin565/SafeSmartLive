<?php

namespace App\Http\Controllers;

use App\Models\plan;
use App\Models\tagert;
use App\Models\ViewsRecomendition;
use GuzzleHttp\Client;
use App\Models\Archive;
use App\Models\plan_recommendation;
use App\Events\recommend;
use Illuminate\Http\Request;
use App\Models\recommendation;
use App\Http\Resources\PlanResource;
use Illuminate\Support\Facades\Http;
use Intervention\Image\Facades\Image;
use App\Http\Resources\RecommendationResource;
use App\Http\Requests\StorerecommendationRequest;
use App\Http\Requests\UpdaterecommendationRequest;
use Spondonit\Arabic\I18N_Arabic;
use Illuminate\Support\Str;
use App\Models\TargetsRecmo;


class RecommendationController extends Controller
{


    public function __construct()
    {
        // $user = auth('api')->user()->load('role');

        // $this->user = $user;
    }

    public function index(Request $request)
    {
        $user = auth('api')->user()->load('role');
        $page = $request->input('page', 1); // Get the requested page from the request parameters

        if ($user->state == 'admin') {
            $planIds = $user->role->pluck('pivot')->pluck('plan_id');

            $recommendations = recommendation::orderBy('created_at', 'desc')
                ->where('archive', 0)
                ->whereIn('planes_id', $planIds)
                ->with(['user', 'target', 'Recommindation_Plan', 'ViewsRecomenditionnumber', 'tragetsRecmo'])
                ->paginate(20, ['*'], 'page', $page);

            return response()->json([
                'data' => RecommendationResource::collection($recommendations),
                'meta' => [
                    'current_page' => $recommendations->currentPage(),
                    'last_page' => $recommendations->lastPage(),
                    'total' => $recommendations->total(),
                    'next_page_url' => $recommendations->nextPageUrl(),
                ],
            ]);
        } else {
            $recommendations = recommendation::orderBy('created_at', 'desc')
                ->where('archive', 0)
                ->with(['user', 'target', 'Recommindation_Plan.plan', 'ViewsRecomenditionnumber', 'tragetsRecmo'])
                ->paginate(20, ['*'], 'page', $page);

            return response()->json([
                'data' => RecommendationResource::collection($recommendations),
                'meta' => [
                    'current_page' => $recommendations->currentPage(),
                    'last_page' => $recommendations->lastPage(),
                    'total' => $recommendations->total(),
                    'next_page' => $recommendations->nextPageUrl(),
                ],
            ]);
        }
    }


    public function storeApiRequest(Request $request)
    {
        $data = $request->all();
        $targets = $data['targets'];
        $entry = $data['entry'];
        $bot_number = $data['bot_num'];
        $floatArray = array_map('floatval', $entry);
        // Get the minimum and maximum values
        $minValue = min($floatArray);
        $maxValue = max($floatArray);
        // Format the result as "minValue - maxValue"
        $result = $minValue . ' - ' . $maxValue;
        $test = recommendation::create([
            'currency' => $data['ticker'],
            'entry_price' => $result,
            'stop_price' => $data['stoplose'],
            'desc' => "Created by API",
            'title' => "Bot Recommendation",
            'active' => 1,
            'planes_id' => 7,
            'archive' => 0,
            'user_id' => 520,
            'bot_number' => $bot_number
        ]);
        foreach ($targets as $target) {
            $tts = TargetsRecmo::create([
                'recomondations_id' => $test->id,
                'target' => $target,
            ]);
        }
        // Rami API Request
        // $rangeString = $test->entry_price;        // Split the range string by the '-' delimiter and trim whitespace
        // $rangeArray = explode('-', $rangeString);
        // $rangeArray = array_map('trim', $rangeArray);
        // // Convert the range values to floats (if needed)
        // $rangeArray = array_map('floatval', $rangeArray);
        // $targets = TargetsRecmo::where('recomondations_id', $test->id)->pluck('target')->toArray();
        // // Use map to convert string values to floats
        // $targets = array_map('floatval', $targets);
        // $data = [
        //     'recomondations_id' => $test->id,
        //     "admin" => 0,
        //     "ticker" => $test->currency,
        //     "targets" => $targets,
        //     "entry" => $rangeArray,
        //     "stoplose" => $test->stop_price,
        //     "bot_num" => $data['bot_num'],
        // ];
        // Http::post('http://51.161.128.30:5015/recomondations', $data);

        $this->sendDataAfterBot($test, $data['bot_num']);

        return response()->json([
            'success' => true,
            'data' => 'Recommendation created successfully',
        ]);
    }

    public function sendDataAfterBot($test, $bot_num)
    {
        // Rami API Request
        $rangeString = $test->entry_price;        // Split the range string by the '-' delimiter and trim whitespace
        $rangeArray = explode('-', $rangeString);
        $rangeArray = array_map('trim', $rangeArray);
        // Convert the range values to floats (if needed)
        $rangeArray = array_map('floatval', $rangeArray);
        $targets = TargetsRecmo::where('recomondations_id', $test->id)->pluck('target')->toArray();
        // Use map to convert string values to floats
        $targets = array_map('floatval', $targets);
        $data = [
            'recomondations_id' => $test->id,
            "admin" => 520,
            "ticker" => $test->currency,
            "targets" => $targets,
            "entry" => $rangeArray,
            "stoplose" => $test->stop_price,
            "bot_num" => $bot_num,
        ];
        Http::post('http://51.161.128.30:5015/recomondations', $data);
    }


    public function store(Request $request)
    {

        // dd($request->has('targets'));
        // return $request['img'];

        $request['active'] = 1;
        $request['planes_id'] = 1;
        $request['archive'] = 0;

        //   return $request;
        $targets = $request->file('img');


        $test = recommendation::create($request->except('img'));

        //   $plan = plan::find($test->planes_id);

        if ($request->hasFile('img')) {

            foreach ($request->file('img') as $file) {
                $filename = time() . '_' . Str::random(10) . $file->getClientOriginalName();
                $file->move(public_path('Advice'), $filename);
                $imageUrl = asset('Advice/' . $filename); // Assuming the images are stored in the 'Advice' directory
                //   $this->telgrame(null,null,$imageUrl);

                $tt = tagert::create([
                    'recomondations_id' => $test->id,
                    'target' => $filename,
                    // 'image_path' => $path,
                ]);
            }
            // return(public_path('Advice'), $filename);

        }

        // Store Targets
        // Need to Send array of targets
        if ($request->has('targets')) {
            $targets = $request->input('targets');

            // Check if $targets is a string, and if so, convert it to an array
            if (is_string($targets)) {
                $targets = json_decode($targets, true);
            }

            // Check if $targets is now an array before proceeding with the foreach loop

            if (is_array($targets)) {
                foreach ($targets as $target) {
                    $tts = TargetsRecmo::create([
                        'recomondations_id' => $test->id,
                        'target' => $target,
                    ]);
                }
            } else {
            }
        }

        // Rami API Request
        $rangeString = $test->entry_price;

        // Split the range string by the '-' delimiter and trim whitespace
        $rangeArray = explode('-', $rangeString);
        $rangeArray = array_map('trim', $rangeArray);

        // Convert the range values to a JSON array
        $rangeJson = json_encode($rangeArray);

        // Convert the range values to floats (if needed)
        $rangeArray = array_map('floatval', $rangeArray);
        $targets = TargetsRecmo::where('recomondations_id', $test->id)->pluck('target')->toArray();
        // Use map to convert string values to floats
        $targets = array_map('floatval', $targets);
        $data = [
            'recomondations_id' => $test->id,
            "admin" => $test->user_id,
            "ticker" => $test->currency,
            "targets" => $targets,
            "entry" => $rangeArray,
            "stoplose" => $test->stop_price,
            "bot_num" => 1,
        ];

        $plansReecommindations = $request->input('totalPlan');
        $array = array_unique($plansReecommindations);
        $array2 = array_values($array);



        if (!empty($array2)) {

            foreach ($array2 as $plansReecommindation) {

                $tts = plan_recommendation::create([
                    'recomondations_id' => $test['id'],
                    "planes_id" => $plansReecommindation,
                ]);
            }
        }

        $recom = plan::whereIn('id', $array2)
            ->orderBy('created_at')
            ->get();

        //   return 500;
        // But Return Before Send Notification

        foreach ($recom as $value) {
            event(new recommend($test, $value->nameChannel));
            $this->sendNotification($value->nameChannel);
            $this->telgrame($value->id, $request->desc, $request->title);
        }

        // event(new recommend($test, $targets, $plan->nameChannel));

        //  $this->telgrame($request->planes_id);
        Http::post('http://51.161.128.30:5015/recomondations', $data);


        return response()->json([
            'success' => true,
        ]);
    }

    public function viewsRecmo(Request $request)
    {
        $user = auth('api')->user();
        // return $user->id .$request->id ;
        $checkView = ViewsRecomendition::where('user_id', $user->id)->where('recomenditions_id', $request->id)->first();
        // return $checkView;
        if (!empty($checkView)) {
            return response()->json(['success' => false]);
        } else {
            $setCountView = ViewsRecomendition::create(
                [
                    'user_id' => $user->id,
                    'recomenditions_id' => $request->id
                ]
            );
        };


        return response()->json(['success' => true]);
    }

    public function show($id)
    {

        $user = recommendation::find($id);

        if (!$user) {
            return response()->json(['message' => 'request not found'], 404);
        }
        return RecommendationResource::make(recommendation::with(['user', 'target'])->find($id));
    }


    public function update($id, Request $request)
    {
        return $request;

        // $this->show($id);
        // $this->destroy($id);
        return $this->store($request);
    }


    public function destroy($id)
    {

        $user = recommendation::find($id);
        if (!$user) {
            return response()->json(['message' => 'Recommendation not found', 'success' => true], 200);
        }
        $target = tagert::where('recomondations_id', $id)->get();
        $target->each->delete();
        // for delete image if use delete not sofdelete
        // $this->deletePreviousImage($user->img,'Recommendation');

        // Delte Targets Recmo
        $targetRecmo = TargetsRecmo::where('recomondations_id', $id)->get();
        $targetRecmo->each->delete();

        $user->delete();


        return response()->json(['message' => 'Recommendation and associated targets deleted successfully', 'success' => true]);
    }



    function convertTextToImage($text)
    {
        // return $text->targets;
        $image = Image::make(public_path('Recommendation/logo/logo.jpg'));
        // $image = Image::make(public_path('images.png'));

        // Set the custom font file path
        $fontFile = public_path('Cairo-VariableFont_slnt,wght.ttf');

        // Set the text content without HTML-like formatting

        $Arabic = new I18N_Arabic('Glyphs');
        $content =  $Arabic->utf8Glyphs("تسمية عملة: " . $Arabic->utf8Glyphs(55)) . "\n";
        $content .=   $Arabic->utf8Glyphs('علي جمال التوصية') . "\n";
        $desc = 'طلب شراء علي العملة من السعر الحالي 1.2345';
        $words = preg_split('/\s+/u', $desc); // Split the Arabic text into an array of words

        $wordsPerLine = 6; // Number of words per line
        $wordCount = count($words);

        // Add line breaks after a certain number of words
        for ($i = 0; $i < $wordCount; $i++) {
            $content .=  $Arabic->utf8Glyphs($words[$i]) . " "; // Append the current word

            if (($i + 1) % $wordsPerLine == 0) {
                $content .= "\n"; // Add a line break after the specified number of words
            }
        }

        $content .= "\n" . 50 . " " . $Arabic->utf8Glyphs("الشراء :") . "\n";


        $targets = $text->targets;
        $count = count($targets);

        // Replace the comma with incremental numbers
        for ($i = 0; $i < $count; $i++) {
            $content .=  $targets[$i] . " " . $Arabic->utf8Glyphs("هدف" . ($i + 1) . ": ") . "\n";
        }

        $content .= "\n" . 50 . " " .  $Arabic->utf8Glyphs("وقف خسارة: ");


        // Calculate the position for each line
        $lineHeight = 30;
        $x = 210;
        $y = 100;

        // Explode the text by line breaks
        $lines = explode("\n", $content);

        // Set the font size and color
        $fontSize = 10;
        $fontColor = "#000";

        // Loop through each line and add it to the image
        foreach ($lines as $line) {
            $image->text($line, $x, $y, function ($font) use ($fontFile, $fontSize, $fontColor) {
                $font->file($fontFile);
                $font->size($fontSize);
                $font->color($fontColor);
                $font->align('center');
                $font->valign('center');
            });
            $y += $lineHeight;
        }

        $image_jpg = time() . '.' . 'jpg';
        $image->save('Recommendation/logo/' . $image_jpg);

        return $image_jpg;
    }


    public function telgrame($plan, $text, $title)
    {
        $plan = Plan::with('telegram')->where('id', $plan)->first();

        $plan->telegram->each(function ($telegram) use ($text, $title) {
            $token = $telegram->token;
            $merchant = $telegram->merchant;

            // Send text message
            $response = Http::post(
                "https://api.telegram.org/bot{$token}/sendMessage",
                [
                    'chat_id' => $merchant,
                    'text' => $title . "\n" . "\n" . $text,
                ]
            );

            // Send images
            $recomondations = recommendation::latest()->first();
            $targets = tagert::where('recomondations_id', $recomondations->id)->get();

            foreach ($targets as $target) {
                $imageUrl = asset('Advice/' . $target->target);

                $response = Http::post(
                    "https://api.telegram.org/bot{$token}/sendPhoto",
                    [
                        'chat_id' => $merchant,
                        'photo' => $imageUrl,
                        // 'caption' => $title . "\n" . "\n" . $text,
                    ]
                );
            }
        });



        // $imageUrl ='https://th.bing.com/th/id/R.4c5f4b654d397dbf388439c146fc2a43?rik=tAXLyC2QQDAW4w&riu=http%3a%2f%2fwww.tandemconstruction.com%2fsites%2fdefault%2ffiles%2fstyles%2fproject_slider_main%2fpublic%2fimages%2fproject-images%2fIMG-Student-Union_6.jpg%3fitok%3dSIO_SJym&ehk=J7Rf60RWZAMlFREdj%2f7pdLWdGMn%2bS07tQsou0pZGgIA%3d&risl=&pid=ImgRaw&r=0';

        // $response = Http::post(
        //     "https://api.telegram.org/bot{$token}/sendPhoto",
        //     [
        //         'chat_id' => $merchant,
        //         'photo' => $imageUrl,
        //         'caption' => 'Image caption',
        //     ]
        // );

    }


    public function adminPlan()
    {
        $user = auth('api')->user()->load('role');

        if ($user->state == 'admin') {
            $planIds = $user->role->pluck('pivot')->pluck('plan_id');
            return PlanResource::collection(plan::whereIn('id', $planIds)
                ->get());
        } else {
            return RecommendationResource::collection(recommendation::orderBy('created_at', 'desc')
                ->where('archive', 0)
                ->with(['user', 'target'])
                ->get());
        }
    }

    public function sendNotification($plan)
    {
        $serverKey = 'AAAAdOBidSQ:APA91bGf83SZcbSaGfybST4Z7y1RHqHV0h1yKgMlB-p09IErYNDo2HXkYiq5aW-iVjgDMQaSinWQNbnJF7vs5m-JPMoILRjoX8kdezLNj54i8gcevawlskPuckqlI9NIxyMzAQKkADWk'; // Replace with your Firebase server key

        $client = new Client([
            'base_uri' => 'https://fcm.googleapis.com/fcm/',
            'headers' => [
                'Authorization' => 'key=' . $serverKey,
                'Content-Type' => 'application/json',
            ],
        ]);

        $message = [
            'condition' => "'all' in topics",
            'notification' => [
                'title' => $plan,
                'body' => '  يوجد الان توصية جديدة    ',
            ],
        ];

        $response = $client->post('send', [
            'json' => $message,
        ]);

        if ($response->getStatusCode() === 200) {
            return response()->json(['message' => 'Notification sent to all users.']);
        } else {
            return response()->json(['error' => 'Failed to send notification.'], $response->getStatusCode());
        }
    }

    // getAllRecomendationPlan
    public function getAllRecomendationPlan($id)
    {
        $user = auth('api')->user()->load('role');

        if ($user->state == 'super_admin') {
            $plan_recmo_ids = plan_recommendation::where('planes_id', $id)->get()->pluck('recomondations_id')->toArray();

            $recom = RecommendationResource::collection(recommendation::orderBy('created_at', 'desc')
                ->whereIn('id', $plan_recmo_ids)
                ->with(['user', 'target', 'Recommindation_Plan'])
                ->paginate(20));

            return response()->json([
                'data' => $recom,
                'meta' => [
                    'current_page' => $recom->currentPage(),
                    'last_page' => $recom->lastPage(),
                    'total' => $recom->total(),
                    'next_page' => $recom->nextPageUrl(),
                ],
            ]);
        }
    }
}
