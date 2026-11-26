<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */

namespace skeeks\cms\themes\unifyshop\helpers;

/**
 * Title, description и keywords карточки товара.
 *
 * Порядок: заполненные у товара meta_* всегда важнее; иначе — шаблон из настроек контента
 * товаров (CmsContent::meta_title_template / meta_description_template / meta_keywords_template);
 * иначе — строка по умолчанию темы.
 *
 * Переменные шаблона (как у шаблонов разделов, {=...}):
 * {=model.name}, {=model.seoName}, {=model.description_short}, {=section}, {=section.seoName},
 * {=brand}, {=sku} (артикул бренда), {=collection}, {=country}, {=price} (только при цене больше нуля),
 * {=measure} (единица, за которую указана цена), {=siteName},
 * {=property.<код или название характеристики>}.
 * Неизвестная или пустая переменная заменяется пустой строкой.
 * Часть шаблона в квадратных скобках выводится, только если все переменные внутри непустые:
 * «[Цена {=price} за {=measure}.]».
 * После подстановки пустые сегменты, повторная пунктуация и висящие разделители убираются.
 */
class ProductMeta
{
    /**
     * @param string        $template
     * @param array         $values   'model.name' => 'Плитка ...'
     * @param callable|null $resolver function(string $name): string — для переменных, которых нет в $values
     * @return string
     */
    public static function render($template, array $values, $resolver = null)
    {
        $resolve = static function ($name) use ($values, $resolver) {
            $name = trim($name);
            if (array_key_exists($name, $values)) {
                $value = $values[$name];
            } elseif ($resolver) {
                $value = call_user_func($resolver, $name);
            } else {
                $value = '';
            }
            return trim(preg_replace('/\s+/u', ' ', (string)$value));
        };

        $result = preg_replace_callback('/\[([^\[\]]*)\]/u', static function ($match) use ($resolve) {
            $isEmpty = false;
            $text = preg_replace_callback('/\{=([^{}]+)\}/u', static function ($var) use ($resolve, &$isEmpty) {
                $value = $resolve($var[1]);
                if ($value === '') {
                    $isEmpty = true;
                }
                return $value;
            }, $match[1]);
            return $isEmpty ? '' : $text;
        }, (string)$template);

        $result = preg_replace_callback('/\{=([^{}]+)\}/u', static function ($var) use ($resolve) {
            return $resolve($var[1]);
        }, $result);

        return static::clean($result);
    }

    /**
     * Описание по умолчанию: «✔ Название. ✔ Краткое описание. Цена 3 290 р.» — без пустых частей.
     *
     * @param string $seoName
     * @param string $descriptionShort HTML или текст
     * @param string $price            пусто, если цены нет
     * @return string
     */
    public static function defaultDescription($seoName, $descriptionShort, $price)
    {
        $parts = [];
        foreach ([['✔', static::plain($seoName)], ['✔', static::plain($descriptionShort)], ['Цена', trim((string)$price)]] as list($prefix, $text)) {
            $text = rtrim($text, " .");
            if ($text !== '') {
                $parts[] = $prefix.' '.$text;
            }
        }
        return $parts ? static::clean(implode('. ', $parts).'.') : '';
    }

    /**
     * HTML в одну строку текста.
     * @param string $html
     * @return string
     */
    public static function plain($html)
    {
        $text = preg_replace('/<(script|style)\b[^>]*>.*?<\/\1>/isu', ' ', (string)$html);
        $text = html_entity_decode(strip_tags(str_replace('<', ' <', $text)), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        return trim(preg_replace('/\s+/u', ' ', $text));
    }

    /**
     * Убирает следы пустых значений: маркеры без текста, повторную пунктуацию, пустые скобки,
     * висящие тире и разделители по краям.
     * @param string $text
     * @return string
     */
    public static function clean($text)
    {
        $text = preg_replace('/\s+/u', ' ', (string)$text);
        $text = preg_replace('/\(\s*[,.;:]*\s*\)/u', '', $text);
        //маркер, за которым ничего нет: «✔ .», «✔ ✔»
        $text = preg_replace('/[✔✓❤]\s*(?=[✔✓❤.,;:!?]|$)/u', '', $text);
        //тире или черта без текста с одной стороны
        $text = preg_replace('/\s+[—–|-]\s*(?=[.,;:!?]|$)/u', '', $text);
        $text = preg_replace('/(^|[.,;:!?])\s*[—–|-]\s+/u', '$1 ', $text);
        $text = preg_replace('/\s+([.,;:!?])/u', '$1', $text);
        //«р..», «, ,», «, .» — остаётся точка, иначе первый знак
        $text = preg_replace_callback('/[.,;:](?:\s*[.,;:])+/u', static function ($match) {
            return strpos($match[0], '.') !== false ? '.' : mb_substr($match[0], 0, 1);
        }, $text);
        $text = preg_replace('/\s{2,}/u', ' ', $text);
        $text = preg_replace('/^[\s.,;:!?—–|-]+/u', '', $text);
        $text = preg_replace('/[\s,;:—–|-]+$/u', '', $text);
        return trim($text);
    }
}
