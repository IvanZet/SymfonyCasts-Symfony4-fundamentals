<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface; 
use Michelf\MarkdownInterface;

class MarkdownHelper
{
    private $cache;
    private $markdown;
    private $logger;
    private $isDebug;
    
    public function __construct(AdapterInterface $cache, MarkdownInterface $markdown, LoggerInterface $markdownLogger, bool $isDebug)
    {
        $this->cache = $cache;
        $this->markdown = $markdown;
        $this->logger = $markdownLogger;
        $this->isDebug = $isDebug;
    }
    
    public function parse(string $source): string
    {
        if(stripos($source, 'bacon')) {
            $this->logger->info('They are talking about bacon again!');
        }
        
        // If debuggin on, don't use caching
        if ($this->isDebug) {
            return $this->markdown->transform($source);
        }

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