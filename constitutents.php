<?php 

require_once 'bsd_api.class.php';
require_once 'factory/array_factory.php';

class Constituent
{
	private $client = null;

	public function __construct($api_id, $api_secret) 
	{
		$this->client = new BSD_API($api_id, $api_secret);     
    }



	public function email_register($email, $constituent_id = null, $is_subscribed = false, $guid = null, $format = 'json')
	{

		$options = ArrayFactory::create();
		$options->add('email', $email);
		if (!is_null($format))
		{
			$format = ($format === 'json') ? $format : 'xml';
			$options->add('format', $format);
		}
		
		$opts = $options->get();
		return  $this->client->call_api('cons/email_register', $opts);
	}



	public function email_unsubscribe($email, $reason)
	{

	}



	public function list_constituent_groups()
	{
		$results = $this->client->call_api('cons_group/list_constituent_groups');
	}



	public function get_constituents_by_id($constituent_id = array(), $bundles = null, $filter = null)
	{
		//TODO
	}


	public function get_constituents($filter, $bundles)
	{
		//TODO
	}



	public function get_constituents_by_guid($guids, $bundles = null, $filter = null)
	{
		//TODO
	}



	public function get_updated_constituents($change_since, $bundles = null, $filter = null)
	{
		//TODO
	}


	public function delete_constituents_by_id($constituent_id)
	{
		//TODO
	} 


}

?>