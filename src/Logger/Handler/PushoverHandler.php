<?php

namespace Subapp\WebApp\Logger\Handler;

use Subapp\Orm\Logger\Collection\Collection;
use Subapp\Orm\Logger\Formatter\LineFormatter;
use Subapp\Orm\Logger\Handler\AbstractHandler;
use Subapp\Orm\Logger\Handler\Mask\LogLevelMask;

/**
 * Class PushoverHandler
 * @package Subapp\Webapp\Logger\Handler
 */
class PushoverHandler extends AbstractHandler
{
    
    const PUSHOVER_URL = 'https://api.pushover.net/1/messages.json';
    
    /**
     * @var string
     */
    protected $token;
    
    /**
     * @var string
     */
    protected $user;
    
    /**
     * @var string
     */
    protected $title;
    
    /**
     * @var string
     */
    protected $device;
    
    /**
     * @var resource
     */
    protected $curl;
    
    /**
     * PushoverHandler constructor.
     * @param string $token
     * @param string $user
     * @param string $title
     * @param int    $level
     */
    public function __construct($token, $user, $title, $level = LogLevelMask::MASK_ALL)
    {
        parent::__construct($level);
        
        $this->token = $token;
        $this->user = $user;
        $this->title = $title;
        $this->curl = curl_init();
        
        curl_setopt_array($this->curl, [
            CURLOPT_URL => static::PUSHOVER_URL,
            CURLOPT_SAFE_UPLOAD => true,
            CURLOPT_RETURNTRANSFER => true,
        ]);
        
        $this->setFormatter(new LineFormatter('[:name.:level] :message'));
    }
    
    /**
     * @inheritDoc
     */
    public function handle(Collection $record)
    {
        $postData = [
            'title' => sprintf('%s: %s', $record->get('level'), $this->title),
            'message' => $this->formatter->format($record),
        ];
        
        $postData = $this->completePostData($postData);
        
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $postData);
        curl_exec($this->curl);
        
        return true;
    }
    
    /**
     * @param array $postData
     * @return array
     */
    protected function completePostData(array $postData)
    {
        return $postData + [
                'token' => $this->token,
                'user' => $this->user,
                'device' => $this->device,
            ];
    }
    
}