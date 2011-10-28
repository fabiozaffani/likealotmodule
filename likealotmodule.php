<?php
/*
*  @author iLet Developer Fabio Zaffani <fabiozaffani@gmail.com>
*  @version  Release: $Revision: 1.1 $
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*/

if (!defined('_CAN_LOAD_FILES_'))
	exit;
	
class likealotmodule extends Module
{

	protected static $cookie;
    private $_html;

	public function __construct()
	{
		$this->name = 'likealotmodule';
		$this->tab = 'front_office_features';
		$this->version = 1.3;
		$this->author = 'Plulz Develop Team';
		$this->need_instance = 0;
				
		parent::__construct();
		
		$this->displayName = $this->l('Facebook Like a Lot');
		$this->description = $this->l('Adds a facebook Like Button to your product page.');
	}

	public function install()
	{
	 	if (
			!parent::install() OR
			!$this->registerHook('header') OR
			!$this->registerHook('top') OR
			!$this->registerHook('extraLeft') OR
			!$this->registerHook('footer') OR
			!Configuration::updateValue('LIKE_SHARE', 1) OR
			!Configuration::updateValue('LIKE_ALREADYFACE', 0) OR
			!Configuration::updateValue('LIKE_FACEBOOK_APP_ID', '') OR
			!Configuration::updateValue('LIKE_FACEBOOK_APP_LANG', 'en_US') OR
            !Configuration::updateValue('LIKE_FACEBOOK_WIDTH', 300) OR
			!Configuration::updateValue('LIKE_FACEBOOK_FACES', 0) OR
			!Configuration::updateValue('LIKE_FACEBOOK_SEND', 1) OR
			!Configuration::updateValue('LIKE_FACEBOOK_BUTTON_TEXT', 'like')
		)
	 		return false;
	 	return true;
	}
	
	public function uninstall()
	{
	 	if (
			!parent::uninstall() OR
			!$this->unregisterHook('header') OR
			!$this->unregisterHook('top') OR
			!$this->unregisterHook('extraLeft') OR
			!$this->unregisterHook('footer') OR
			!Configuration::deleteByName('LIKE_SHARE') OR
			!Configuration::deleteByName('LIKE_ALREADYFACE') OR
			!Configuration::deleteByName('LIKE_FACEBOOK_APP_ID') OR
			!Configuration::deleteByName('LIKE_FACEBOOK_APP_LANG') OR
			!Configuration::deleteByName('LIKE_FACEBOOK_FACES') OR
			!Configuration::deleteByName('LIKE_FACEBOOK_SEND') OR
			!Configuration::deleteByName('LIKE_FACEBOOK_BUTTON_TEXT')
		)
	 		return false;
	 	return true;
	}

	public function getContent()
	{
		$this->_html = '';
		if (Tools::isSubmit('submitFace'))
		{
			if (Tools::getValue('displayLike') != 0 AND Tools::getValue('displayLike') != 1)
				$this->_html .= $this->displayError('Invalid Option for Like Button Show');
			if (Tools::getValue('textLike') != 'recommend' AND Tools::getValue('textLike') != 'like')
				$this->_html .= $this->displayError('Invalid Option for Like Button Text');
			else
			{
				Configuration::updateValue('LIKE_ALREADYFACE', Tools::getValue('alreadyFace'));
				Configuration::updateValue('LIKE_FACEBOOK_APP_ID', Tools::getValue('appID'));
				Configuration::updateValue('LIKE_FACEBOOK_APP_LANG', Tools::getValue('language'));
                Configuration::updateValue('LIKE_FACEBOOK_WIDTH', Tools::getValue('width'));
				Configuration::updateValue('LIKE_FACEBOOK_FACES', Tools::getValue('displayFaces'));
				Configuration::updateValue('LIKE_FACEBOOK_SEND', Tools::getValue('displaySend'));
				Configuration::updateValue('LIKE_FACEBOOK_BUTTON_TEXT', Tools::getValue('textLike'));
				$this->_html .= $this->displayConfirmation($this->l('Settings updated successfully'));
			}
		}

        $appId = Configuration::get('LIKE_FACEBOOK_APP_ID');

        if (empty($appId))
        {
            $alertMsg = '<div class="margin-form" style="padding:0 0 1em 100px;">
			                <p style="color:red;">
                                Your module will not work until you insert your Facebook APP ID.
                                Need Help? Know <a href="http://www.plulz.com/how-to-create-a-facebook-app" target="_blank" style="color:red;font-weight:bold;"><strong>How To Create your Facebook App</strong></a>
			                </p>
			            </div>';
        }

		$this->_html .= '
		<div style="position:absolute;top:300px;right:130px;background:#7B0099;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;color:#ffffff;width:170px;height:150px;border:2px solid #7B0099;padding:15px;">
		<p style="padding-bottom:25px;text-align:center;">I spend a lot of time making and improving this plugin, any donation would be very helpful for me, thank you very much :)</p>
		<form id="paypalform" action="https://www.paypal.com/cgi-bin/webscr" method="post"><input type="hidden" name="cmd" value="_s-xclick"><input type="hidden" name="hosted_button_id" value="NMR62HAEAHCRL"><input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!"><img alt="" border="0" src="https://www.paypalobjects.com/pt_BR/i/scr/pixel.gif" width="1" height="1"></form>
		</div>
		
