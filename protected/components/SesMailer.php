<?php
use Aws\Ses\SesClient;
class SesMailer {
    
	private $_sesClient;
	private $_from;
	private  $_to;
	private $_subject;
	private $_view;
	private $_data;
	function __construct() {
		$this->_setSesClient(SesClient::factory(array(
            'key' => Yii::app()->params['awsKey'],
            'secret' => Yii::app()->params['awsSecret'],
            'region' => Yii::app()->params['awsRegion'],
        )));
	}

    /**
     * Sets the value of _sesClient.
     *
     * @param mixed $_sesClient the  ses client 
     *
     * @return self
     */
    private function _setSesClient($sesClient)
    {
        $this->_sesClient = $sesClient;
    }

    /**
     * Sets the value of _from.
     *
     * @param mixed $_from the  from 
     *
     * @return self
     */
    public function setFrom($from, $name = null)
    {
        if ($name) {
            $this->_from = $name.' <'.$from.'>';
        } else {
            $this->_from = $from;
        }
    }

    /**
     * Sets the value of _to.
     *
     * @param mixed $_to the  to 
     *
     * @return self
     */
    public function setTo($to)
    {
        $this->_to = $to;
    }

    /**
     * Sets the value of _subject.
     *
     * @param mixed $_subject the  subject 
     *
     * @return self
     */
    public function setSubject($subject)
    {
        $this->_subject = $subject;
    }

    /**
     * Sets the value of _view.
     *
     * @param mixed $_view the  view 
     *
     * @return self
     */
    public function setView($view)
    {
        $this->_view = '//mail/'.$view;
    }

    /**
     * Sets the value of _data.
     *
     * @param mixed $_data the  data 
     *
     * @return self
     */
    public function setData($data)
    {
        $this->_data = $data;
    }

    private function _getRenderedView()
    {
    	if(isset(Yii::app()->controller))
			$controller=Yii::app()->controller;
		else
			$controller=new CController(__CLASS__);

		//render and return the result
		return $controller->renderInternal($controller->getViewFile($this->_view), $this->_data, true);
    }

    public function send()
    {
        $result = [];
        $result['error'] = FALSE;
        try{
        	$this->_sesClient->sendEmail(
        		array(
    			    // Source is required
    			    'Source' => $this->_from,
    			    // Destination is required
    			    'Destination' => array(
    			        'ToAddresses' => array($this->_to)
    			    ),
    			    // Message is required
    			    'Message' => array(
    			        // Subject is required
    			        'Subject' => array(
    			            // Data is required
    			            'Data' => $this->_subject,
    			            'Charset' => 'utf-8',
    			        ),
    			        // Body is required
    			        'Body' => array(
    			            'Html' => array(
    			                // Data is required
    			                'Data' => $this->_getRenderedView(),
    			                'Charset' => 'utf-8',
    			            ),
    			        ),
    			    ),
    			    'ReplyToAddresses' => array(Yii::app()->params['supportEmail']),
    			    'ReturnPath' => Yii::app()->params['adminEmail']
    			)
        	);

            return true;
        } catch (Exception $e) {
            //stop( $e->getMessage()."\n");
            //error_reporting(E_ALL);
            //ini_set('display_errors', 'On');
            //return false;
            $result['message'] = $e->getAwsErrorType();
            $result['errorCode'] = $e->getAwsErrorCode();
            $result['error'] = TRUE;
        }
        return $result;
    }
}