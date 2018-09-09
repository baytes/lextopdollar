<?php
/*

 $Id: sitemap-ui.php 935247 2014-06-19 17:13:03Z arnee $

*/

class GoogleSitemapGeneratorUI {

	/**
	 * The Sitemap Generator Object
	 *
	 * @var GoogleSitemapGenerator
	 */
	private $sg = null;


	public function __construct(GoogleSitemapGenerator $sitemapBuilder) {
		$this->sg = $sitemapBuilder;
	}

	private function HtmlPrintBoxHeader($id, $title) {
		?>
			<div id="<?php echo $id; ?>" class="postbox">
				<h3 class="hndle"><span><?php echo $title ?></span></h3>
				<div class="inside">
		<?php
	}

	private function HtmlPrintBoxFooter() {
			?>
				</div>
			</div>
		<?php
	}

	/**
	 * Echos option fields for an select field containing the valid change frequencies
	 *
	 * @since 4.0
	 * @param $currentVal mixed The value which should be selected
	 */
	public function HtmlGetFreqNames($currentVal) {

		foreach($this->sg->GetFreqNames() AS $k=>$v) {
			echo "<option value=\"" . esc_attr($k) . "\" " . self::HtmlGetSelected($k,$currentVal) .">" . esc_attr($v) . "</option>";
		}
	}

	/**
	 * Echos option fields for an select field containing the valid priorities (0- 1.0)
	 *
	 * @since 4.0
	 * @param $currentVal string The value which should be selected
	 * @return void
	 */
	public static function HtmlGetPriorityValues($currentVal) {
		$currentVal=(float) $currentVal;
		for($i=0.0; $i<=1.0; $i+=0.1) {
			$v = number_format($i,1,".","");
			echo "<option value=\"" . esc_attr($v) . "\" " . self::HtmlGetSelected("$i","$currentVal") .">";
			echo esc_attr(number_format_i18n($i,1));
			echo "</option>";
		}
	}

	/**
	 * Returns the checked attribute if the given values match
	 *
	 * @since 4.0
	 * @param $val string The current value
	 * @param $equals string The value to match
	 * @return string The checked attribute if the given values match, an empty string if not
	 */
	public static function HtmlGetChecked($val, $equals) {
		if($val==$equals) return self::HtmlGetAttribute("checked");
		else return "";
	}

	/**
	 * Returns the selected attribute if the given values match
	 *
	 * @since 4.0
	 * @param $val string The current value
	 * @param $equals string The value to match
	 * @return string The selected attribute if the given values match, an empty string if not
	 */
	public static function HtmlGetSelected($val,$equals) {
		if($val==$equals) return self::HtmlGetAttribute("selected");
		else return "";
	}

	/**
	 * Returns an formatted attribute. If the value is NULL, the name will be used.
	 *
	 * @since 4.0
	 * @param $attr string The attribute name
	 * @param $value string The attribute value
	 * @return string The formatted attribute
	 */
	public static function HtmlGetAttribute($attr,$value=NULL) {
		if($value==NULL) $value=$attr;
		return " " . $attr . "=\"" . esc_attr($value) . "\" ";
	}

