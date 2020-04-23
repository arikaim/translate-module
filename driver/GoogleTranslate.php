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

/**
 * Google translate driver class
 */
class GoogleTranslate implements DriverInterface, TranslateInterface
{   
    use Driver;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setDriverParams('google','translate','GoogleTranslate','Driver for Goolge translate service');      
    }

    /**
     * Translate text
     *
     * @param string $text
     * @param string $sourceLanguage
     * @param string $targetLanguage
     * @return string|false
     */
    public function translate($text, $sourceLanguage, $targetLanguage)
    {
        $result = $this->instance->translate($text,[
            'source' => $sourceLanguage,
            'target' => $targetLanguage
        ]);

        return $result['text'];
    }

    /**
     * Detect language
     *
     * @param string $text
     * @return string|false
     */
    public function detectLanguage($text)
    {
        $result = $this->instance->detectLanguage($text);

        return $result['languageCode'];
    }

    /**
     * Initialize driver
     *
     * @return void
     */
    public function initDriver($properties)
    {
        $apiKey = $properties->getValue('api_key');
        $this->instance = new Google\Cloud\Translate\V2\TranslateClient([
            'key' => $apiKey
        ]);        
    }

    /**
     * Create driver config properties array
     *
     * @param Arikaim\Core\Collection\Properties $properties
     * @return array
     */
    public function createDriverConfig($properties)
    {
        $properties->property('api_key',function($property) {
            $property
                ->title('Api Key')
                ->type('text')
                ->default('')
                ->required(true);
        });        
    }
}
