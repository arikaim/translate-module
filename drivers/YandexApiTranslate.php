<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Modules\Translate\Drivers;

use Arikaim\Core\Arikaim;
use Arikaim\Core\Driver\Traits\Driver;
use Arikaim\Core\Interfaces\Driver\DriverInterface;
use Arikaim\Modules\Translate\TranslateInterface;
use Arikaim\Core\Utils\Curl;

/**
 * YandexApi translate driver class
 */
class YandexApiTranslate implements DriverInterface, TranslateInterface
{   
    use Driver;

    /**
     * Api base url
     *
     * @var string
     */
    protected $baseUrl;

    /**
     * Api Key
     *
     * @var string
     */
    protected $apiKey;

    /**
     * Error message
     *
     * @var string
     */
    protected $errorMessage = '';

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setDriverParams('yandex','translate','YandexApiTranslate','Yandex Translate service');      
    }

    /**
     * Translate text
     *
     * @param string $text 
     * @param string $targetLanguage
     * @param string|null $sourceLanguage
     * @return string|false
     */
    public function translate($text, $targetLanguage, $sourceLanguage = null)
    {
        $sourceLanguage = (empty($sourceLanguage) == true) ? 'en': $sourceLanguage;
        $text = \urlencode($text);
        $url = $this->baseUrl . "translate?lang=" . $sourceLanguage . "-" . $targetLanguage;
    
        $data = [
            'key'  => $this->apiKey,
            'text' => $text
        ];
        $data = \http_build_query($data);      
        $json = Curl::post($url,$data,[
            'Accept: */*',
            'Content-Type: application/x-www-form-urlencoded'
        ]);
     
        $result = \json_decode($json,true);
        if (isset($result['code']) == true) {
            $this->errorMessage = ($result['code'] == 402) ? $result['message'] : '';
        }
       
        return (isset($result['text'][0]) == true) ? \urldecode($result['text'][0]) : false;
    }

    /**
     * Get error message
     *
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * Detect language
     *
     * @param string $text
     * @return string|false
     */
    public function detectLanguage($text)
    {
        return false;
    }

    /**
     * Get supported languages
     *
     * @return array
     */
    public function getLanguages()
    {
        $url = $this->baseUrl . "getLangs?ui=en";
        $data = [
            'key'  => $this->apiKey           
        ];
        $data = \http_build_query($data);  

        $json = Curl::post($url,$data,[
            'Accept: */*',
            'Content-Type: application/x-www-form-urlencoded'
        ]);
        $result = \json_decode($json,true);
       
        return (isset($result['langs']) == true) ? $result['langs'] : [];       
    }

    /**
     * Initialize driver
     *
     * @return void
     */
    public function initDriver($properties)
    {
        $this->baseUrl = $properties->getValue('baseUrl');    
        $this->apiKey = $properties->getValue('key');        
    }

    /**
     * Create driver config properties array
     *
     * @param Arikaim\Core\Collection\Properties $properties
     * @return array
     */
    public function createDriverConfig($properties)
    {
        $properties->property('baseUrl',function($property) {
            $property
                ->title('Base Url')
                ->type('text')
                ->default('https://translate.yandex.net/api/v1.5/tr.json/')
                ->value('https://translate.yandex.net/api/v1.5/tr.json/')
                ->readonly(true);              
        }); 
        
        $properties->property('key',function($property) {
            $property
                ->title('Api Key')
                ->type('text')              
                ->value('');                 
        }); 
    }
}
