<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Modules\Translate;

/**
 * Translate interface
 */
interface TranslateInterface
{    
    /**
     * Translate text
     *
     * @param string $text    
     * @param string $targetLanguage
     * @param string|null $sourceLanguage
     * @return string|false
     */
    public function translate($text, $targetLanguage, $sourceLanguage = null);

    /**
     * Detect language
     *
     * @param string $text
     * @return string|false
     */
    public function detectLanguage($text);

    /**
     * Get supported languages
     *
     * @return array
     */
    public function getLanguages();
}
