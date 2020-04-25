<?php

namespace App\Service;

use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Knp\Bundle\MarkdownBundle\MarkdownParserInterface;

class MarkdownTransformer
{
    private $parser;

    private $cache;

    public function __construct(MarkdownParserInterface $markdownParser, CacheInterface $cache)
    {
        $this->parser = $markdownParser;
        $this->cache = $cache;
    }

    public function parse(string $str):string
    {
        
        $key = 'markdown_' . md5($str);

        //**Pour le cache */:::::::::::::::::::::::::::::::
        $callFunctioncache = function (ItemInterface $item) use ($str)  {
            return $this->parser->transformMarkdown($str);
        };

        return $this->cache->get($key, $callFunctioncache); 
    }
}