		<form action="'.$_SERVER['REQUEST_URI'].'" method="post">';
				
		// LIKE BUTTON
		$this->_html .='

		<fieldset>
			<legend><img src="'.$this->_path.'logo.gif" alt="" title="" />'.$this->l('Facebook Like Button').'</legend>
			' . $alertMsg . '
			<div class="margin-form" style="padding:0 0 1em 100px;">
				<label style="width:162px;text-align:left;">'.$this->l('Have other Facebook Module?').'</label>
				<input type="radio" name="alreadyFace" id="alreadyFace" value="1" '.(Configuration::get('LIKE_ALREADYFACE') ? 'checked="checked" ' : '').'/>
				<label class="t" for="alreadyFace"> <img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" /></label>
				<input type="radio" name="alreadyFace" id="notAlreadyFace" value="0" '.(!Configuration::get('LIKE_ALREADYFACE') ? 'checked="checked" ' : '').'/>
				<label class="t" for="notAlreadyFace"> <img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" /></label><br/>
				<small style="padding-left:164px;padding-top:10px;display:block;font-size:11px;">Check this <strong>only</strong> if you have other Module using Facebook Integration.</small>
			</div>
			<br/>
			<div class="margin-form" style="padding:0 0 1em 100px;">
				<label style="width:162px;text-align:left;">'.$this->l('Facebook APP ID').'</label>
				<input type="text" name="appID" id="appID" value="'.(Configuration::get('LIKE_FACEBOOK_APP_ID')).'" /><br/>
				<small style="padding-left:164px;padding-top:10px;display:block;font-size:11px;">Your Facebook APP ID. Need Help? <a href="http://www.plulz.com/how-to-create-a-facebook-app" target="_blank">How To Create a Facebook App</a></small>
			</div>
			<br/>
			<div class="margin-form" style="padding:0 0 1em 100px;">
				<label style="width:162px;text-align:left;">'.$this->l('Facebook APP Language').'</label>
				<input type="text" name="language" id="language" value="'.(Configuration::get('LIKE_FACEBOOK_APP_LANG')).'" /><br/>
				<small style="padding-left:164px;padding-top:10px;display:block;font-size:11px;">Choose your language. Examples: pt_BR, en_US. Default to en_US</small>
			</div>
			<br/>
			<div class="margin-form" style="padding:0 0 1em 100px;">
				<label style="width:162px;text-align:left;">'.$this->l('Like a Lot Width').'</label>
				<input type="text" name="width" id="width" value="'.(Configuration::get('LIKE_FACEBOOK_WIDTH')).'" /><br/>
				<small style="padding-left:164px;padding-top:10px;display:block;font-size:11px;">ONLY NUMBERS. Choose the width of the like box. Default to 300</small>
			</div>
			<br/>
			<div class="margin-form" style="padding:0 0 1em 100px;">
				<label style="width:162px;text-align:left;">'.$this->l('Show users pictures?').'</label>
				<input type="radio" name="displayFaces" id="displayFaces" value="1" '.(Configuration::get('LIKE_FACEBOOK_FACES') ? 'checked="checked" ' : '').'/>
				<label class="t" for="displayLike"> <img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" /></label>
				<input type="radio" name="displayFaces" id="dontDisplayFaces" value="0" '.(!Configuration::get('LIKE_FACEBOOK_FACES') ? 'checked="checked" ' : '').'/>
				<label class="t" for="displayRecommend"> <img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" /></label>
			</div>
			<br/>	
			<div class="margin-form" style="padding:0 0 1em 100px;">
				<label style="width:162px;text-align:left;">'.$this->l('Show Send Button?').'</label>
				<input type="radio" name="displaySend" id="displaySend" value="1" '.(Configuration::get('LIKE_FACEBOOK_SEND') ? 'checked="checked" ' : '').'/>
				<label class="t" for="displayLike"> <img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" /></label>
				<input type="radio" name="displaySend" id="dontDisplaySend" value="0" '.(!Configuration::get('LIKE_FACEBOOK_SEND') ? 'checked="checked" ' : '').'/>
				<label class="t" for="displayRecommend"> <img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" /></label><br/>
				<small style="padding-left:164px;padding-top:10px;display:block;font-size:11px;">Allows the user the send the page to a friend.</small>
			</div>
			<br/>
			<div class="margin-form" style="padding:0 0 1em 100px;">
				<label style="width:162px;text-align:left;">'.$this->l('Choose Like button text').'</label>
				<input type="radio" name="textLike" id="display_off" value="like" '.(Configuration::get('LIKE_FACEBOOK_BUTTON_TEXT') == 'like' ? 'checked="checked" ' : '').'/>
				<label class="t" for="display_off"> Like </label>
				<input type="radio" name="textLike" id="display_on" value="recommend" '.(Configuration::get('LIKE_FACEBOOK_BUTTON_TEXT') == 'recommend' ? 'checked="checked" ' : '').'/>
				<label class="t" for="display_on"> Recommend </label>
			</div>
			<br/>
			<center><input type="submit" name="submitFace" value="'.$this->l('Save').'" class="button" /></center>
		</fieldset><br/>';
        
