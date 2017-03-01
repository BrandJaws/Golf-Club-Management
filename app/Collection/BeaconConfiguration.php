<?php
namespace App\Collection;

use Illuminate\Support\Collection;
use PhpParser\Node\Stmt\Foreach_;

class BeaconConfiguration
{

    private $skeleton = [
        'Near' => [
            'action' => '',
            'message' => ''
        ],
        'Immediate' => [
            'action' => '',
            'message' => ''
        ],
        'Far' => [
           'action' => '',
           'message' => ''
        ]
    ];
    private $configuration;
    public function __construct(){
        $this->configuration =  new Collection([]);
    }
    public function boot(array $configuration){
        foreach ($this->skeleton as $key=>$value){
            $propertySet = array_get($configuration, $key, null);
            if(is_null($propertySet))
                throw new \Exception($key.' is required');
            else 
                foreach($this->skeleton[$key] as $propertyKey=>$value){
                    if(!is_null(array_get($configuration, $key.'.'.$propertyKey, null))){
                        $this->configuration->put($key, array_get($configuration, $key.'.'.$propertyKey, null));
                    }
                   
            }
        }
        dd($this->configuration);
    }
}