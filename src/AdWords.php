<?php

namespace SchulzeFelix\AdWords;

use Google\AdsApi\AdWords\v201705\o\AttributeType;
use Google\AdsApi\AdWords\v201705\o\RequestType;
use Google\AdsApi\AdWords\v201705\o\TargetingIdeaService;
use Google\AdsApi\Common\Util\MapEntries;
use Illuminate\Support\Collection;
use SchulzeFelix\AdWords\Responses\Keyword;
use SchulzeFelix\AdWords\Responses\MonthlySearchVolume;

class AdWords
{
    const CHUNK_SIZE = 700;

    /**
     * @var TargetingIdeaService
     */
    private $service;

    /** @var bool */
    protected $withTargetedMonthlySearches = false;

    /** @var int|null */
    protected $language = null;

    /** @var int|null */
    protected $location = null;

    /**
     * AdWords constructor.
     *
     * @param AdWordsService $service
     */
    public function __construct(AdWordsService $service)
    {
        $this->service = $service;
    }

    /**
     * @param array $keywords
     *
     * @return Collection
     */
    public function searchVolumes(array $keywords)
    {
        $keywords = $this->prepareKeywords($keywords);
        $requestType = RequestType::STATS;

        $searchVolumes = new Collection();
        $chunks = array_chunk($keywords, self::CHUNK_SIZE);

        foreach ($chunks as $index => $keywordChunk) {
            $results = $this->service->performQuery($keywordChunk, $requestType, $this->language, $this->location, $this->withTargetedMonthlySearches);
            if ($results->getEntries() !== null) {
                foreach ($results->getEntries() as $targetingIdea) {
                    $keyword = $this->extractKeyword($targetingIdea);
                    $searchVolumes->push($keyword);
                }
            }
        }

        $missingKeywords = array_diff($keywords, $searchVolumes->pluck('keyword')->toArray());

        foreach ($missingKeywords as $missingKeyword) {
            $missingKeywordInstance = new Keyword([
                'keyword'       => $missingKeyword,
                'search_volume' => null,
                'cpc'           => null,
                'competition'   => null,
            ]);

            if ($this->withTargetedMonthlySearches) {
                $missingKeywordInstance->targeted_monthly_searches = null;
            }

            $searchVolumes->push($missingKeywordInstance);
        }

        return $searchVolumes;
    }

    public function keywordIdeas($keyword)
    {

    }
    /**
     * Include Targeted Monthly Searches.
     *
     * @return $this
     */
    public function withTargetedMonthlySearches()
    {
        $this->withTargetedMonthlySearches = true;

        return $this;
    }

    /**
     * Add Language Search Parameter.
     *
     * @return $this
     */
    public function language($language = null)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Add Location Search Parameter.
     *
     * @return $this
     */
    public function location($location = null)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * @return TargetingIdeaService
     */
    public function getTargetingIdeaService(): TargetingIdeaService
    {
        return $this->service->getTargetingIdeaService();
    }

    /**
     * Private Functions.
     */
    private function prepareKeywords(array $keywords)
    {
        $keywords = array_map('trim', $keywords);
        $keywords = array_map('mb_strtolower', $keywords);
        $keywords = array_filter($keywords);
        $keywords = array_unique($keywords);
        $keywords = array_values($keywords);

        return $keywords;
    }

    /**
     * @param $targetingIdea
     *
     * @return Keyword
     */
    private function extractKeyword($targetingIdea)
    {
        $data = MapEntries::toAssociativeArray($targetingIdea->getData());
        $keyword = $data[AttributeType::KEYWORD_TEXT]->getValue();
        $search_volume =
            ($data[AttributeType::SEARCH_VOLUME]->getValue() !== null)
                ? $data[AttributeType::SEARCH_VOLUME]->getValue() : 0;

        $average_cpc =
            ($data[AttributeType::AVERAGE_CPC]->getValue() !== null)
                ? $data[AttributeType::AVERAGE_CPC]->getValue()->getMicroAmount() : 0;
        $competition =
            ($data[AttributeType::COMPETITION]->getValue() !== null)
                ? $data[AttributeType::COMPETITION]->getValue() : 0;

        $result = new Keyword([
            'keyword'                   => $keyword,
            'search_volume'             => $search_volume,
            'cpc'                       => $average_cpc,
            'competition'               => $competition,
            'targeted_monthly_searches' => null,
        ]);

        if ($this->withTargetedMonthlySearches) {
            $targeted_monthly_searches =
                ($data[AttributeType::TARGETED_MONTHLY_SEARCHES]->getValue() !== null)
                    ? $data[AttributeType::TARGETED_MONTHLY_SEARCHES]->getValue() : 0;
            $targetedMonthlySearches = collect($targeted_monthly_searches)
                ->transform(function ($item, $key) {
                    return new MonthlySearchVolume([
                        'year'  => $item->getYear(),
                        'month' => $item->getMonth(),
                        'count' => $item->getCount(),
                    ]);
                });

            $result->targeted_monthly_searches = $targetedMonthlySearches;
        }

        return $result;
    }
}
