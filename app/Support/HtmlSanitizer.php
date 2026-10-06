<?php

namespace App\Support;

/**
 * Sanitasi HTML kaya (blog, template notifikasi) tanpa dependency tambahan.
 * Menghapus: script/style/iframe/object/embed/form, atribut event (on*),
 * URL javascript:/data: pada href/src/action, dan komentar kondisional.
 * Tag format dasar (p, b, a, ul, img, table, …) dipertahankan.
 */
class HtmlSanitizer
{
    public static function clean(?string $html): ?string
    {
        if ($html === null || $html === '') return $html;

        $doc = new \DOMDocument('1.0', 'UTF-8');
        libxml_use_internal_errors(true);
        $doc->loadHTML('<?xml encoding="utf-8" ?>'.$html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $dangerous = ['script', 'style', 'iframe', 'object', 'embed', 'form', 'input', 'button', 'link', 'meta', 'base'];
        foreach ($dangerous as $tag) {
            $nodes = $doc->getElementsByTagName($tag);
            for ($i = $nodes->length - 1; $i >= 0; $i--) {
                $node = $nodes->item($i);
                $node->parentNode?->removeChild($node);
            }
        }

        $xpath = new \DOMXPath($doc);
        $onAttrs = $xpath->query('//*[@*[starts-with(name(), "on")]]');
        if ($onAttrs instanceof \DOMNodeList) {
            foreach ($onAttrs as $el) {
                foreach (iterator_to_array($el->attributes) as $attr) {
                    if (stripos($attr->nodeName, 'on') === 0) $el->removeAttribute($attr->nodeName);
                }
            }
        }
        $urlAttrs = $xpath->query('//*[@href or @src or @action]');
        if ($urlAttrs instanceof \DOMNodeList) {
            foreach ($urlAttrs as $el) {
                foreach (['href', 'src', 'action'] as $name) {
                    if (! $el->hasAttribute($name)) continue;
                    $val = trim((string) $el->getAttribute($name));
                    if (preg_match('/^\s*(javascript|data|vbscript)\s*:/i', $val)) {
                        $el->removeAttribute($name);
                    }
                }
            }
        }

        return $doc->saveHTML() ?: '';
    }
}
