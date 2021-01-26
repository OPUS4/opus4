<?php
/**
 * This file is part of OPUS. The software OPUS has been originally developed
 * at the University of Stuttgart with funding from the German Research Net,
 * the Federal Department of Higher Education and Research and the Ministry
 * of Science, Research and the Arts of the State of Baden-Wuerttemberg.
 *
 * OPUS 4 is a complete rewrite of the original OPUS software and was developed
 * by the Stuttgart University Library, the Library Service Center
 * Baden-Wuerttemberg, the Cooperative Library Network Berlin-Brandenburg,
 * the Saarland University and State Library, the Saxon State Library -
 * Dresden State and University Library, the Bielefeld University Library and
 * the University Library of Hamburg University of Technology with funding from
 * the German Research Foundation and the European Regional Development Fund.
 *
 * LICENCE
 * OPUS is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the Licence, or any later version.
 * OPUS is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details. You should have received a copy of the GNU General Public License
 * along with OPUS; if not, write to the Free Software Foundation, Inc., 51
 * Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 *
 * @category    Framework
 * @package     Opus_Validate
 * @author      Ralf Claussnitzer <ralf.claussnitzer@slub-dresden.de>
 * @author      Jens Schwidder <schwidder@zib.de>
 * @copyright   Copyright (c) 2008-2021, OPUS 4 development team
 * @license     http://www.gnu.org/licenses/gpl.html General Public License
 */

namespace Opus\Validate;

use Laminas\Validator\AbstractValidator;

/**
 * Validator for Language field. Only accept standard Zend_Locale locale names.
 *
 * @category    Framework
 * @package     Opus_Validate
 */
class Language extends AbstractValidator
{
    /**
     * Error message key.
     *
     */
    const MSG_LANGUAGE = 'language';

    /**
     * Error message templates.
     *
     * @var array
     */
    protected $messageTemplates = [
        self::MSG_LANGUAGE => "'%value%' is not a valid language shortcut."
    ];

    /**
     * Validate the given value. Looks for the language translation list
     * in the registry (key 'Available_Languages'). If this key is not registered
     * the language list is obtained through a call to getLanguageTranslationList()
     * of Zend_Locale.
     *
     * @param string $value An enum string.
     * @return boolean True if the given enum string is known.
     */
    public function isValid($value)
    {
        if (false === is_array($value)) {
            $value = [$value];
        }

        $registry = \Zend_Registry::getInstance();
        if ($registry->isRegistered('Available_Languages')) {
            $translationList = $registry->get('Available_Languages');
        } else {
            $locale = new \Zend_Locale();
            $translationList = $locale->getTranslationList('language');
        }

        foreach ($value as $val) {
            $this->setValue($val);

            if (is_string($val) === false) {
                $this->error(self::MSG_LANGUAGE);
                return false;
            }

            if (array_key_exists($val, $translationList) === false) {
                $this->error(self::MSG_LANGUAGE);
                return false;
            }
        }

        return true;
    }
}
