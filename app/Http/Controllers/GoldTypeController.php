<?php

namespace App\Http\Controllers;

use App\Models\GoldType;
use Illuminate\Console\View\Components\Info;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use LINE\LINEBot;
use LINE\LINEBot\Constant\HTTPHeader;
use LINE\LINEBot\Event\MessageEvent;
use LINE\LINEBot\Event\MessageEvent\StickerMessage;
use LINE\LINEBot\Event\MessageEvent\TextMessage;
use LINE\LINEBot\Exception\InvalidEventRequestException;
use LINE\LINEBot\Exception\InvalidSignatureException;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use LINE\LINEBot\MessageBuilder\StickerMessageBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;

class GoldTypeController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return GoldType::all();
    }

    public function viewLiff(Request $r)
    {
        $goldtypes = GoldType::all();
        return view("viewLiff", ["goldtypes" => $goldtypes]);
    }

    public function replyMessage(Request $r) {


        $httpClient = new CurlHTTPClient(config("linebot.channel_access_token"));
        $bot = new LINEBot($httpClient, ['channelSecret' => config("linebot.channel_secret")]);

        $signature = $r->header(HTTPHeader::LINE_SIGNATURE);
        if (empty($signature)) {
            abort(400);
        }

        try {
            $events = $bot->parseEventRequest($r->getContent(), $signature);
        } catch (InvalidSignatureException $e) {
            abort(400);
        } catch (InvalidEventRequestException $e) {
            abort(400);
        }


        foreach ($events as $event) {
            $replyToken = $event->getReplyToken();
            if (!($event instanceof MessageEvent)) {
                $response = $bot->replyText($replyToken, "Not a message");
                continue;
            }

            if (!($event instanceof TextMessage) and !($event instanceof StickerMessage)) {
                $response = $bot->replyText($replyToken, "Not a text or a sticker");
                continue;
            }

            $replyText = "";
            $obj = null;

            $userID = $event->getUserId();
            $profile = $bot->getProfile($userID);
            $profile = $profile->getJSONDecodedBody();
            $displayName = $profile['displayName'];
            $pictureUrl = $profile['pictureUrl'];
            $statusMessage = $profile['statusMessage'];

            if ($event instanceof TextMessage) {
                $inputText = $event->getText();

                $textResponses = [
                    "give me 10 scores",
                    "hi",
                    "hello"
                ];

                if ($inputText === $textResponses[0]) {
                    $obj = GoldType::inRandomOrder()->get()->first();

                    if ($obj != null) {
                        $multiMessageBuilder = new MultiMessageBuilder();
                        $multiMessageBuilder->add(new TextMessageBuilder("ID: ".$obj->id));
                        $multiMessageBuilder->add(new TextMessageBuilder("Name: ".$obj->name));
                        $multiMessageBuilder->add(new TextMessageBuilder("Size: ".$obj->size));
                        $response = $bot->replyMessage($replyToken, $multiMessageBuilder);
                    }
                }
                else if ($inputText === $textResponses[1]) {
                    $response = $bot->replyText($replyToken, "hello");
                }
                else if ($inputText === $textResponses[2]) {
                    $response = $bot->replyText($replyToken, "hi");
                }
                else {
                    $inputTextSplit = explode(",", $inputText);
                    if ($inputTextSplit[0] === "add") {

                        $gold = new GoldType();
                        $gold->name = $inputTextSplit[1].trim();
                        $gold->size = $inputTextSplit[2].trim();
                        $gold->save();
                        $response = $bot->replyText($replyToken, "added ".$gold->name);
                        Log::info($gold);
                    } else {
                        $response = $bot->replyText($replyToken, "input text is not a valid response");
                    }
                }
            }

            if ($event instanceof StickerMessage) {
                $packageID = $event->getPackageId();
                $stickerID = $event->getStickerId();

                $multiMessageBuilder = new MultiMessageBuilder();
                $multiMessageBuilder->add(new TextMessageBuilder("Package ID: ".$packageID));
                $multiMessageBuilder->add(new TextMessageBuilder("Sticker ID: ".$stickerID));
                $response = $bot->replyMessage($replyToken, $multiMessageBuilder);
                continue;
            }

            return [];


        }
    }

    public function pushM(Request $request) {
        $httpClient = new CurlHTTPClient(config("linebot.channel_access_token"));
        $bot = new LINEBot($httpClient, ['channelSecret' => config("linebot.channel_secret")]);

        Log::info($request);
        $userID = $request->get("userIdForm");
        $message = $request->get("message");
        $bot->pushMessage($userID, new TextMessageBuilder($message));
        $bot->pushMessage($userID, new StickerMessageBuilder("446", "1993"));

        $goldtypes = GoldType::all();
        return redirect()->back();
    }

    public function pushG(Request $request) {
        $httpClient = new CurlHTTPClient(config("linebot.channel_access_token"));
        $bot = new LINEBot($httpClient, ['channelSecret' => config("linebot.channel_secret")]);

        Log::info($request);
        $name = $request->get("nameForm");
        if ($name == null) {
            $name = $request->get("name");
        }
        $size = $request->get("sizeForm");
        if ($size == null) {
            $size = $request->get("size");
        }

        $gold = new GoldType();
        $gold->name = $name;
        $gold->size = $size;
        $gold->save();

//        $userID = "U5659f11cbc78a596f3d088cee20b72e5";
        $bot->pushMessage($userID, new TextMessageBuilder("someone added a gold type ".$gold->name." with size ".$gold->size));
        $bot->pushMessage($userID, new StickerMessageBuilder("446", "1992"));

        $goldtypes = GoldType::all();
        return redirect()->back();
    }

    public function pushPM(Request $request) {
        $httpClient = new CurlHTTPClient(config("linebot.channel_access_token"));
        $bot = new LINEBot($httpClient, ['channelSecret' => config("linebot.channel_secret")]);

        Log::info($request);
        $name = $request->get("name");
        $size = $request->get("size");

        $gold = new GoldType();
        $gold->name = $name;
        $gold->size = $size;
        $gold->save();

//        $userID = "U5659f11cbc78a596f3d088cee20b72e5";
        $bot->pushMessage($userID, new TextMessageBuilder("someone added a gold type ".$gold->name." with size ".$gold->size));
        $bot->pushMessage($userID, new StickerMessageBuilder("446", "1992"));

        $goldtypes = GoldType::all();
        return redirect()->back();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\GoldType  $goldType
     * @return \Illuminate\Http\Response
     */
    public function show(GoldTypeController $goldType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\GoldType  $goldType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GoldTypeController $goldType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GoldType  $goldType
     * @return \Illuminate\Http\Response
     */
    public function destroy(GoldTypeController $goldType)
    {
        //
    }
}
