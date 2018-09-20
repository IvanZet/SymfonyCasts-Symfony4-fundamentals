<?php

namespace App\Service;

use Symfony\Component\Cache\Adapter\AdapterInterface; 
use Michelf\MarkdownInterface;

class MarkdownHelper
{
    private $cache;
    private $markdown;
    
    public function __construct(AdapterInterface $cache, MarkdownInterface $markdown)
    {
        $this->cache = $cache;
        $this->markdown = $markdown;
    }
    
    public function parse(string $source): string
    {
        // Create cache item object in memory to handle cache item
        $item = $this->cache->getItem('markdown_'.md5($source));
        
        if (!$item->isHit()) { // If the key is not already cached
            // Put the item into cache
            $item->set($this->markdown->transform($source));
            $this->cache->save($item);
        }        

        // Fetch value from the cache
        return $item->get(); 
    }
}