<?php

declare(strict_types = 1);

namespace Example;

use Synatos\Porta\Model\OpenAPI;

class CompiledSchema
{

    /**
     * @var OpenAPI
     */
    private static $openAPI;

    /**
     * 
     * @return OpenAPI
     */
    public static function getOpenAPI() : OpenAPI
    {
        if (self::$openAPI === null) {
            self::$openAPI = new OpenAPI();
            self::$openAPI->fromArray(['openapi'=>'3.0.0','info'=>['title'=>'Core V3','description'=>'Core V3','version'=>'3.0.0'],'paths'=>['/api/security/login-with-password'=>['post'=>['requestBody'=>['description'=>'Login with Email and Password','content'=>['application/json'=>['schema'=>['type'=>'object','properties'=>['email'=>['type'=>'string','format'=>'email'],'password'=>['type'=>'string','minLength'=>10]]]]]],'responses'=>['200'=>['description'=>'Login with Email and Password','content'=>['application/json'=>['schema'=>['$ref'=>'#/components/schemas/User']]]],'401'=>['description'=>'NotAuthenticatedException','content'=>['application/json'=>['schema'=>['$ref'=>'#/components/schemas/ErrorMessage']]]],'428'=>['description'=>'PasswordResetRequiredException','content'=>['application/json'=>['schema'=>['$ref'=>'#/components/schemas/ErrorMessage']]]]]]]],'components'=>['schemas'=>['ErrorMessage'=>['type'=>'object','properties'=>['exception'=>['type'=>'string'],'statusCode'=>['type'=>'integer'],'message'=>['type'=>'string'],'stacktrace'=>['type'=>'array','items'=>['type'=>'string']]]],'User'=>['type'=>'object','nullable'=>true,'properties'=>['id'=>['type'=>'string','nullable'=>true,'maxLength'=>36],'gender'=>['type'=>'string','nullable'=>true,'maxLength'=>1],'firstName'=>['type'=>'string','nullable'=>true,'maxLength'=>50],'lastName'=>['type'=>'string','nullable'=>true,'maxLength'=>50],'email'=>['type'=>'string','nullable'=>true,'maxLength'=>50],'password'=>['type'=>'string','nullable'=>true,'maxLength'=>100],'resetCode'=>['type'=>'string','nullable'=>true,'maxLength'=>100],'blocked'=>['type'=>'boolean','nullable'=>true],'technicalUser'=>['type'=>'boolean','nullable'=>true],'lastPasswordChange'=>['type'=>'string','nullable'=>true],'jwsToken'=>['type'=>'string','nullable'=>true],'roleList'=>['type'=>'array','nullable'=>true],'locale'=>['type'=>'string','nullable'=>true,'maxLength'=>5],'timezone'=>['type'=>'string','nullable'=>true,'maxLength'=>20]]]],'parameters'=>['AuthToken'=>['name'=>'x-core-auth-token','in'=>'header','schema'=>['type'=>'string','nullable'=>false]]]]]);
        }
        return self::$openAPI;
    }
}