<?php

namespace SchulzeFelix\AdWords;

use Google\AdsApi\AdWords\v201809\cm\ApiException;
use Google\AdsApi\AdWords\v201809\cm\Language;
use Google\AdsApi\AdWords\v201809\cm\Location;
use Google\AdsApi\AdWords\v201809\cm\NetworkSetting;
use Google\AdsApi\AdWords\v201809\cm\Paging;
use Google\AdsApi\AdWords\v201809\o\AttributeType;
use Google\AdsApi\AdWords\v201809\o\IdeaTextFilterSearchParameter;
use Google\AdsApi\AdWords\v201809\o\IdeaType;
use Google\AdsApi\AdWords\v201809\o\LanguageSearchParameter;
use Google\AdsApi\AdWords\v201809\o\LocationSearchParameter;
use Google\AdsApi\AdWords\v201809\o\NetworkSearchParameter;
use Google\AdsApi\AdWords\v201809\o\RelatedToQuerySearchParameter;
use Google\AdsApi\AdWords\v201809\o\TargetingIdeaSelector;
use Google\AdsApi\AdWords\v201809\o\TargetingIdeaService;

class AdWordsService
{
    const PAGE_LIMIT = 700;

    const MAX_RETRIES = 10;

    /** @var TargetingIdeaService */
    protected $targetingIdeaService;

    public function __construct(TargetingIdeaService $targetingIdeaService)
    {
        $this->targetingIdeaService = $targetingIdeaService;
    }

    /**
     * Query the Google AdWords TargetingIdeaService with given parameters.
     *
     * @param array $keywords
     * @param $requestType
     * @param $language
     * @param $location
     * @param bool $withTargetedMonthlySearches
     * @param $included
     * @param $excluded
     *
     * @throws ApiException
     *
     * @return \Google\AdsApi\AdWords\v201809\o\TargetingIdeaPage
     */
    public function performQuery(array $keywords, $requestType, $language = null, $location = null, $withTargetedMonthlySearches = false, $included = null, $excluded = null)
    {

        // Create selector.
        $selector = new TargetingIdeaSelector();
        $selector->setRequestType($requestType);
        $selector->setIdeaType(IdeaType::KEYWORD);
        $selector->setRequestedAttributeTypes($this->getRequestedAttributeTypes($withTargetedMonthlySearches));
        $selector->setPaging(new Paging(0, self::PAGE_LIMIT));
        $selector->setSearchParameters($this->getSearchParameters($keywords, $language, $location, $included, $excluded));

        return (new ExponentialBackoff(10))->execute(function () use ($selector) {
            return $this->targetingIdeaService->get($selector);
        });
    }

    /**
     * @return TargetingIdeaService
     */
    public function getTargetingIdeaService()
    {
        return $this->targetingIdeaService;
    }

    /**
     * @param bool $withTargetedMonthlySearches
     *
     * @return array
     */
    private function getRequestedAttributeTypes($withTargetedMonthlySearches = false)
    {
        if ($withTargetedMonthlySearches) {
            return [
                AttributeType::KEYWORD_TEXT,
                AttributeType::SEARCH_VOLUME,
                AttributeType::COMPETITION,
                AttributeType::AVERAGE_CPC,
                AttributeType::TARGETED_MONTHLY_SEARCHES,
            ];
        }

        return [
            AttributeType::KEYWORD_TEXT,
            AttributeType::SEARCH_VOLUME,
            AttributeType::COMPETITION,
            AttributeType::AVERAGE_CPC,
        ];
    }

    /**
     * @param array $keywords
     * @param $languageId
     * @param $locationId
     * @param $included
     * @param $excluded
     *
     * @return array
     */
    private function getSearchParameters(array $keywords, $languageId, $locationId, $included, $excluded)
    {
        $searchParameters = [];

        //Create Language Parameter
        if (! is_null($languageId)) {
            $languageParameter = new LanguageSearchParameter();
            $language = new Language();
            $language->setId($languageId);
            $languageParameter->setLanguages([$language]);
            $searchParameters[] = $languageParameter;
        }

        //Create Location Parameter
        if (! is_null($locationId)) {
            $locationParameter = new LocationSearchParameter();
            $location = new Location();
            $location->setId($locationId);
            $locationParameter->setLocations([$location]);
            $searchParameters[] = $locationParameter;
        }

        //Network Settings
        $networkSetting = new NetworkSetting();
        $networkSetting->setTargetGoogleSearch(true);
        $networkSetting->setTargetSearchNetwork(false);
        $networkSetting->setTargetContentNetwork(false);
        $networkSetting->setTargetPartnerSearchNetwork(false);

        $networkSearchParameter = new NetworkSearchParameter();
        $networkSearchParameter->setNetworkSetting($networkSetting);
        $searchParameters[] = $networkSearchParameter;

        // Create related to query search parameter.
        $relatedToQuerySearchParameter = new RelatedToQuerySearchParameter();
        $relatedToQuerySearchParameter->setQueries($keywords);
        $searchParameters[] = $relatedToQuerySearchParameter;

        if (! is_null($included) || ! is_null($excluded)) {
            $ideaTextFilterSearchParameter = new IdeaTextFilterSearchParameter();
            if (! is_null($included)) {
                $ideaTextFilterSearchParameter->setIncluded($included);
            }
            if (! is_null($excluded)) {
                $ideaTextFilterSearchParameter->setExcluded($excluded);
            }
            $searchParameters[] = $ideaTextFilterSearchParameter;
        }

        return $searchParameters;
    }
}
