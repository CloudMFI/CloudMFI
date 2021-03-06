<?php defined('BASEPATH') or exit('No direct script access allowed');


class Erp
{

    public function __construct()
    {

    }

    public function __get($var)
    {
        return get_instance()->$var;
    }

    private function _rglobRead($source, &$array = array())
    {
        if (!$source || trim($source) == "") {
            $source = ".";
        }
        foreach ((array) glob($source . "/*/") as $key => $value) {
            $this->_rglobRead(str_replace("//", "/", $value), $array);
        }
        $hidden_files = glob($source . ".*") and $htaccess = preg_grep('/\.htaccess$/', $hidden_files);
        $files = array_merge(glob($source . "*.*"), $htaccess);
        foreach ($files as $key => $value) {
            $array[] = str_replace("//", "/", $value);
        }
    }

    private function _zip($array, $part, $destination, $output_name = 'erp')
    {
        $zip = new ZipArchive;
        @mkdir($destination, 0777, true);

        if ($zip->open(str_replace("//", "/", "{$destination}/{$output_name}"    . ($part ? '_p' . $part : '') . ".zip"), ZipArchive::CREATE)) {
            foreach ((array) $array as $key => $value) {
                $zip->addFile($value, str_replace(array("../", "./"), null, $value));
            }
            $zip->close();
        }
    }
	public function formatMoneyPurchase($number)
    {
        if ($this->Settings->sac) {
            return ($this->Settings->display_symbol == 1 ? $this->Settings->symbol : '') .
            $this->formatSAC($this->formatDecimal($number)) .
            ($this->Settings->display_symbol == 2 ? $this->Settings->symbol : '');
        }
        $decimals = $this->Settings->purchase_decimals;
        $ts = $this->Settings->thousands_sep == '0' ? ' ' : $this->Settings->thousands_sep;
        $ds = $this->Settings->decimals_sep;
        return ($this->Settings->display_symbol == 1 ? $this->Settings->symbol : '') .
        number_format($number, $decimals, $ds, $ts) .
        ($this->Settings->display_symbol == 2 ? $this->Settings->symbol : '');
    }
	
	
	/************function Covert Month****************/
	function KhmerMonth($m){
		if($m==1){
			return "មករា";
		}else if($m==2){
			return "កុម្ភៈ";
		}else if($m==3){
			return "មិនា";
		}else if($m==4){
			return "មេសា";
		}else if($m==5){
			return "ឧសភា";
		}else if($m==6){
			return "មិថុនា";
		}else if($m==7){
			return "កក្កដា";
		}else if($m==8){
			return "សីហា";
		}else if($m==9){
			return "កញ្ញា";
		}else if($m==10){
			return "តុលា";
		}else if($m==11){
			return "វិច្ឆិកា";
		}else if($m==12){
			return "ធ្នូ";
		}
	}

	/**************************************************/

	######## convert Date to Khmer Number #########
	function KhmerNumDate ($numDate){
		$numDate = str_replace('1', '១', $numDate);
		$numDate = str_replace('2', '២', $numDate);
		$numDate = str_replace('3', '៣', $numDate);
		$numDate = str_replace('4', '៤', $numDate);
		$numDate = str_replace('5', '៥', $numDate);
		$numDate = str_replace('6', '៦', $numDate);
		$numDate = str_replace('7', '៧', $numDate);
		$numDate = str_replace('8', '៨', $numDate);
		$numDate = str_replace('9', '៩', $numDate);
		$numDate = str_replace('0', '០', $numDate); 
		return $numDate;
	}
	
	
    public function formatMoney($number)
    {
        if ($this->Settings->sac) {
            return ($this->Settings->display_symbol == 1 ? $this->Settings->symbol : '') .
            $this->formatSAC($this->formatDecimal($number)) .
            ($this->Settings->display_symbol == 2 ? $this->Settings->symbol : '');
        }
        $decimals = $this->Settings->decimals;
        $ts = $this->Settings->thousands_sep == '0' ? ' ' : $this->Settings->thousands_sep;
        $ds = $this->Settings->decimals_sep;
        return ($this->Settings->display_symbol == 1 ? $this->Settings->symbol : '') .
        number_format($number, $decimals, $ds, $ts) .
        ($this->Settings->display_symbol == 2 ? $this->Settings->symbol : '');
    }
	
    public function formatQuantity($number, $decimals = null)
    {
        if (!$decimals) {
            $decimals = $this->Settings->qty_decimals;
        }
        if ($this->Settings->sac) {
            return $this->formatSAC($this->formatDecimal($number, $decimals));
        }
        $ts = $this->Settings->thousands_sep == '0' ? ' ' : $this->Settings->thousands_sep;
        $ds = $this->Settings->decimals_sep;
        return number_format($number, $decimals, $ds, $ts);
    }

    public function formatNumber($number, $decimals = null)
    {
        if (!$decimals) {
            $decimals = $this->Settings->decimals;
        }
        if ($this->Settings->sac) {
            return $this->formatSAC($this->formatDecimal($number, $decimals));
        }
        $ts = $this->Settings->thousands_sep == '0' ? ' ' : $this->Settings->thousands_sep;
        $ds = $this->Settings->decimals_sep;
        return number_format($number, $decimals, $ds, $ts);
    }
	
    public function formatDecimal($number, $decimals = null)
    {
        if (!is_numeric($number)) {
            return null;
        }
        if (!$decimals) {
            $decimals = $this->Settings->decimals;
        }
        return number_format($number, $decimals, '.', '');
    }
	
	public function formatPurDecimal($number, $decimals = null)
    {
        if (!is_numeric($number)) {
            return null;
        }
        if (!$decimals) {
            $decimals = $this->Settings->purchase_decimals;
        }
        return number_format($number, $decimals, '.', '');
    }
    
    public function clear_tags($str)
    {
        return htmlentities(
            strip_tags($str,
                '<span><div><a><br><p><b><i><u><img><blockquote><small><ul><ol><li><hr><big><pre><code><strong><em><table><tr><td><th><tbody><thead><tfoot><h3><h4><h5><h6>'
            ),
            ENT_QUOTES | ENT_XHTML | ENT_HTML5,
            'UTF-8'
        );
    }

    public function decode_html($str)
    {
        return html_entity_decode($str, ENT_QUOTES | ENT_XHTML | ENT_HTML5, 'UTF-8');
    }

    public function roundMoney($num, $nearest = 0.05)
    {
        return round($num * (1 / $nearest)) * $nearest;
    }

    public function roundNumber($number, $toref = null)
    {
        switch ($toref) {
            case 1:
                $rn = round($number * 20) / 20;
                break;
            case 2:
                $rn = round($number * 2) / 2;
                break;
            case 3:
                $rn = round($number);
                break;
            case 4:
                $rn = ceil($number);
                break;
            default:
                $rn = $number;
        }
        return $rn;
    }
	