	/**
	 * Returns an array with GoogleSitemapGeneratorPage objects which is generated from POST values
	 *
	 * @since 4.0
	 * @see GoogleSitemapGeneratorPage
	 * @return array An array with GoogleSitemapGeneratorPage objects
	 */
	public function HtmlApplyPages() {
		// Array with all page URLs
		$pages_ur=(!isset($_POST["sm_pages_ur"]) || !is_array($_POST["sm_pages_ur"])?array():$_POST["sm_pages_ur"]);

		//Array with all priorities
		$pages_pr=(!isset($_POST["sm_pages_pr"]) || !is_array($_POST["sm_pages_pr"])?array():$_POST["sm_pages_pr"]);

		//Array with all change frequencies
		$pages_cf=(!isset($_POST["sm_pages_cf"]) || !is_array($_POST["sm_pages_cf"])?array():$_POST["sm_pages_cf"]);

		//Array with all lastmods
		$pages_lm=(!isset($_POST["sm_pages_lm"]) || !is_array($_POST["sm_pages_lm"])?array():$_POST["sm_pages_lm"]);

		//Array where the new pages are stored
		$pages=array();
		//Loop through all defined pages and set their properties into an object
		if(isset($_POST["sm_pages_mark"]) && is_array($_POST["sm_pages_mark"])) {
			for($i=0; $i<count($_POST["sm_pages_mark"]); $i++) {
				//Create new object
				$p=new GoogleSitemapGeneratorPage();
				if(substr($pages_ur[$i],0,4)=="www.") $pages_ur[$i]="http://" . $pages_ur[$i];
				$p->SetUrl($pages_ur[$i]);
				$p->SetProprity($pages_pr[$i]);
				$p->SetChangeFreq($pages_cf[$i]);
				//Try to parse last modified, if -1 (note ===) automatic will be used (0)
				$lm=(!empty($pages_lm[$i])?strtotime($pages_lm[$i],time()):-1);
				if($lm===-1) $p->setLastMod(0);
				else $p->setLastMod($lm);
				//Add it to the array
				array_push($pages,$p);
			}
		}

		return $pages;
	}

