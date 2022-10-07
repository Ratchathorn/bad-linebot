<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use LINE\LINEBot;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot\MessageBuilder\StickerMessageBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;

class message extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'message
                            {string : the string to send message}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'sends a message to a predetermined line id';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $httpClient = new CurlHTTPClient(config("linebot.channel_access_token"));
        $bot = new LINEBot($httpClient, ['channelSecret' => config("linebot.channel_secret")]);

        $userID = "U5659f11cbc78a596f3d088cee20b72e5";
        $message = $this->argument('string');
        $bot->pushMessage($userID, new TextMessageBuilder($message));
        $bot->pushMessage($userID, new StickerMessageBuilder("446", "1991"));
        return 0;
    }
}
