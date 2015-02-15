<?php
namespace Translation\Model;

use Zend\I18n\Translator\Translator as ZendTranslator;
use Zend\I18n\Translator\Plural\Rule;

class Translator extends ZendTranslator
{
	/**
     * Translate a plural message.
     *
     * @param  string                         $singular
     * @param  string                         $plural
     * @param  int                            $number
     * @param  string                         $textDomain
     * @param  string|null                    $locale
     * @return string
     * @throws Exception\OutOfBoundsException
     */
    public function translatePlural(
        $singular,
        $plural,
        $number,
        $textDomain = 'default',
        $locale = null
    ) {
        $locale      = $locale ?: $this->getLocale();
        $translation = $this->getTranslatedMessage($singular, $locale, $textDomain);

        if ($translation === null || $translation === '') {
            if (null !== ($fallbackLocale = $this->getFallbackLocale())
                && $locale !== $fallbackLocale
            ) {
                return $this->translatePlural(
                    $singular,
                    $plural,
                    $number,
                    $textDomain,
                    $fallbackLocale
                );
            }

            return ($number == 1 ? $singular : $plural);
        }

		$index = Rule::fromString('nplurals=3; nplural=(n%100>10&&n%100<20 ? 2 : (n%100%10>1&&n%100%10<5 ? 1 : (n%100%10==1 ? 0 : 2)))')->evaluate($number);

        if (!isset($translation[$index])) {
            throw new Exception\OutOfBoundsException(sprintf(
                'Provided index %d does not exist in plural array', $index
            ));
        }

        return $translation[$index];
    }
}