	/**
	 * Displays the option page
	 *
	 * @since 3.0
	 * @access public
	 * @author Arne Brachhold
	 */
	public function HtmlShowOptionsPage() {
		global $wp_version;

		$snl = false; //SNL

		$this->sg->Initate();

		$message="";

		if(!empty($_REQUEST["sm_rebuild"])) { //Pressed Button: Rebuild Sitemap
			check_admin_referer('sitemap');


			if(isset($_GET["sm_do_debug"]) && $_GET["sm_do_debug"]=="true") {

				//Check again, just for the case that something went wrong before
				if(!current_user_can("administrator") || !is_super_admin()) {
					echo '<p>Please log in as admin</p>';
					return;
				}

				$oldErr = error_reporting(E_ALL);
				$oldIni = ini_set("display_errors",1);

				echo '<div class="wrap">';
				echo '<h2>' .  __('XML Sitemap Generator for WordPress', 'sitemap') .  " " . $this->sg->GetVersion(). '</h2>';
				echo '<p>This is the debug mode of the XML Sitemap Generator. It will show all PHP notices and warnings as well as the internal logs, messages and configuration.</p>';
				echo '<p style="font-weight:bold; color:red; padding:5px; border:1px red solid; text-align:center;">DO NOT POST THIS INFORMATION ON PUBLIC PAGES LIKE SUPPORT FORUMS AS IT MAY CONTAIN PASSWORDS OR SECRET SERVER INFORMATION!</p>';
				echo "<h3>WordPress and PHP Information</h3>";
				echo '<p>WordPress ' . $GLOBALS['wp_version'] . ' with ' . ' DB ' . $GLOBALS['wp_db_version'] . ' on PHP ' . phpversion() . '</p>';
				echo '<p>Plugin version: ' . $this->sg->GetVersion() . ' (' . $this->sg->GetSvnVersion() . ')';
				echo '<h4>Environment</h4>';
				echo "<pre>";
				$sc = $_SERVER;
				unset($sc["HTTP_COOKIE"]);
				print_r($sc);
				echo "</pre>";
				echo "<h4>WordPress Config</h4>";
				echo "<pre>";
				$opts = array();
				if(function_exists('wp_load_alloptions')) {
					$opts = wp_load_alloptions();
				} else {
					/** @var $wpdb wpdb*/
					global $wpdb;
					$os = $wpdb->get_results( "SELECT option_name, option_value FROM $wpdb->options");
					foreach ( (array) $os as $o ) $opts[$o->option_name] = $o->option_value;
				}

				$popts = array();
				foreach($opts as $k=>$v) {
					//Try to filter out passwords etc...
					if(preg_match("/pass|login|pw|secret|user|usr|key|auth|token/si",$k)) continue;
					$popts[$k] = htmlspecialchars($v);
				}
				print_r($popts);
				echo "</pre>";
				echo '<h4>Sitemap Config</h4>';
				echo "<pre>";
				print_r($this->sg->GetOptions());
				echo "</pre>";
				echo '<h3>Sitemap Content and Errors, Warnings, Notices</h3>';
				echo '<div>';

				$sitemaps = $this->sg->SimulateIndex();

				foreach($sitemaps AS $sitemap) {

					/** @var $s GoogleSitemapGeneratorSitemapEntry */
					$s = $sitemap["data"];

					echo "<h4>Sitemap: <a href=\"" . $s->GetUrl() . "\">" . $sitemap["type"] . "/" . ($sitemap["params"]?$sitemap["params"]:"(No parameters)") .  "</a> by " . $sitemap["caller"]["class"] . "</h4>";

					$res = $this->sg->SimulateSitemap($sitemap["type"], $sitemap["params"]);

					echo "<ul style='padding-left:10px;'>";
					foreach($res AS $s) {
						/** @var $d GoogleSitemapGeneratorSitemapEntry */
						$d = $s["data"];
						echo "<li>" . $d->GetUrl() . "</li>";
					}
					echo "</ul>";
				}

				$status = GoogleSitemapGeneratorStatus::Load();
				echo '</div>';
				echo '<h3>MySQL Queries</h3>';
				if(defined('SAVEQUERIES') && SAVEQUERIES) {
					echo '<pre>';
					var_dump($GLOBALS['wpdb']->queries);
					echo '</pre>';

					$total = 0;
					foreach($GLOBALS['wpdb']->queries as $q) {
						$total+=$q[1];
					}
					echo '<h4>Total Query Time</h4>';
					echo '<pre>' . count($GLOBALS['wpdb']->queries) . ' queries in ' . round($total,2) . ' seconds.</pre>';
				} else {
					echo '<p>Please edit wp-db.inc.php in wp-includes and set SAVEQUERIES to true if you want to see the queries.</p>';
				}
				echo "<h3>Build Process Results</h3>";
				echo "<pre>";
				print_r($status);
				echo "</pre>";
				echo '<p>Done. <a href="' . wp_nonce_url($this->sg->GetBackLink() . "&sm_rebuild=true&sm_do_debug=true",'sitemap') . '">Rebuild</a> or <a href="' . $this->sg->GetBackLink() . '">Return</a></p>';
				echo '<p style="font-weight:bold; color:red; padding:5px; border:1px red solid; text-align:center;">DO NOT POST THIS INFORMATION ON PUBLIC PAGES LIKE SUPPORT FORUMS AS IT MAY CONTAIN PASSWORDS OR SECRET SERVER INFORMATION!</p>';
				echo '</div>';
				@error_reporting($oldErr);
				@ini_set("display_errors",$oldIni);
				return;
			} else {

				$redirURL = $this->sg->GetBackLink() . '&sm_fromrb=true';

				//Redirect so the sm_rebuild GET parameter no longer exists.
				@header("location: " . $redirURL);
				//If there was already any other output, the header redirect will fail
				echo '<script type="text/javascript">location.replace("' . $redirURL . '");</script>';
				echo '<noscript><a href="' . $redirURL . '">Click here to continue</a></noscript>';
				exit;
			}
		} else if (!empty($_POST['sm_update'])) { //Pressed Button: Update Config
			check_admin_referer('sitemap');

			if(isset($_POST['sm_b_style']) && $_POST['sm_b_style'] == $this->sg->getDefaultStyle()) {
				$_POST['sm_b_style_default'] = true;
				$_POST['sm_b_style'] = '';
			}

			foreach($this->sg->GetOptions() as $k=>$v) {

				//Skip some options if the user is not super admin...
				if(!is_super_admin() && in_array($k,array("sm_b_time","sm_b_memory","sm_b_style","sm_b_style_default"))) {
					continue;
				}

				//Check vor values and convert them into their types, based on the category they are in
				if(!isset($_POST[$k])) $_POST[$k]=""; // Empty string will get false on 2bool and 0 on 2float

				//Options of the category "Basic Settings" are boolean, except the filename and the autoprio provider
				if(substr($k,0,5)=="sm_b_") {
					if($k=="sm_b_prio_provider" || $k == "sm_b_style" || $k == "sm_b_memory" || $k == "sm_b_baseurl") {
						if($k=="sm_b_filename_manual" && strpos($_POST[$k],"\\")!==false){
							$_POST[$k]=stripslashes($_POST[$k]);
						} else if($k=="sm_b_baseurl") {
							$_POST[$k] = trim($_POST[$k]);
							if(!empty($_POST[$k])) $_POST[$k] = trailingslashit($_POST[$k]);
						}
						$this->sg->SetOption($k,(string) $_POST[$k]);
					} else if($k == "sm_b_time") {
						if($_POST[$k]=='') $_POST[$k] = -1;
						$this->sg->SetOption($k,intval($_POST[$k]));
					} else if($k== "sm_i_install_date") {
						if($this->sg->GetOption('i_install_date')<=0) $this->sg->SetOption($k,time());
					} else if($k=="sm_b_exclude") {
						$IDss = array();
						$IDs = explode(",",$_POST[$k]);
						for($x = 0; $x<count($IDs); $x++) {
							$ID = intval(trim($IDs[$x]));
							if($ID>0) $IDss[] = $ID;
						}
						$this->sg->SetOption($k,$IDss);
					} else if($k == "sm_b_exclude_cats") {
						$exCats = array();
						if(isset($_POST["post_category"])) {
							foreach((array) $_POST["post_category"] AS $vv) if(!empty($vv) && is_numeric($vv)) $exCats[] = intval($vv);
						}
						$this->sg->SetOption($k,$exCats);
					} else {
						$this->sg->SetOption($k,(bool) $_POST[$k]);

					}
				//Options of the category "Includes" are boolean
				} else if(substr($k,0,6)=="sm_in_") {
					if($k=='sm_in_tax') {

						$enabledTaxonomies = array();

						foreach(array_keys((array) $_POST[$k]) AS $taxName) {
							if(empty($taxName) || !taxonomy_exists($taxName)) continue;

							$enabledTaxonomies[] = $taxName;
						}

						$this->sg->SetOption($k,$enabledTaxonomies);

					} else if($k=='sm_in_customtypes') {

						$enabledPostTypes = array();

						foreach(array_keys((array) $_POST[$k]) AS $postTypeName) {
							if(empty($postTypeName) || !post_type_exists($postTypeName)) continue;

							$enabledPostTypes[] = $postTypeName;
						}

						$this->sg->SetOption($k, $enabledPostTypes);

					} else $this->sg->SetOption($k,(bool) $_POST[$k]);
				//Options of the category "Change frequencies" are string
				} else if(substr($k,0,6)=="sm_cf_") {
					$this->sg->SetOption($k,(string) $_POST[$k]);
				//Options of the category "Priorities" are float
				} else if(substr($k,0,6)=="sm_pr_") {
					$this->sg->SetOption($k,(float) $_POST[$k]);
				}
			}

			//Apply page changes from POST
			if(is_super_admin()) $this->sg->SetPages($this->HtmlApplyPages());

			if($this->sg->SaveOptions()) $message.=__('Configuration updated', 'sitemap') . "<br />";
			else $message.=__('Error while saving options', 'sitemap') . "<br />";

			if(is_super_admin()) {
				if($this->sg->SavePages()) $message.=__("Pages saved",'sitemap') . "<br />";
				else $message.=__('Error while saving pages', 'sitemap'). "<br />";
			}

		} else if(!empty($_POST["sm_reset_config"])) { //Pressed Button: Reset Config
			check_admin_referer('sitemap');
			$this->sg->InitOptions();
			$this->sg->SaveOptions();

			$message.=__('The default configuration was restored.','sitemap');
		} else if(!empty(