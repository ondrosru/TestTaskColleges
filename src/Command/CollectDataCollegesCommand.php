<?php

namespace App\Command;

use App\Parser\CollegesListPageCountParser;
use App\Parser\CollegesListParser;
use App\Parser\CollegesProfileParser;
use App\Service\CollegeService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CollectDataCollegesCommand extends Command
{
    protected static $defaultName = 'app:collect-data-colleges';
    private string $url = 'https://www.princetonreview.com/college-search?ceid=cp-1022984';

    private CollegeService $collegeService;

    public function __construct(CollegeService $collegeService)
    {
        $this->collegeService = $collegeService;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:collect-data-colleges')
            ->setDescription('Collects data for all colleges from the list');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $url = $this->url;
        $baseUrl = $this->getBaseUrl($url);
        $collegesListPageCountParser = new CollegesListPageCountParser();
        $pageCount = $collegesListPageCountParser->parse($url);
        $urls = [$url];
        for ($i = 2; $i <= $pageCount; $i++) {
            $urls[] = $url . '&page=' . $i;
        }
        $collegesData = $this->getCollegesListData($urls);
        $count = count($collegesData);
        $output->writeln('[profiles]');
        for ($i = 0; $i < count($collegesData); $i++) {
            $profile_url = $baseUrl . $collegesData[$i]['profile_relative_link'];
            $profileData = $this->getCollegeProfileDate($profile_url);
            $collegesData[$i]['address'] = $profileData['address'];
            $collegesData[$i]['website_url'] = $profileData['website_url'];
            $collegesData[$i]['phone'] = $profileData['phone'];
            $output->writeln($count . '|' . ($i + 1));
        }
        $this->updateCollegesData($collegesData);
        return Command::SUCCESS;
    }

    private function updateCollegesData(array $collegesData = [])
    {
        $updated_ids = array();
        foreach ($collegesData as $collegeData) {
            $collegeFromDB = $this->collegeService->getCollegeByNameAndCity($collegeData['name'], $collegeData['city']);
            if ($collegeFromDB === null) {
                $updated_ids[] = $this->collegeService->createCollege(
                    $collegeData['name'],
                    $collegeData['address'],
                    $collegeData['city'],
                    $collegeData['state'],
                    $collegeData['img_url'],
                    $collegeData['phone'],
                    $collegeData['website_url']
                );
            } else {
                $updated_ids[] = $collegeFromDB->getId();
                $this->collegeService->updateCollege(
                    $collegeFromDB->getId(),
                    $collegeData['name'],
                    $collegeData['address'],
                    $collegeData['city'],
                    $collegeData['state'],
                    $collegeData['img_url'],
                    $collegeData['phone'],
                    $collegeData['website_url']
                );
            }
        }
        $collegesDB = $this->collegeService->getAll();
        foreach ($collegesDB as $collegeDB) {
            if (!in_array($collegeDB->getId(), $updated_ids)) {
                $this->collegeService->deleteCollege($collegeDB->getId());
            }
        }
    }

    private function getCollegesListData(array $urls = [])
    {
        $collegesListParser = new CollegesListParser();
        return $collegesListParser->parse($urls);
    }

    private function getCollegeProfileDate(string $url)
    {
        $collegesListParser = new CollegesProfileParser();
        return $collegesListParser->parse($url);
    }

    private function getBaseUrl($url): string
    {
        $parsedUrl = parse_url($url);
        $scheme = isset($parsedUrl['scheme']) ? $parsedUrl['scheme'] . '://' : '';
        $host = $parsedUrl['host'] ?? '';
        return "$scheme$host";
    }
}