    public function unset_data($ud)
    {
        if ($this->session->userdata($ud)) {
            $this->session->unset_userdata($ud);
            return true;
        }
        return false;
    }

    public function hrsd($sdate)
    {
        if ($sdate) {
            return date($this->dateFormats['php_sdate'], strtotime($sdate));
        } else {
            return '0000-00-00';
        }
    }

    public function hrld($ldate)
    {
        if ($ldate) {
            return date($this->dateFormats['php_ldate'], strtotime($ldate));
        } else {
            return '0000-00-00 00:00:00';
        }
    }

    public function fsd($inv_date)
    {
        if ($inv_date) {
            $jsd = $this->dateFormats['js_sdate'];
            if ($jsd == 'dd-mm-yyyy' || $jsd == 'dd/mm/yyyy' || $jsd == 'dd.mm.yyyy') {
                $date = substr($inv_date, -4) . "-" . substr($inv_date, 3, 2) . "-" . substr($inv_date, 0, 2);
            } elseif ($jsd == 'mm-dd-yyyy' || $jsd == 'mm/dd/yyyy' || $jsd == 'mm.dd.yyyy') {
                $date = substr($inv_date, -4) . "-" . substr($inv_date, 0, 2) . "-" . substr($inv_date, 3, 2);
            } else {
                $date = $inv_date;
            }
            return $date;
        } else {
            return '0000-00-00';
        }
    }

    public function fld($ldate)
    {
        if ($ldate) {
            $date = explode(' ', $ldate);
            $jsd = $this->dateFormats['js_sdate'];
            $inv_date = $date[0];
            $time = isset($date[1])? $date[1]:'';
            if ($jsd == 'dd-mm-yyyy' || $jsd == 'dd/mm/yyyy' || $jsd == 'dd.mm.yyyy') {
                $date = substr($inv_date, -4) . "-" . substr($inv_date, 3, 2) . "-" . substr($inv_date, 0, 2) . " " . $time;
            } elseif ($jsd == 'mm-dd-yyyy' || $jsd == 'mm/dd/yyyy' || $jsd == 'mm.dd.yyyy') {
                $date = substr($inv_date, -4) . "-" . substr($inv_date, 0, 2) . "-" . substr($inv_date, 3, 2) . " " . $time;
			}elseif($jsd == 'yyyy-mm-dd'){
				$date = $inv_date . ' ' . $time;
			} else {
                $date = $inv_date;
            }
			
			/* Error date 0000-00-00 00:00:00 */
            //return $date." ".$time;
			return $date;
        } else {
            return '0000-00-00 00:00:00';
        }
    }

    public function send_email($to, $subject, $message, $from = null, $from_name = null, $attachment = null, $cc = null, $bcc = null)
    {
        $this->load->library('email');
        $config['useragent'] = "Stock Manager Advance";
        $config['protocol'] = $this->Settings->protocol;
        $config['mailtype'] = "html";
        $config['crlf'] = "\r\n";
        $config['newline'] = "\r\n";
        if ($this->Settings->protocol == 'sendmail') {
            $config['mailpath'] = $this->Settings->mailpath;
        } elseif ($this->Settings->protocol == 'smtp') {
            $this->load->library('encrypt');
            $config['smtp_host'] = $this->Settings->smtp_host;
            $config['smtp_user'] = $this->Settings->smtp_user;
            $config['smtp_pass'] = $this->encrypt->decode($this->Settings->smtp_pass);
            $config['smtp_port'] = $this->Settings->smtp_port;
            if (!empty($this->Settings->smtp_crypto)) {
                $config['smtp_crypto'] = $this->Settings->smtp_crypto;
            }
        }

        $this->email->initialize($config);

        if ($from && $from_name) {
            $this->email->from($from, $from_name);
        } elseif ($from) {
            $this->email->from($from, $this->Settings->site_name);
        } else {
            $this->email->from($this->Settings->default_email, $this->Settings->site_name);
        }

        $this->email->to($to);
        if ($cc) {
            $this->email->cc($cc);
        }
        if ($bcc) {
            $this->email->bcc($bcc);
        }
        $this->email->subject($subject);
        $this->email->message($message);
        if ($attachment) {
            if (is_array($attachment)) {
                foreach ($attachment as $file) {
                    $this->email->attach($file);
                }
            } else {
                $this->email->attach($attachment);
            }
        }

        if ($this->email->send()) {
            //echo $this->email->print_debugger(); die();
            return true;
        } else {
            //echo $this->email->print_debugger(); die();
            return false;
        }
    }

