<?php

namespace App\Console\Commands;

use App\Models\PageRank;
use Illuminate\Console\Command;

class UpdatePageRanks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-page-ranks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates page ranks';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $page = 1;

        do {
            $pages = PageRank::query()->limit(100)->offset(($page - 1) * 100)->get();
            $page++;

            if (count($pages) > 0) {

                $domainsOutput = $this->getDomainsDataFromApi($pages);

                foreach ($domainsOutput->response as $domainFromApi) {
                    if (is_numeric($domainFromApi->page_rank_integer)) {
                        PageRank::query()->where('root_domain', $domainFromApi->domain)->update([
                            'rank' => $domainFromApi->page_rank_integer,
                        ]);
                    }
                }
            }

        } while (count($pages) > 0);
    }

    protected function getDomainsDataFromApi($pages)
    {
        $url = 'https://openpagerank.com/api/v1.0/getPageRank';

        $domains = $pages->pluck('root_domain')->toArray();

        $query = http_build_query([
            'domains' => $domains,
        ]);
        $url = $url .'?'. $query;
        $ch = curl_init();
        $headers = ['API-OPR: ws48csg0cow0w4kg0gck4oc8wo80kcg8w0g4s08k']; //@todo: we need to move it to .env file
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec ($ch);
        curl_close ($ch);

        return json_decode($output);
    }
}
