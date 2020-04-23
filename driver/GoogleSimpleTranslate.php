<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Modules\Translate\Driver;

use Arikaim\Core\Arikaim;
use Arikaim\Core\Driver\Traits\Driver;
use Arikaim\Core\Interfaces\Driver\DriverInterface;
use Arikaim\Modules\Translate\TranslateInterface;
use Arikaim\Core\Utils\Curl;

/**
 * Google simple translate driver class
 */
class GoogleSimpleTranslate implements DriverInterface, TranslateInterface
{   
    use Driver;

    /**
     * Api base url
     *
     * @var string
     */
    protected $baseUrl;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setDriverParams('google-simple','translate','GoogleSimpleTranslate','Goolge simple translatation service');      
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
        $sourceLanguage= (empty($sourceLanguage) == true) ? 'auto': $sourceLanguage;
        $text = urlencode($text);
        $url = $this->baseUrl . "single?client=gtx&sl=" . $sourceLanguage . "&tl=" . $targetLanguage . "&dt=t&q=" . $text;

        $json = Curl::get($url);
        $result = json_decode($json,true);

        return (isset($result[0][0][0]) == true) ? $result[0][0][0] : false;
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
        return [];
    }

    /**
     * Initialize driver
     *
     * @return void
     */
    public function initDriver($properties)
    {
        $this->baseUrl = $properties->getValue('baseUrl');        
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
                ->default('https://translate.googleapis.com/translate_a/')
                ->value('https://translate.googleapis.com/translate_a/')
                ->readonly(true);              
        });        
    }
}
