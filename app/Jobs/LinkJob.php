<?php

namespace App\Jobs;

use App\Http\Crawler\Crawler;
use App\Models\Link;
use App\Models\Site;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class LinkJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private Link $link, private Site $site)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->site->last_check = date('Y-m-d H:i:s');
        $this->site->status = 'Crawler em andamento';
        $this->site->update();

        $crawler = Crawler::crawler($this->link->url);

        if (count($crawler['links']) > 0) {

            foreach ($crawler['links'] as $k => $v) {

                $checkLink = Link::where('url', $v['url'])->where('site_id', $this->site->id)->first();

                if ($checkLink) {
                    $checkLink->last_check = date('Y-m-d H:i:s');
                    $checkLink->update();
                } else if (filter_var($v['url'], FILTER_VALIDATE_URL)) {
                    $newLink = Link::create([
                        'page' => $v['page'],
                        'url' => $v['url'],
                        'title' => $v['title'],
                        'status' => 'Aguardando',
                        'site_id' => $this->site->id,
                        'last_check' => date('Y-m-d H:i:s')
                    ]);

                    $newLink->save();

                    if ($this->extractDomain($v['url']) == $this->extractDomain($this->site->url)) {
                        LinkJob::dispatch($newLink, $this->site);
                    } else {
                        $newLink->status = 'Finalizado';
                        $newLink->update();
                    }
                }
            }
        }

        $this->link->last_check = date('Y-m-d H:i:s');
        $this->link->status = 'Finalizado';
        $this->link->update();

        $this->site->last_check = date('Y-m-d H:i:s');
        $this->site->status = 'Finalizado';
        $this->site->update();
    }

    private function extractDomain($url)
    {
        return str_replace('www.', '', parse_url($url, PHP_URL_HOST));
    }
}
