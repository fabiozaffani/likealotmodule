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
		$this->version = 1.2;
		$this->author = 'iLet Develop Team';
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
			!Configuration::updateValue('LIKE_FACEBOOK_APP_ID', '168421886551926') OR
			!Configuration::updateValue('LIKE_FACEBOOK_APP_LANG', 'en_US') OR
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
				Configuration::updateValue('LIKE_FACEBOOK_FACES', Tools::getValue('displayFaces'));
				Configuration::updateValue('LIKE_FACEBOOK_SEND', Tools::getValue('displaySend'));
				Configuration::updateValue('LIKE_FACEBOOK_BUTTON_TEXT', Tools::getValue('textLike'));
				$this->_html .= $this->displayConfirmation($this->l('Settings updated successfully'));
			}
		}

		$this->_html .= '
		<form action="'.$_SERVER['REQUEST_URI'].'" method="post">';
				
		// LIKE BUTTON
		$this->_html .='
		<fieldset>
			<legend><img src="'.$this->_path.'logo.gif" alt="" title="" />'.$this->l('Facebook Like Button').'</legend>
			<div class="margin-form">
				<label>'.$this->l('Have other Facebook Module?').'</label>
				<input type="radio" name="alreadyFace" id="alreadyFace" value="1" '.(Configuration::get('LIKE_ALREADYFACE') ? 'checked="checked" ' : '').'/>
				<label class="t" for="alreadyFace"> <img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" /></label>
				<input type="radio" name="alreadyFace" id="notAlreadyFace" value="0" '.(!Configuration::get('LIKE_ALREADYFACE') ? 'checked="checked" ' : '').'/>
				<label class="t" for="notAlreadyFace"> <img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" /></label><br/>
				<small style="padding-left:90px">Check this box if you already have another Module using Facebook Integration.</small>
			</div>
			<br/>
			<div class="margin-form">
				<label>'.$this->l('Facebook APP ID').'</label>
				<input type="text" name="appID" id="appID" value="'.(Configuration::get('LIKE_FACEBOOK_APP_ID')).'" /><br/>
				<small style="padding-left:90px">Insert your own Facebook APP ID or use the module default one.</small>
			</div>
			<br/>
			<div class="margin-form">
				<label>'.$this->l('Facebook APP Language').'</label>
				<input type="text" name="language" id="language" value="'.(Configuration::get('LIKE_FACEBOOK_APP_LANG')).'" /><br/>
				<small style="padding-left:90px">Choose your language. Examples: es_LA, pt_BR, en_US</small>				
			</div>
			<br/>
			<div class="margin-form">
				<label>'.$this->l('Show users pictures?').'</label>
				<input type="radio" name="displayFaces" id="displayFaces" value="1" '.(Configuration::get('LIKE_FACEBOOK_FACES') ? 'checked="checked" ' : '').'/>
				<label class="t" for="displayLike"> <img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" /></label>
				<input type="radio" name="displayFaces" id="dontDisplayFaces" value="0" '.(!Configuration::get('LIKE_FACEBOOK_FACES') ? 'checked="checked" ' : '').'/>
				<label class="t" for="displayRecommend"> <img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" /></label>
			</div>
			<br/>	
			<div class="margin-form">
				<label>'.$this->l('Show Send Button?').'</label>
				<input type="radio" name="displaySend" id="displaySend" value="1" '.(Configuration::get('LIKE_FACEBOOK_SEND') ? 'checked="checked" ' : '').'/>
				<label class="t" for="displayLike"> <img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" /></label>
				<input type="radio" name="displaySend" id="dontDisplaySend" value="0" '.(!Configuration::get('LIKE_FACEBOOK_SEND') ? 'checked="checked" ' : '').'/>
				<label class="t" for="displayRecommend"> <img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" /></label><br/>
				<small style="padding-left:90px">New feature from Facebook. Allows the user the send the page to a friend.</small>
			</div>
			<br/>
			<div class="margin-form">
				<label>'.$this->l('Choose Like button text').'</label>
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
			
		$thanks = '<span style="font-size:11px;font-color:#999999;font-style:italic;margin-top:11px;float:left">Plugin created by <a href="http://www.ilet.com.br" target="_blank">Acessórios Apple - iLet</a> - More info at - <a href="http://www.ilet.com.br/blog/prestashop-modules/" target="_blank">Prestashop Modules</a></span>';
		
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

		$smarty->assign(array(
			'action' => Configuration::get('LIKE_FACEBOOK_BUTTON_TEXT'),
			'faces' => $faceFaces,
			'send' => $faceSend
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