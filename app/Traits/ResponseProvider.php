<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Response
 *
 * @author kas
 */
trait ResponseProvider {

    protected $error = '';
    protected $validationError = '';
    protected $response = '';
    protected $responseParameters = [];
    protected $supportingDataUseCase = '';
    protected $supportingData = null;

    public function response() {
        $response = ['code' => '', 'httpcode' => '', 'response' => '', 'supportingData'=>['useCase'=>$this->supportingDataUseCase,'data'=>$this->supportingData]];
        if ($this->validationError) {
            $response['code'] = 412;
            $response['response'] = $this->validationError;
            $response['httpCode'] = 412;
        } else
        if ($this->error) {
            $response['code'] = trans('message.' . $this->error . '.code');
            $response['response'] = trans('message.' . $this->error . '.message',$this->responseParameters);
            $response['httpCode'] = trans('message.' . $this->error . '.httpCode');
        } else {
            if (is_object($this->response)) {
                $case = 'object';
            } else if (is_array($this->response)) {
                $case = 'array';
            } else {
                $case = 'string';
            }

            switch ($case) {
                case 'object':
                    $response['response'] = $this->response->toArray();
                    break;
                case 'array':
                    $response['response'] = $this->response;
                    break;
                case 'string';
                    $response['response'] = trans('message.' . $this->response . '.message',$this->responseParameters);
                    break;
            }
            $response['code'] = 200;
            $response['httpCode'] = 200;
        }

        return \Response::json([
                    'code' => $response['code'],
                    'response' => $response['response'],
                    'supportingData' => $response['supportingData'],
                        ], $response['httpCode']);
    }

}
