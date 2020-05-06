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

use Arikaim\Core\Extension\Module;

/**
 * Translate module class
 */
class Translate extends Module
{   
    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * Install module
     *
     * @return void
     */
    public function install()
    {
        $this->installDriver('Arikaim\\Modules\\Translate\\Driver\\GoogleTranslate');
        $this->installDriver('Arikaim\\Modules\\Translate\\Driver\\GoogleSimpleTranslate');
        $this->installDriver('Arikaim\\Modules\\Translate\\Driver\\YandexApiTranslate');

        return true;
    }
}
