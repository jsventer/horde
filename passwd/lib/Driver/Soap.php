<?php
/**
 * Copyright 2009-2014 Horde LLC (http://www.horde.org/)
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.horde.org/licenses/gpl.
 *
 * @category  Horde
 * @copyright 2009-2014 Horde LLC
 * @license   http://www.horde.org/licenses/gpl GPL
 * @package   Passwd
 */

/**
 * Changes a password through a SOAP request.
 *
 * @author    Jan Schneider <jan@horde.org>
 * @category  Horde
 * @copyright 2009-2014 Horde LLC
 * @license   http://www.horde.org/licenses/gpl GPL
 * @package   Passwd
 */
class Passwd_Driver_Soap extends Passwd_Driver
{
    /**
     */
    public function __construct(array $params = array())
    {
        if (!class_exists('SoapClient')) {
            throw new Passwd_Exception('You need the soap PHP extension to use this driver.');
        }

        if (empty($params['wsdl']) &&
            (empty($params['soap_params']['location']) ||
             empty($params['soap_params']['uri']))) {
            throw new Passwd_Exception('Either the "wsdl" or the "location" and "uri" parameter must be provided.');
        }

        if (isset($params['wsdl'])) {
            unset($params['soap_params']['location']);
            unset($params['soap_params']['uri']);
        }
        $params['soap_params']['exceptions'] = false;

        parent::__construct($params);
    }

    /**
     */
    protected function _changePassword($user, $oldpass, $newpass)
    //* la funcion estaba mal definida. No hubiera funcionado sin el ._.
    {
        /*
         * Se modifica la variable args para adaptarla a la llamada de los metodos
         * de ISPConfig
         */
        $args = array('session_id'=>'','1','params' => array());

        //* Se colocan los parametros de la funcion en el sitio definido por
        //* array "arguments"
        //* Esto es casi igual que como estaba, solo que ahora los argumentos
        //* van dentro de un array

        if (($pos = array_search('username', $this->_params['arguments'])) !== false) {
            $args['params'][$pos] = $user;
        }
        if (($pos = array_search('oldpassword', $this->_params['arguments'])) !== false) {
            $args['params'][$pos] = $oldpass;
        }
        if (($pos = array_search('newpassword', $this->_params['arguments'])) !== false) {
            $args['params'][$pos] = $newpass;
        }

        //* Abrimos el cliente como indica el metodo general
        $client = new SoapClient(
            $this->_params['wsdl'],
            $this->_params['soap_params']
        );

        //* Abrimos una sesion con el usuario y password definidos en el array login
        if  ($session_id = $client->login(
                 $this->_params['login']['username'],
                 $this->_params['login']['password']
             )){
            //+ Si todo va bien, hacemos la llamada al metodo como estaba antes.
            $args['session_id'] = $session_id;
            $res = $client->__soapCall($this->_params['method'], $args);
            if ($res instanceof SoapFault) {
                throw new Passwd_Exception($res->getMessage(), $res->getCode());
            }
        }
    }

}