		return $this->_html;
	}


	public function hookHeader($params)
	{
		global $smarty;
		
		$id_product = (int)Tools::getValue('id_product');
		$id_lang = (int)$params['cookie']->id_lang;
		
		// Get product info to append to the OG tags for Facebook Integration
		$row = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('
			SELECT p.*, pl.`description`, pl.`description_short`, pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, pl.`name`, p.`ean13`,  p.`upc`,
				i.`id_image`, il.`legend`, t.`rate`
			FROM `'._DB_PREFIX_.'product` p
			LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.`id_product` = pl.`id_product` AND pl.`id_lang` = '.(int)($id_lang).')
			LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product` AND i.`cover` = 1)
			LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)($id_lang).')
			LEFT JOIN `'._DB_PREFIX_.'tax_rule` tr ON (p.`id_tax_rules_group` = tr.`id_tax_rules_group`
													   AND tr.`id_country` = '.(int)Country::getDefaultCountryId().'
													   AND tr.`id_state` = 0)
			LEFT JOIN `'._DB_PREFIX_.'tax` t ON (t.`id_tax` = tr.`id_tax`)
			WHERE p.id_product = '.(int)$id_product);

		$product = Product::getProductProperties($id_lang, $row);
		
		// Instantiate the class to get the product description
		$description = new Product($id_product, true, $id_lang);
		
		$smarty->assign(array(
			'description' => strip_tags($description->description_short),
			'product' => $product
			));
		return $this->display(__FILE__, 'head.tpl');
	}
	
	public function hookFooter($params)
	{
		global $smarty;
			
		$thanks = '<span style="font-size:11px;font-color:#999999;font-style:italic;margin-top:11px;float:left">Module from the creators of <a href="http://www.guitarpro6.com.br" target="_blank">Guitar Pro</a> :: More at <a href="http://www.plulz.com/prestashop-modules" target="_blank">Prestashop Modules</a></span>';
		
		$smarty->assign(array(
			'enable' => true,
			'clear' => $thanks,
		));
		
		return $this->display(__FILE__, 'protected.tpl');
	}
	
	public function hookExtraLeft($params)
	{
		global $smarty;
		
		$faceFaces =  Configuration::get('LIKE_FACEBOOK_FACES');
		if ($faceFaces)
			$faceFaces = 'true';
		else
			$faceFaces = 'false';
			
		$faceSend = Configuration::get('LIKE_FACEBOOK_SEND');
		if($faceSend)
			$faceSend = 'true';
		else
			$faceSend = 'false';

        $width = Configuration::get('LIKE_FACEBOOK_WIDTH');

		$smarty->assign(array(
			'action' => Configuration::get('LIKE_FACEBOOK_BUTTON_TEXT'),
			'faces' => $faceFaces,
			'send' => $faceSend,
            'width' => $width
		));
		return $this->display(__FILE__, 'likealot.tpl');
	}
	
	public function hookTop($params)
	{
		global $smarty;
		$smarty->assign(array(
			'alreadyface' => Configuration::get('LIKE_ALREADYFACE'),
			'app_id' => Configuration::get('LIKE_FACEBOOK_APP_ID'),
			'lang' => Configuration::get('LIKE_FACEBOOK_APP_LANG')
		));
		return $this->display(__FILE__, 'top.tpl');
	}
}
?>