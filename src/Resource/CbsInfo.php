<?php

declare(strict_types=1);

namespace H22k\MngKargo\Resource;

use GuzzleHttp\Exception\GuzzleException;
use H22k\MngKargo\Http\Payload;
use H22k\MngKargo\Model\Response\CbsInfo\CityResponse;
use H22k\MngKargo\Model\Response\CbsInfo\DistrictResponse;
use H22k\MngKargo\Model\Response\CbsInfo\Object\City;
use H22k\MngKargo\Service\ResponseTransformerService;

/**
 * Şehirler, ilçeler mahalleler gibi coğrafik bilgileri getirir ve servis dışı ve mobil olan alanları listeler.
 * Gets geographic informations like citites, districts, neighborhoods and lists out of service areas and mobile areas.
 *
 * @see https://apizone.mngkargo.com.tr/tr/product/3066/api/1741#/CBSInfoAPI_10/overview
 */
class CbsInfo extends AbstractResource
{
    public const PATH_PREFIX = 'cbsinfoapi';

    public const GET_CITIES_URI = 'getcities';

    public const GET_DISTRICTS_URI = 'getdistricts';

    /**
     * Türkiye'deki şehirlerin ismini ve kodlarını getirir.
     * Gets city names and city codes of Turkey.
     *
     * @throws GuzzleException
     *
     * @see https://sandbox.mngkargo.com.tr/tr/product/2134/api/2121#/CBSInfoAPI_10/operation/%2Fgetcities/get
     */
    public function getCities(): CityResponse
    {
        $payload = Payload::from(self::PATH_PREFIX . '/' . self::GET_CITIES_URI);

        /**
         * @var ResponseTransformerService<array{code: string, name: string}> $responseTransformerService
         */
        $responseTransformerService = $this->client->get($payload);

        return CityResponse::from($responseTransformerService);
    }

    /**
     * İlgili şehir koduna ait ilçe isimlerini ve kodlarını getirir.
     * Gets district names and district codes of the relevant city code.
     *
     * @throws GuzzleException
     *
     * @see https://sandbox.mngkargo.com.tr/tr/product/2134/api/2121#/CBSInfoAPI_10/operation/%2Fgetdistricts%2F{cityCode}/get
     */
    public function getDistricts(City $city): DistrictResponse
    {
        $payload = Payload::from(self::PATH_PREFIX . '/' . self::GET_DISTRICTS_URI . '/1');

        $responseTransformerService = $this->client->get($payload);

        return DistrictResponse::from($responseTransformerService, $city);
    }
}