    public function checkPermissions($action = null, $js = null, $module = null)
    {
        if (!$this->actionPermissions($action, $module)) {
            $this->session->set_flashdata('error', lang("access_denied"));
            if ($js) {
                die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : site_url('welcome')) . "'; }, 10);</script>");
            } else {
                redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'welcome');
            }
        }
    }

    public function actionPermissions($action = null, $module = null)
    {
        if ($this->Owner || $this->Admin) {
            if ($this->Admin && stripos($action, 'delete') !== false) {
                return false;
            }
            return true;
        } elseif ($this->Customer || $this->Supplier) {
            return false;
        } else {
            if (!$module) {
                $module = $this->m;
            }
            if (!$action) {
                $action = $this->v;
            }
            //$gp = $this->site->checkPermissions();
            if ($this->GP[$module . '-' . $action] == 1) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function save_barcode($text = null, $bcs = 'code128', $height = 56, $stext = 1, $sq = null)
    {
        $file_name = 'assets/uploads/barcode' . $this->session->userdata('user_id') . ($sq ? $sq : '') . '.png';
        $drawText = ($stext != 1) ? false : true;
        $this->load->library('zend');
        $this->zend->load('Zend/Barcode');
        $barcodeOptions = array('text' => $text, 'barHeight' => $height, 'drawText' => $drawText, 'factor' => 1);
        $rendererOptions = array('imageType' => 'png', 'horizontalPosition' => 'center', 'verticalPosition' => 'middle');
        $image = Zend_Barcode::draw($bcs, 'image', $barcodeOptions, $rendererOptions);
        if (imagepng($image, $file_name)) {
            imagedestroy($image);
            $bc = file_get_contents($file_name);
            $bcimage = base64_encode($bc);
            return $bcimage;
        }
        return false;
    }

    public function qrcode($type = 'text', $text = 'PHP QR Code', $size = 2, $level = 'H', $sq = null)
    {
        $file_name = 'assets/uploads/qrcode' . $this->session->userdata('user_id') . ($sq ? $sq : '') . '.png';
        if ($type == 'link') {
            $text = urldecode($text);
        }
        $this->load->library('phpqrcode');
        $config = array('data' => $text, 'size' => $size, 'level' => $level, 'savename' => $file_name);
        $this->phpqrcode->generate($config);
        $qr = file_get_contents($file_name);
        $qrimage = base64_encode($qr);
        return $qrimage;
    }

    public function generate_pdf($content, $name = 'download.pdf', $output_type = null, $footer = null, $margin_bottom = null, $header = null, $margin_top = null, $orientation = 'P')
    {
        if (!$output_type) {
            $output_type = 'D';
        }
        if (!$margin_bottom) {
            $margin_bottom = 10;
        }
        if (!$margin_top) {
            $margin_top = 20;
        }
        $this->load->library('pdf');
        $pdf = new mPDF('utf-8', 'A4-' . $orientation, '13', '', 10, 10, $margin_top, $margin_bottom, 9, 9);
        $pdf->debug = false;
        $pdf->autoScriptToLang = true;
        $pdf->autoLangToFont = true;
        $pdf->SetProtection(array('print')); // You pass 2nd arg for user password (open) and 3rd for owner password (edit)
        //$pdf->SetProtection(array('print', 'copy')); // Comment above line and uncomment this to allow copying of content
        $pdf->SetTitle($this->Settings->site_name);
        $pdf->SetAuthor($this->Settings->site_name);
        $pdf->SetCreator($this->Settings->site_name);
        $pdf->SetDisplayMode('fullpage');
        $stylesheet = file_get_contents('assets/bs/bootstrap.min.css');
        $pdf->WriteHTML($stylesheet, 1);
        // $pdf->SetFooter($this->Settings->site_name.'||{PAGENO}/{nbpg}', '', TRUE); // For simple text footer

        if (is_array($content)) {
            $pdf->SetHeader($this->Settings->site_name.'||{PAGENO}/{nbpg}', '', TRUE); // For simple text header
            $as = sizeof($content);
            $r = 1;
            foreach ($content as $page) {
                $pdf->WriteHTML($page['content']);
                if (!empty($page['footer'])) {
                    $pdf->SetHTMLFooter('<p class="text-center">' . $page['footer'] . '</p>', '', true);
                }
                if ($as != $r) {
                    $pdf->AddPage();
                }
                $r++;
            }

        } else {

            $pdf->WriteHTML($content);
            if ($header != '') {
                $pdf->SetHTMLHeader('<p class="text-center">' . $header . '</p>', '', true);
            }
            if ($footer != '') {
                $pdf->SetHTMLFooter('<p class="text-center">' . $footer . '</p>', '', true);
            }

        }

        if ($output_type == 'S') {
            $file_content = $pdf->Output('', 'S');
            write_file('assets/uploads/' . $name, $file_content);
            return 'assets/uploads/' . $name;
        } else {
            $pdf->Output($name, $output_type);
        }
    }

    public function print_arrays()
    {
        $args = func_get_args();
        echo "<pre>";
        foreach ($args as $arg) {
            print_r($arg);
        }
        echo "</pre>";
        die();
    }

    public function logged_in()
    {
        return (bool) $this->session->userdata('identity');
    }

    public function in_group($check_group, $id = false)
    {
        if ( ! $this->logged_in()) {
            return false;
        }
        $id || $id = $this->session->userdata('user_id');
        $group = $this->site->getUserGroup($id);
        if ($group->name === $check_group) {
            return true;
        }
        return false;
    }

    public function log_payment($msg, $val = null)
    {
        $this->load->library('logs');
        return (bool) $this->logs->write('payments', $msg, $val);
    }

    public function update_award_points($total, $customer, $user, $scope = null)
    {
        if (!empty($this->Settings->each_spent) && $total >= $this->Settings->each_spent) {
            $company = $this->site->getCompanyByID($customer);
            $points = floor(($total / $this->Settings->each_spent) * $this->Settings->ca_point);
            $total_points = $scope ? $company->award_points - $points : $company->award_points + $points;
            $this->db->update('companies', array('award_points' => $total_points), array('id' => $customer));
        }
        if($saleman){
            if (!empty($this->Settings->each_sale) && !$this->Customer && $total >= $this->Settings->each_sale) {
                $staff = $this->site->getUser($saleman);
                $points = floor(($total / $this->Settings->each_sale) * $this->Settings->sa_point);
                $total_points = $scope ? $staff->award_points - $points : $staff->award_points + $points;
                $this->db->update('users', array('award_points' => $total_points), array('id' => $saleman));
            }
        }else{
            if (!empty($this->Settings->each_sale) && !$this->Customer && $total >= $this->Settings->each_sale) {
                $staff = $this->site->getUser($user);
                $points = floor(($total / $this->Settings->each_sale) * $this->Settings->sa_point);
                $total_points = $scope ? $staff->award_points - $points : $staff->award_points + $points;
                $this->db->update('users', array('award_points' => $total_points), array('id' => $user));
            }
        }
        return true;
    }

    public function zip($source = null, $destination = "./", $output_name = 'erp', $limit = 5000)
    {
        if (!$destination || trim($destination) == "") {
            $destination = "./";
        }

        $this->_rglobRead($source, $input);
        $maxinput = count($input);
        $splitinto = (($maxinput / $limit) > round($maxinput / $limit, 0)) ? round($maxinput / $limit, 0) + 1 : round($maxinput / $limit, 0);

        for ($i = 0; $i < $splitinto; $i++) {
            $this->_zip(array_slice($input, ($i * $limit), $limit, true), $i, $destination, $output_name);
        }

        unset($input);
        return;
    }

    public function unzip($source, $destination = './')
    {

        // @chmod($destination, 0777);
        $zip = new ZipArchive;
        if ($zip->open(str_replace("//", "/", $source)) === true) {
            $zip->extractTo($destination);
            $zip->close();
        }
        // @chmod($destination,0755);

        return true;
    }

    public function view_rights($check_id, $js = null)
    {
        if (!$this->Owner && !$this->Admin) {
            if ($check_id != $this->session->userdata('user_id')) {
                $this->session->set_flashdata('warning', $this->data['access_denied']);
                if ($js) {
                    die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'welcome') . "'; }, 10);</script>");
                } else {
                    redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'welcome');
                }
            }
        }
        return true;
    }

    public function makecomma($input)
    {
        if (strlen($input) <= 2) {return $input;}
        $length = substr($input, 0, strlen($input) - 2);
        $formatted_input = $this->makecomma($length) . "," . substr($input, -2);
        return $formatted_input;
    }

    public function formatSAC($num)
    {
        $pos = strpos((string) $num, ".");
        if ($pos === false) {$decimalpart = "00";} else {
            $decimalpart = substr($num, $pos + 1, 2);
            $num = substr($num, 0, $pos);}

        if (strlen($num) > 3 & strlen($num) <= 12) {
            $last3digits = substr($num, -3);
            $numexceptlastdigits = substr($num, 0, -3);
            $formatted = $this->makecomma($numexceptlastdigits);
            $stringtoreturn = $formatted . "," . $last3digits . "." . $decimalpart;
        } elseif (strlen($num) <= 3) {
            $stringtoreturn = $num . "." . $decimalpart;
        } elseif (strlen($num) > 12) {
            $stringtoreturn = number_format($num, 2);
        }

        if (substr($stringtoreturn, 0, 2) == "-,") {$stringtoreturn = "-" . substr($stringtoreturn, 2);}

        return $stringtoreturn;
    }

    public function md()
    {
        die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'welcome') . "'; }, 10);</script>");
    }

    public function analyze_term($term)
    {
        $spos = strpos($term, $this->Settings->barcode_separator);
        if ($spos !== false) {
            $st = explode($this->Settings->barcode_separator, $term);
            $sr = trim($st[0]);
            $option_id = trim($st[1]);
        } else {
            $sr = $term;
            $option_id = false;
        }
        return array('term' => $sr, 'option_id' => $option_id);
    }

    public function paid_opts($paid_by = null, $purchase = false)
    {
        $opts = '
        <option value="cash"'.($paid_by && $paid_by == 'cash' ? ' selected="selected"' : '').'>'.lang("cash").'</option>
        <option value="gift_card"'.($paid_by && $paid_by == 'gift_card' ? ' selected="selected"' : '').'>'.lang("gift_card").'</option>
        <option value="CC"'.($paid_by && $paid_by == 'CC' ? ' selected="selected"' : '').'>'.lang("CC").'</option>
        <option value="Cheque"'.($paid_by && $paid_by == 'Cheque' ? ' selected="selected"' : '').'>'.lang("cheque").'</option>
        <option value="other"'.($paid_by && $paid_by == 'other' ? ' selected="selected"' : '').'>'.lang("other").'</option>';
        if (!$purchase) {
            $opts .= '<option value="deposit"'.($paid_by && $paid_by == 'deposit' ? ' selected="selected"' : '').'>'.lang("deposit").'</option>';
        }
        return $opts;
    }

    public function send_json($data)
    {
        header('Content-Type: application/json');
        die(json_encode($data));
        exit;
    }
    
    
    /* Sikeat : Fraction */
	public function fraction($num){
		$intpart = floor( $num );
		$fraction = $num - $intpart;
		return $this->formatDecimal($fraction);
	}
    
    public function floorFigure($figure, $decimals){
        if(!$decimals){
            $decimals = 2;
        }
        return number_format((floor($figure*100)/100), $decimals);
    }
	
	function numberOfDecimals($value)
	{
		if ((int)$value == $value)
		{
			return 0;
		}
		else if (! is_numeric($value))
		{
			// throw new Exception('numberOfDecimals: ' . $value . ' is not a number!');
			return false;
		}

		return strlen($value) - strrpos($value, '.') - 1;
	}
    
    public function removeComma($str){
        return number_format(preg_replace("/[^0-9,.]/", "", $str));
    }

	function convert_number_to_words($number) {
		
		$number = str_replace(',','',$number)-0;
   
		$hyphen      = '-';
		$conjunction = ' and ';
		$separator   = ', ';
		$negative    = 'negative ';
		$decimal     = ' point ';
		$dictionary  = array(
			0                   => 'zero',
			1                   => 'one',
			2                   => 'two',
			3                   => 'three',
			4                   => 'four',
			5                   => 'five',
			6                   => 'six',
			7                   => 'seven',
			8                   => 'eight',
			9                   => 'nine',
			10                  => 'ten',
			11                  => 'eleven',
			12                  => 'twelve',
			13                  => 'thirteen',
			14                  => 'fourteen',
			15                  => 'fifteen',
			16                  => 'sixteen',
			17                  => 'seventeen',
			18                  => 'eighteen',
			19                  => 'nineteen',
			20                  => 'twenty',
			30                  => 'thirty',
			40                  => 'fourty',
			50                  => 'fifty',
			60                  => 'sixty',
			70                  => 'seventy',
			80                  => 'eighty',
			90                  => 'ninety',
			100                 => 'hundred',
			1000                => 'thousand',
			1000000             => 'million',
			1000000000          => 'billion',
			1000000000000       => 'trillion',
			1000000000000000    => 'quadrillion',
			1000000000000000000 => 'quintillion'
		);
	   
		if (!is_numeric($number)) {
			return false;
		}
	   
		if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
			// overflow
			trigger_error(
				'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
				E_USER_WARNING
			);
			return false;
		}

		if ($number < 0) {
			return $negative . $this->convert_number_to_words(abs($number));
		}
	   
		$string = $fraction = null;
	   
		if (strpos($number, '.') !== false) {
			list($number, $fraction) = explode('.', $number);
		}
	   
		switch (true) {
			case $number < 21:
				$string = $dictionary[$number];
				break;
			case $number < 100:
				$tens   = ((int) ($number / 10)) * 10;
				$units  = $number % 10;
				$string = $dictionary[$tens];
				if ($units) {
					$string .= $hyphen . $dictionary[$units];
				}
				break;
			case $number < 1000:
				$hundreds  = $number / 100;
				$remainder = $number % 100;
				$string = $dictionary[$hundreds] . ' ' . $dictionary[100];
				if ($remainder) {
					$string .= $conjunction . $this->convert_number_to_words($remainder);
				}
				break;
			default:
				$baseUnit = pow(1000, floor(log($number, 1000)));
				$numBaseUnits = (int) ($number / $baseUnit);
				$remainder = $number % $baseUnit;
				$string = $this->convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
				if ($remainder) {
					$string .= $remainder < 100 ? $conjunction : $separator;
					$string .= $this->convert_number_to_words($remainder);
				}
				break;
		}
	   
		if (null !== $fraction && is_numeric($fraction)) {
			$string .= $decimal;
			$words = array();
			foreach (str_split((string) $fraction) as $number) {
				$words[] = $dictionary[$number];
			}
			$string .= implode(' ', $words);
		}
	   
		return $string;
	}
	
	function getLastPaymentDate($term_in_days = NULL, $frequency = NULL, $start_date = NULL)
	{
		$term = round($term_in_days/$frequency);
		$j=0;
		for($i=1;$i<=$term;$i++) {
			if($i == 1) {
				$dateline = $start_date;
			} else {
				$dateline = date('Y-m-d', strtotime("+".$j." days", strtotime($start_date)));
			}
			if($frequency == 30) {
				$day_of_month = date('t', strtotime($dateline));
			}else {
				$day_of_month = $frequency;
			}
			$j += $day_of_month;
		}
		return $dateline;
	}
	
	
	function getPaymentSchedule($sale_id = NULL, $lease_amount = NULL, $rate_type = NULL, $interest = NULL, $term_in_days = NULL, $frequency = NULL, $start_date = NULL, $app_date = NULL, $currency = NULL, $principle_fq = NULL)
	{
		///$this->erp->print_arrays($app_date);
		$term = round($term_in_days/$frequency);
		$payment_schedule = '';
		if($rate_type == '1') {
			$terms = ($frequency > 1) ? $term : round($term / 1.4);	
			$principles = $lease_amount/$terms;
			$principle = $this->erp->roundUpMoney($principles, $currency);
			$lease_amt = $lease_amount;
			$interest_rate = 0;
			$j=0;
			$days = 0;
			
			for($i=1;$i<=$terms;$i++) {
				if($i == 1) {
					$st_dateline = $start_date;
					$dateline = $this->site->getNoneHoliday($start_date);
				} else {
					$st_dateline = $deadline;
					$dateline = $this->site->getNoneHoliday(date('Y-m-d', strtotime("+".$j." days", strtotime($start_date))));					
				}
				$day = date('l',strtotime($dateline));
				$deadline = $this->site->getWeekendPayments($day, $dateline);
				$n = ((strtotime($deadline) - strtotime($st_dateline)) / (60 * 60 * 24));
				$nameday = date('l',strtotime($deadline));
				
				if($frequency == 30) {
					$day_of_month = date('t', strtotime($dateline));
				}
				else if($frequency == 1){
					if($n > 1) {
						$day_of_month = $n;
					}else {
						$day_of_month = $frequency;
					}
				}
				else{
					$day_of_month = $frequency;
				}
				$days = $day_of_month;
				
				if($i == 1) {
					$ap_date = date('Y-m-d', strtotime($app_date));
					$appr_date = date_create($ap_date);
					$st_date = date_create($start_date);
					$numdays = date_diff($appr_date, $st_date);
					$interest_rate = (($lease_amt * $interest) / $frequency) * $numdays->days;
				} else {
					$interest_rate = (($lease_amt * $interest) / $frequency) * ($days);
				}				
				$principle_amt = str_replace(',', '', $principle);
				$payment_amt = $principle_amt + $interest_rate;
				if($i == $terms){
					$payment_schedule [] = array(
													'period' 	=> $i,
													'sale_id' 	=> $sale_id,
													'type' 		=> $rate_type,
													'dateline' 	=> $deadline,
													'principle' => str_replace(',', '', $lease_amt),
													'interest' 	=> $interest_rate,
													'payment' 	=> $payment_amt,
													'balance' 	=> 0,
												);
				} else {
					$lease_amt -= $principle_amt;
					$payment_schedule [] = array(
													
													'period'	=> $i,
													'sale_id' 	=> $sale_id,
													'type' 		=> $rate_type,
													'dateline' 	=> $deadline,
													'principle' => str_replace(',', '', $principle_amt),
													'interest' 	=> $interest_rate,
													'payment' 	=> $payment_amt,
													'balance' 	=> (($lease_amt <= 0)? 0:$lease_amt),
												);
				}
				$j += $day_of_month;
			}
			//$this->erp->print_arrays($payment_schedule);
			return $payment_schedule;
		} else if($rate_type == '2') {
			$terms = ($frequency > 1) ? $term : round($term / 1.4);	
			$payment_amt = (($lease_amount * $interest)*((pow((1+$interest),$terms))/(pow((1+$interest),$terms)-1)));
			$lease_amt = $lease_amount;
			$j=0;
			$days = 0;
			for($i=1;$i<=$terms;$i++) {
				if($i == 1) {
					$dateline = $this->site->getNoneHoliday($start_date);
				} else {
					$dateline = $this->site->getNoneHoliday(date('Y-m-d', strtotime("+".$j." days", strtotime($start_date))));
				}
				$day = date('l',strtotime($dateline));
				$deadline = $this->site->getWeekendPayments($day, $dateline);
				$nameday = date('l',strtotime($deadline));
				
				if($frequency == 30) {
					$day_of_month = date('t', strtotime($dateline));
				}else {
					if($frequency == 1) {
						if($nameday == "Friday") {
							$day_of_month = $frequency + 2;
						}else if($nameday == "Saturday") {
							$day_of_month = $frequency + 1;
						} else{
							$day_of_month = $frequency;
						}
					}else {
						$day_of_month = $frequency;
					}
				}
				$days = $day_of_month;
				if($i == 1) {
					$ap_date = date('Y-m-d',strtotime($app_date));
					$appr_date = date_create($ap_date);
					$st_date = date_create($start_date);					
					$numdays = date_diff($appr_date, $st_date);
					$interest_rate = (($lease_amt * $interest) / $frequency) * $numdays->days;
				} else {
					$interest_rate = (($lease_amt * $interest) / $frequency) * ($days);
				}
				
				$principles = $payment_amt - $interest_rate;
				$principle = $this->erp->roundUpMoney($principles, $currency);
				$principle_amt = str_replace(',', '', $principle);
				if($i == $terms) {
					$payment_schedule [] = array(
													'period' 	=> $i,
													'sale_id'	=> $sale_id,
													'type' 		=> $rate_type,
													'dateline' 	=> $deadline,
													'principle' => str_replace(',', '', $lease_amt),
													'interest' 	=> $interest_rate,
													'payment' 	=> $payment_amt,
													'balance' 	=> (($lease_amt <= 0)? 0:$lease_amt),
												);
											
				}else {
					$lease_amt -= $principle_amt;
					$payment_schedule [] = array(
													'period' 	=> $i,
													'sale_id' 	=> $sale_id,
													'type' 		=> $rate_type,
													'dateline' 	=> $deadline,
													'principle' => str_replace(',', '', $principle_amt),
													'interest' 	=> $interest_rate,
													'payment' 	=> $payment_amt,
													'balance' 	=> (($lease_amt <= 0)? 0:$lease_amt),
												);
				}
				$j += $day_of_month;
			}
			return $payment_schedule;
		} else if($rate_type == '3') {
			$terms = ($frequency > 1) ? $term : round($term / 1.4);
			$principles = $lease_amount/$terms;
			$principle = $this->erp->roundUpMoney($principles, $currency);
			$interest_rate = $lease_amount * $interest;
			$lease_amt = $lease_amount;
			$j=0;
			$days = 0;
			
			for($i=1;$i<=$terms;$i++) {
				if($i == 1) {
					$dateline = $this->site->getNoneHoliday($start_date);
				} else {
					$dateline = $this->site->getNoneHoliday(date('Y-m-d', strtotime("+".$j." days", strtotime($start_date))));
				}
				$day = date('l',strtotime($dateline));
				$deadline = $this->site->getWeekendPayments($day, $dateline);
				$nameday = date('l',strtotime($deadline));
				
				if($frequency == 30) {
					$day_of_month = date('t', strtotime($dateline));
				}else {
					if($frequency == 1) {
						if($nameday == "Friday") {
							$day_of_month = $frequency + 2;
						}else if($nameday == "Saturday") {
							$day_of_month = $frequency + 1;
						} else{
							$day_of_month = $frequency;
						}
					}else {
						$day_of_month = $frequency;
					}
				}
				$days = $day_of_month;
				if($i == 1) {
					$ap_date = date('Y-m-d',strtotime($app_date));
					$appr_date = date_create($ap_date);
					$st_date = date_create($start_date);
					$numdays = date_diff($appr_date, $st_date);
					$interest_rate = (($lease_amt * $interest) / $frequency) * $numdays->days;
				} else {
					$interest_rate = (($lease_amt * $interest) / $frequency) * ($days);
				}
				$principle_amt = str_replace(',', '', $principle);
				$payment_amt = $principle_amt + $interest_rate;
				if($i == $terms) {
					$payment_schedule [] = array(
													'period' 	=> $i,
													'sale_id' 	=> $sale_id,
													'type' 		=> $rate_type,
													'dateline' 	=> $deadline,
													'principle' => str_replace(',', '', $lease_amt),
													'interest' 	=> $interest_rate,
													'payment' 	=> $payment_amt,
													'balance' 	=> (($lease_amt <= 0)? 0:$lease_amt),
												);
											
				}else {
					$lease_amt -= $principle_amt;
					$payment_schedule [] = array(
													'period' 	=> $i,
													'sale_id' 	=> $sale_id,
													'type' 		=> $rate_type,
													'dateline' 	=> $deadline,
													'principle' => str_replace(',', '', $principle_amt),
													'interest' 	=> $interest_rate,
													'payment' 	=> $payment_amt,
													'balance' 	=> (($lease_amt <= 0)? 0:$lease_amt),
												);
				}
				$j += $day_of_month;
			}
			return $payment_schedule;
		} else if($rate_type == '4') {
			$terms = ($frequency > 1) ? $term : round($term / 1.4);			
			$principles = $lease_amount/$terms;
			$principle = $this->erp->roundUpMoney($principles, $currency);
			$lease_amt = $lease_amount;
			
			$interests = ($lease_amt * $interest) / $frequency;
			$loan_days = $frequency * $term_in_days;
			$interest_rate = ($interests * $loan_days) / $terms;
			
			$j=0;
			$days = 0;		    
			for($i=1;$i<=$terms;$i++) {
				if($i == 1) {
					$dateline = $this->site->getNoneHoliday($start_date);
				} else {
					$dateline = $this->site->getNoneHoliday(date('Y-m-d', strtotime("+".$j." days", strtotime($start_date))));					
				}
				$day = date('l',strtotime($dateline));
				$dateline = $this->site->getWeekendPayments($day, $dateline);
				$nameday = date('l',strtotime($deadline));
				
				if($frequency == 30) {
					$day_of_month = date('t', strtotime($dateline));
				}else {
					if($frequency == 1) {
						if($nameday == "Friday") {
							$day_of_month = $frequency + 2;
						}else if($nameday == "Saturday") {
							$day_of_month = $frequency + 1;
						} else{
							$day_of_month = $frequency;
						}
					}else {
						$day_of_month = $frequency;
					}
				}
				$days = $day_of_month;
				
				$principle_amt = str_replace(',', '', $principle);
				$payment_amt = $principle_amt + $interest_rate;
				if($i == $terms){
					$payment_schedule [] = array(
													'period' 	=> $i,
													'sale_id' 	=> $sale_id,
													'type' 		=> $rate_type,
													'dateline' 	=> $dateline,
													'principle' => str_replace(',', '', $lease_amt),
													'interest' 	=> $interest_rate,
													'payment' 	=> $payment_amt,
													'balance' 	=> 0,
												);
				} else{
					$lease_amt -= $principle_amt;
					$payment_schedule [] = array(
													'period' 	=> $i,
													'sale_id' 	=> $sale_id,
													'type' 		=> $rate_type,
													'dateline' 	=> $dateline,
													'principle' => str_replace(',', '', $principle_amt),
													'interest' 	=> $interest_rate,
													'payment' 	=> $payment_amt,
													'balance' 	=> (($lease_amt <= 0)? 0:$lease_amt),
												);
				}
				$j += $day_of_month;
			}
			//$this->erp->print_arrays($payment_schedule);
			return $payment_schedule;
		}else if($rate_type == '5') {
			$terms = ($frequency > 1) ? $term : round($term / 1.4);	
			$principles = $lease_amount/$terms;
			$principle = $this->erp->roundUpMoney($principles, $currency);
			$lease_amt = $lease_amount;
			$interest_rate = 0;
			$j=0;
			$counter = 1;
			$tprinciple = 0;
			$days = 0;
			for($i=1;$i<=$terms;$i++) { 
				if($i == 1) {
					$dateline = $this->site->getNoneHoliday($start_date);
				} else {
					$dateline = $this->site->getNoneHoliday(date('Y-m-d', strtotime("+".$j." days", strtotime($start_date))));
					///$dateline = date('Y-m-d', strtotime("+".$j." days", strtotime($start_date)));
				}
				$day = date('l',strtotime($dateline));
				$deadline = $this->site->getWeekendPayments($day, $dateline);
				$nameday = date('l',strtotime($deadline));
				
				if($frequency == 30) {
					$day_of_month = date('t', strtotime($dateline));
				}else {
					if($frequency == 1) {
						if($nameday == "Friday") {
							$day_of_month = $frequency + 2;
						}else if($nameday == "Saturday") {
							$day_of_month = $frequency + 1;
						} else{
							$day_of_month = $frequency;
						}
					}else {
						$day_of_month = $frequency;
					}
				}
				$days = $day_of_month;
				
				if($i == 1) {
					$ap_date = date('Y-m-d',strtotime($app_date));
					$appr_date = date_create($ap_date);
					$st_date = date_create($start_date);					
					$numdays = date_diff($appr_date, $st_date);
					$interest_rate = (($lease_amt * $interest) / $frequency) * $numdays->days;
				} else {
					$interest_rate = (($lease_amt * $interest) / $frequency) * ($days);
				}
				$principle_amt = str_replace(',', '', $principle);
				if($counter == $principle_fq || $i == $terms) {
					$principle_amt += $tprinciple;
					$tprinciple = 0;
					$counter = 0;
				}else {
					$tprinciple += $principle_amt;
					$principle_amt = 0;
				}
				$payment_amt = $principle_amt + $interest_rate;
				if($i == $terms) {
					$payment_schedule [] = array(
													'period' 	=> $i,
													'sale_id' 	=> $sale_id,
													'type' 		=> $rate_type,
													'dateline' 	=> $deadline,
													'principle' => str_replace(',', '', $lease_amt),
													'interest' 	=> $interest_rate,
													'payment' 	=> $payment_amt,
													'balance' 	=> 0,
												);
				} else {
					$lease_amt -= $principle_amt;
					$payment_schedule [] = array(
													'period' 	=> $i,
													'sale_id' 	=> $sale_id,
													'type' 		=> $rate_type,
													'dateline' 	=> $deadline,
													'principle' => str_replace(',', '', $principle_amt),
													'interest' 	=> $interest_rate,
													'payment' 	=> $payment_amt,
													'balance' 	=> (($lease_amt <= 0)? 0:$lease_amt),
												);
				}
				$counter++;
				$j += $day_of_month;
			}
			//$this->print_arrays($payment_schedule);
			return $payment_schedule;
		} else if($rate_type == '6') {
			$terms = $term ;			
			$principles = $lease_amount/$terms;
			$principle = $this->erp->roundUpMoney($principles, $currency);
			$lease_amt = $lease_amount;
			
			$j=0;
			$days = 0;		    
			for($i=1;$i<=$terms;$i++) {				
				
				$interest_rate = $lease_amount * $interest;
				if($i == 1) {
					$st_dateline = $start_date;
					$dateline = $this->site->getNoneHoliday($start_date);
				} else {
					$st_dateline = $deadline;
					$dateline = $this->site->getNoneHoliday(date('Y-m-d', strtotime("+".$j." days", strtotime($start_date))));					
				}
				$day = date('l',strtotime($dateline));
				$deadline = $this->site->getWeekendPayments($day, $dateline);
				$n = ((strtotime($deadline) - strtotime($st_dateline)) / (60 * 60 * 24));
				$nameday = date('l',strtotime($deadline));
				
				if($frequency == 30) {
					$day_of_month = date('t', strtotime($dateline));
				}
				else if($frequency == 1){
					if($n > 1) {
						$day_of_month = $n;
					}else {
						$day_of_month = $frequency;
					}
				}
				else{
					$day_of_month = $frequency;
				}
				$days = $day_of_month;
				
				$principle_amt = str_replace(',', '', $principle);
				$payment_amt = $principle_amt + $interest_rate;
				if($i == $terms){
					$payment_schedule [] = array(
													'period' 	=> $i,
													'sale_id' 	=> $sale_id,
													'type' 		=> $rate_type,
													'dateline' 	=> $deadline,
													'principle' => str_replace(',', '', $lease_amt),
													'interest' 	=> $interest_rate,
													'payment' 	=> $payment_amt,
													'balance' 	=> 0,
												);
				} else{
					$lease_amt -= $principle_amt;
					$payment_schedule [] = array(
													'period' 	=> $i,
													'sale_id' 	=> $sale_id,
													'type' 		=> $rate_type,
													'dateline' 	=> $deadline,
													'principle' => str_replace(',', '', $principle_amt),
													'interest' 	=> $interest_rate,
													'payment' 	=> $payment_amt,
													'balance' 	=> (($lease_amt <= 0)? 0:$lease_amt),
												);
				}
				$j += $day_of_month;
			}
			
			return $payment_schedule;
		} else {
			return $payment_schedule;
		}
	}
	
	function getAllTotal($lease_amount = NULL, $rate_type = NULL, $interest = NULL, $term_in_days = NULL, $frequency = NULL ,$principle_fq = NULL ){	
		$term = round($term_in_days/$frequency);
		$all_total = '';
		if($rate_type == '1') {
			$principle = $lease_amount/$term;
			$lease_amt = $lease_amount;
			$interest_rate = 0;
			$total_principle = 0;
			$total_interest = 0;
			$total_payment = 0;
			for($i=1; $i<=$term; $i++) {
				if($i == 1) {
					$interest_rate = $lease_amt * $interest;
				} else {
					$interest_rate = $lease_amt * $interest;
				}
				$lease_amt -= $principle;
				$payment_amt = $principle + $interest_rate;
				$total_principle += $principle;
				$total_interest += $interest_rate;
				$total_payment += $payment_amt;
			}
			$all_total = array(
								'total_principle' => $total_principle,
								'total_interest' => $total_interest,
								'total_payment' => $total_payment,
							  );
			///$this->erp->print_arrays($all_total);
			$all_total = 1000 ;
			return $all_total;
		} else if($rate_type == '2') {
			$payment_amt = (($lease_amount * $interest)*((pow((1+$interest),$term))/(pow((1+$interest),$term)-1)));
			$lease_amt = $lease_amount;
			$total_principle = 0;
			$total_interest = 0;
			$total_payment = 0;
			for($i=1;$i<=$term;$i++) {
				if($i == 1) {
					$interest_rate = $lease_amt * $interest;
				} else {
					$interest_rate = $lease_amt * $interest;
				}
				$principle = $payment_amt - $interest_rate;
				$lease_amt -= $principle;
				$total_principle += $principle;
				$total_interest += $interest_rate;
				$total_payment += $payment_amt;
			}
			$all_total = array(
								'total_principle' => $total_principle,
								'total_interest' => $total_interest,
								'total_payment' => $total_payment,
							  );
			return $all_total;
		} else if($rate_type == '3') {
			$principle = $lease_amount/$term;
			$interest_rate = $lease_amount * $interest;
			$lease_amt = $lease_amount;
			$total_principle = 0;
			$total_interest = 0;
			$total_payment = 0;
			for($i=1;$i<=$term;$i++) {
				$payment_amt = $principle + $interest_rate;
				$lease_amt -= $principle;
				$total_principle += $principle;
				$total_interest += $interest_rate;
				$total_payment += $payment_amt;
			}
			$all_total = array(
								'total_principle' => $total_principle,
								'total_interest' => $total_interest,
								'total_payment' => $total_payment,
							  );
			return $all_total;
		} else if($rate_type == '4') {
			$principle = $lease_amount/$term;
			$lease_amt = $lease_amount;
			$interest_rate = 0;
			$total_principle = 0;
			$total_interest = 0;
			$total_payment = 0;
			$counter = 1;
			$tprinciple = 0;
			for($i=1; $i<=$term; $i++) {
				if($i == 1) {
					$interest_rate = $lease_amt * $interest;
				} else {
					$interest_rate = $lease_amt * $interest;
				}
				
				if($counter == $principle_fq || $i == $term) {
					$principle += $tprinciple;
					$tprinciple = 0;
					$counter = 0;
				}else {
					$tprinciple += $principle;
					$principle = 0;
				}
				
				$lease_amt -= $principle;
				$payment_amt = $principle + $interest_rate;
				$total_principle += $principle;
				$total_interest += $interest_rate;
				$total_payment += $payment_amt;
				$counter++;
			}
			$all_total = array(
								'total_principle' => $total_principle,
								'total_interest' => $total_interest,
								'total_payment' => $total_payment,
							  );
			//$this->erp->print_arrays($all_total);
			return $all_total;
		} else if ($rate_type == '5'){
			return $all_total;
		}else {
			return $all_total;
		}
	}
	
	function getInstallmentAmount($lease_amount = NULL, $rate_type = NULL, $interest = NULL, $term = NULL) {
		$principle = 0;
		$interest_rate = 0;
		$installment_amount = 0;
		if($rate_type == '1') {
			$principle = $lease_amount/$term;
			$interest_rate = $lease_amount * $interest;
			$installment_amount = $principle + $interest_rate;
			return $installment_amount;
		} else if($rate_type == '2') {
			$installment_amount = (($lease_amount * $interest)*((pow((1+$interest),$term))/(pow((1+$interest),$term)-1)));
			return $installment_amount;
		} else if($rate_type == '3') {
			$principle = $lease_amount/$term;
			$interest_rate = $lease_amount * $interest;
			$installment_amount = $principle + $interest_rate;
			return $installment_amount;
		} else if($rate_type == '4') {
			return false;
		}
	}
	
	function imageJPGExcel($url,$cellNum,$height) {
		$gdImage = imagecreatefromjpeg(base_url().''.$url);
  				$objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
  				$objDrawing->setName('Sample image');
  				$objDrawing->setDescription('Sample image');
  				$objDrawing->setImageResource($gdImage);
  				$objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_JPEG);
  				$objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
  				$objDrawing->setHeight($height);
  				$objDrawing->setCoordinates($cellNum);
  				$objDrawing->setWorksheet($this->excel->getActiveSheet());
	}
	
	function getExcel($merge = array(), $center = array(), $borderout = array(), $text = array(),$fontSize=array(),$putBold=array(),$fontColor=array()) {
		$this->load->library('excel');
		// Merge Cells
			$length = count($merge);
			for($i=0;$i<$length;$i++){
			$this->excel->getActiveSheet()->mergeCells($merge[$i]);
			}
			// Eng Merg Cells
			// Center text in cell
			$length = count($center);
			for($i=0;$i<$length;$i++){
				$this->excel->getActiveSheet()->getStyle($center[$i])->getAlignment()->applyFromArray(
					array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
				);
			}	
			// End center text in cell	
			// Border
			$length = count($borderout);
			for($i=0;$i<$length;$i++){
				$border_style= array('borders' => array('outline' => array('style' =>
					PHPExcel_Style_Border::BORDER_THIN)));
				$this->excel->getActiveSheet()->getStyle($borderout[$i])->applyFromArray($border_style);
			}
			// End Border
			// Input Excel			
			foreach($text as $key => $value) {
				$this->excel->getActiveSheet()->SetCellValue($key, lang($value));
			}
			// End Input Excel
			// Font Size
			foreach($fontSize as $key => $value) {
				$fontS= array(
				'font'  => array(
					'size'  => $value,
				));
				$this->excel->getActiveSheet()->getStyle($key)->applyFromArray($fontS);
			}
			//End Font Size
			// Bold
			$length = count($putBold);
			for($i=0;$i<$length;$i++){
			$bold= array(
				'font'  => array(
					'bold'  => TRUE
				));
				$this->excel->getActiveSheet()->getStyle($putBold[$i])->applyFromArray($bold);
			}
			//End Bold
			// Font Size
			foreach($fontColor as $key => $value) {
				$fontC= array(
				'font'  => array(
					'color' => array('rgb' => $value)
				));
				$this->excel->getActiveSheet()->getStyle($key)->applyFromArray($fontC);
			}
			//End Font Size
	}
	
	public function roundUpMoney($number = null, $currency = null){
		if($currency) {
			$getcurrency = $this->site->getCurrencyByCode($currency);
			$type = $getcurrency->currency_type;
		}else {
			$type = 0;
		}
		
		switch ($type) {
            case 1:
                $devide = round($number/100);
				$result = ($devide * 100);
				return number_format($result);
                break;
            case 2:
				return number_format($number);
                break;
            case 3:
                if ($this->Settings->sac) {
					return ($this->Settings->display_symbol == 1 ? $this->Settings->symbol : '') .
					$this->formatSAC($this->formatDecimal($number)) .
					($this->Settings->display_symbol == 2 ? $this->Settings->symbol : '');
				}
				$decimals = $this->Settings->decimals;
				$ts = $this->Settings->thousands_sep == '0' ? ' ' : $this->Settings->thousands_sep;
				$ds = $this->Settings->decimals_sep;
				return number_format($number, $decimals, $ds, $ts);
                break;
            default:
                if ($this->Settings->sac) {
					return ($this->Settings->display_symbol == 1 ? $this->Settings->symbol : '') .
					$this->formatSAC($this->formatDecimal($number)) .
					($this->Settings->display_symbol == 2 ? $this->Settings->symbol : '');
				}
				$decimals = $this->Settings->decimals;
				$ts = $this->Settings->thousands_sep == '0' ? ' ' : $this->Settings->thousands_sep;
				$ds = $this->Settings->decimals_sep;
				return number_format($number, $decimals, $ds, $ts);
        }
    }
	
	function convertCurrency($to_currencyCodes = NULL, $from_currency_codes = NULL, $amount = 0){
		$to_currencyCode = $this->site->getCurrncyByCode($to_currencyCodes);
		$from_currency_code = $this->site->getCurrncyByCode($from_currency_codes);
		$convertAmount = ($to_currencyCode->rate / $from_currency_code->rate) * $amount;
		return $convertAmount;		
	}
	
	public function syncDisbursPayment($sale_id = NULL , $company_id = NULL){
		$payments = $this->site->getPaymentBySale_ID($sale_id);
		$sum_payment = 0;
			foreach($payments as $payment)
			{
			   $sum_payment+= $payment->amount;
			}
		return $sum_payment;
	}
	function limit_words($input, $length, $delimiter = "...")
	{
		//only truncate if input is actually longer than $length
		if(strlen($input) > $length)
		{
			//check if there are any spaces at all and if the last one is within the given length if so truncate at space else truncate at length.
			if(strrchr($input, " ") && strrchr($input, " ") < $length)
			{
				return substr( $input, 0, strrpos( substr( $input, 0, $length), ' ' ) ) . $delimiter;
			}
			else
			{
				return substr( $input, 0, $length ) . $delimiter;
			}
		}
		else
		{
			return $input;
		}
	}
	
}

