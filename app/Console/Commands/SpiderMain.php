<?php namespace App\Console\Commands;

use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use Sunra\PhpSimple\HtmlDomParser;

use Illuminate\Console\Command;

class SpiderMain extends Command
{

    private $totalPageCount;
    private $counter        = 1;
    private $concurrency    = 7;  // 同时并发抓取
    private $users = ['CycloneAxe', 'appleboy', 'Aufree', 'lifesign',
        'overtrue', 'zhengjinghua', 'NauxLiu'];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spider:main';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //初始化
        //echo file_get_contents('https://laser.ofweek.com/CATList-2400-8100-laser.html');

        //return true;

        $header = [
            'headers'=>[
                'Accept'=> 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
                'Accept-Encoding'=> 'gzip, deflate, br',
                'Accept-Language'=> 'zh-CN,zh;q=0.9',
                'Cache-Control'=>'max-age=0',
                'Connection'=>'keep-alive',
                'Host'=>'weixin.sogou.com',
                'Upgrade-Insecure-Requests'=>'1',
                'Referer'=>'https://weixin.sogou.com/weixin?type=2&s_from=input&query=%E5%85%89%E7%94%B5%E6%B1%87&ie=utf8&_sug_=n&_sug_type_=',
                'User-Agent'=>'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36',
            ]
        ];
        $client = new Client();

        $promise = $client->requestAsync('GET', 'http://img.wehdz.gov.cn/ggxw/ggxw/index.shtml');
        $promise->then(
            function (ResponseInterface $res) {
                echo $res->getStatusCode() . "\n";
                $res =  $res->getBody()->getContents();

                $dom = HtmlDomParser::str_get_html( $res );

                $list = $dom->find('div.tzgg-right-block li');

                foreach ($list as $e) {
                    //$this->line(trim($e->plaintext));

                    try {
                        $this->line(trim($e->find('a',0)->plaintext));
                        $this->line(trim($e->find('a',0)->href));
                        $this->line(trim($e->find('span',0)->plaintext));
                        $this->line(1);
                    }catch(\Exception $e) {

                    }
                    
                    //$this->line(trim($e->href));
                }
                //var_dump($res);

                $this->line(gettype($res));
                $this->line('----------');

                $this->line('-----结束-----');
            },
            function (RequestException $e) {
                //echo $e->getMessage() . "\n";
                echo $e->getResponse()->getStatusCode() . "\n";
                //echo $e->getRequest()->getMethod();
            }
        );
        $promise->wait();
    }
    public function countedAndCheckEnded()
    {
        if ($this->counter < $this->totalPageCount){
            $this->counter++;
            return;
        }
        $this->info("请求结束！");
    }
}
