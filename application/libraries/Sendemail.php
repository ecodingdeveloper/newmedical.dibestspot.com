<?php
defined('BASEPATH') OR exit('No direct script access allowed');

    // Load Composer's autoloader
    require 'vendor/autoload.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

#[\AllowDynamicProperties]
    
class Sendemail {



   
    public function __construct() {

        $this->ci = & get_instance();
        $this->ci->load->helper('file');
        $this->mail = !empty(settings("mail_option"))?settings("mail_option"):2;
        $this->mail_type = !empty(settings("email_option"))?settings("email_option"):"sendgrid";
        $this->title=!empty(settings("website_name"))?settings("website_name"):"DiBest Spot";
        if($this->mail==1)
        {
            if($this->mail_type=='smtp'){
                // die("smtp");
                // echo settings("smtp_user");
                $this->ci->load->library('email');
                $this->ci->email->initialize(smtp_mail_config());
                $this->ci->email->set_newline("\r\n");
                $this->ci->email->from(!empty(settings("smtp_user"))?settings("smtp_user"):"naveenkumar.t@dreamguystech.com",!empty(settings("email_tittle"))?settings("email_tittle"):"DiBest Spot");
            }else{
                $this->sendgrid = !empty(settings("sendgrid_apikey"))?settings("sendgrid_apikey"):"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";
            }
 
        }else{//acfj vfjx vdmk izuw
            // dibest puqv uoik aauy kaen
            return true;
        }

    }

    public function new_email($fullname,$emailAddress){

        $subject = $this->title.' Mail is from '.strtoupper($this->mail_type);
        $message = 'New Registration on MyWarmEmbrace from '.$emailAddress.'..';

        if($this->mail_type=='smtp'){
        $this->ci->email->cc('team@mywarmembrace.com');
    

            $this->ci->email->to(settings("smtp_user")); 
            $this->ci->email->subject($subject);
            $this->ci->email->message($message);
            $this->ci->email->send();

        }else{

            $email = new \SendGrid\Mail\Mail();
            $email->setFrom(!empty(settings("email_address"))?settings("email_address"):"info@doccure.com",!empty(settings("email_tittle"))?settings("email_tittle"):"DiBest Spot");
            $email->setSubject($subject);
            $email->addTo($emailAddress);
            $email->addContent(
                "text/html", $message
            );
            $sendgrid = new \SendGrid($this->sendgrid);
            try {
                $response = $sendgrid->send($email);
                print $response->statusCode() . "\n";
                print_r($response->headers());
                print $response->body() . "\n";
            } catch (Exception $e) {
                echo 'Caught exception: '. $e->getMessage() ."\n";
            }

        }

    }

    public function email_test($emailAddress){

        $subject = $this->title.' Test Mail from '.strtoupper($this->mail_type);
        $message = 'This is a test mail from '.$this->title.'..';

        if($this->mail_type=='smtp'){

            $this->ci->email->to($emailAddress); 
            $this->ci->email->subject($subject);
            $this->ci->email->message($message);
            $this->ci->email->send();

        }else{

            $email = new \SendGrid\Mail\Mail();
            $email->setFrom(!empty(settings("email_address"))?settings("email_address"):"info@doccure.com",!empty(settings("email_tittle"))?settings("email_tittle"):"DiBest Spot");
            $email->setSubject($subject);
            $email->addTo($emailAddress);
            $email->addContent(
                "text/html", $message
            );
            $sendgrid = new \SendGrid($this->sendgrid);
            try {
                $response = $sendgrid->send($email);
                print $response->statusCode() . "\n";
                print_r($response->headers());
                print $response->body() . "\n";
            } catch (Exception $e) {
                echo 'Caught exception: '. $e->getMessage() ."\n";
            }

        }

    }


    public function send_email_verification($data)
    {    
        $message='';

        $email_templates=email_template(1);
        $body=$email_templates['template_content'];
        $subject=$email_templates['template_subject'];

        $subject = str_replace('{{website_name}}', !empty(settings("website_name"))?settings("website_name"):"DiBest Spot", $subject);

        $body = str_replace('{{site_url}}', base_url(), $body);
        $body = str_replace('{{site_logo}}',!empty(base_url().settings("logo_front"))?base_url()."assets/img/logo1.png":base_url()."assets/img/logo.png", $body);
        $body = str_replace('{{user_name}}', ucfirst($data['first_name'].' '.$data['last_name']), $body);
        $body = str_replace('{{website_name}}', !empty(settings("website_name"))?settings("website_name"):"DiBest Spot", $body);
        $body = str_replace('{{verify_url}}', base_url().'activate/'.md5($data['id']), $body);
        $body = str_replace('{{date}}', date('Y'), $body);

        $message=$body;
 
        if($this->mail_type=='smtp'){

            $this->ci->email->to($data['email']); 
            $this->ci->email->subject($subject);
            $this->ci->email->message($message);
            $this->ci->email->send();

        }else{
 
            $email = new \SendGrid\Mail\Mail();
            $email->setFrom(!empty(settings("email_address"))?settings("email_address"):"info@doccure.com",!empty(settings("email_tittle"))?settings("email_tittle"):"DiBest Spot");
            $email->setSubject($subject);
            $email->addTo($data['email']);
            $email->addContent(
                "text/html", $message
            );
            $sendgrid = new \SendGrid($this->sendgrid);
            try {
                $response = $sendgrid->send($email);
            } catch (Exception $e) {

            }

        }

                
    }

    public function send_resetpassword_email($data)
    {    
        $message='';

        $email_templates=email_template(3);
        $body=$email_templates['template_content'];
        $subject=$email_templates['template_subject'];

        $body = str_replace('{{site_url}}', base_url(), $body);
        $body = str_replace('{{site_logo}}',!empty(base_url().settings("logo_front"))?base_url()."assets/img/logo1.png":base_url()."assets/img/logo.png", $body);
        $body = str_replace('{{user_name}}', ucfirst($data['first_name'].' '.$data['last_name']), $body);
        $body = str_replace('{{website_name}}', !empty(settings("website_name"))?settings("website_name"):"DiBest Spot", $body);
        $body = str_replace('{{email}}', $data['email'], $body);
        $body = str_replace('{{reset_url}}', base_url().'reset/'.$data['url'], $body);
        $body = str_replace('{{date}}', date('Y'), $body);

        $message=$body;

        if($this->mail_type=='smtp'){

            $this->ci->email->to($data['email']); 
            $this->ci->email->subject($subject);
            $this->ci->email->message($message);
            $this->ci->email->send();

        }else{

            $email = new \SendGrid\Mail\Mail();
            $email->setFrom(!empty(settings("email_address"))?settings("email_address"):"info@doccure.com",!empty(settings("email_tittle"))?settings("email_tittle"):"DiBest Spot");
            $email->setSubject($subject);
            $email->addTo($data['email']);
            $email->addContent(
                "text/html", $message
            );
            $sendgrid = new \SendGrid($this->sendgrid);
            try {
                $response = $sendgrid->send($email);
            } catch (Exception $e) {

            }

        }
                
    }

    public function send_appoinment_email($data)
    {    
        $message='';

        $email_templates=email_template(2);
        $body=$email_templates['template_content'];
        $subject=$email_templates['template_subject'];

        $body = str_replace('{{site_url}}', base_url(), $body);
        $body = str_replace('{{site_logo}}',!empty(base_url().settings("logo_front"))?base_url()."assets/img/logo1.png":base_url()."assets/img/logo.png", $body);
        $body = str_replace('{{doctor_name}}', ucfirst($data['doctor_name']), $body);
        $body = str_replace('{{website_name}}', !empty(settings("website_name"))?settings("website_name"):"DiBest Spot", $body);
        $body = str_replace('{{patient_name}}', $data['patient_name'], $body);
        $body = str_replace('{{date}}', date('Y'), $body);

        $message=$body;

        if($this->mail_type=='smtp'){

            $this->ci->email->to($data['doctor_email']); 
            $this->ci->email->subject($subject);
            $this->ci->email->message($message);
            $this->ci->email->send();

        }else{

            $email = new \SendGrid\Mail\Mail();
            $email->setFrom(!empty(settings("email_address"))?settings("email_address"):"info@doccure.com",!empty(settings("email_tittle"))?settings("email_tittle"):"DiBest Spot");
            $email->setSubject($subject);
            $email->addTo($data['doctor_email']);
            $email->addContent(
                "text/html", $message
            );
            $sendgrid = new \SendGrid($this->sendgrid);
            try {
                $response = $sendgrid->send($email);
            } catch (Exception $e) {

            }

        }
                
    }

    public function send_appoinment_cancelemail($data)
    {    
        $message='';

        $email_templates=email_template(4);
        $body=$email_templates['template_content'];
        $subject=$email_templates['template_subject'];

        $body = str_replace('{{site_url}}', base_url(), $body);
        $body = str_replace('{{site_logo}}',!empty(base_url().settings("logo_front"))?base_url()."assets/img/logo1.png":base_url()."assets/img/logo.png", $body);
        $body = str_replace('{{doctor_name}}', ucfirst($data['doctor_name']), $body);
        $body = str_replace('{{website_name}}', !empty(settings("website_name"))?settings("website_name"):"DiBest Spot", $body);
        $body = str_replace('{{patient_name}}', $data['patient_name'], $body);
        $body = str_replace('{{date}}', date('Y'), $body);

        $message=$body;

        if($this->mail_type=='smtp'){

            $this->ci->email->to($data['patient_email']); 
            $this->ci->email->subject($subject);
            $this->ci->email->message($message);
            $this->ci->email->send();

        }else{

            $email = new \SendGrid\Mail\Mail();
            $email->setFrom(!empty(settings("email_address"))?settings("email_address"):"info@doccure.com",!empty(settings("email_tittle"))?settings("email_tittle"):"DiBest Spot");
            $email->setSubject($subject);
            $email->addTo($data['patient_email']);
            $email->addContent(
                "text/html", $message
            );
            $sendgrid = new \SendGrid($this->sendgrid);
            try {
                $response = $sendgrid->send($email);
            } catch (Exception $e) {

            }

        }
                
    }

    public function send_appoinment_acceptemail($data)
    {    
        $message='';

        $email_templates=email_template(5);
        $body=$email_templates['template_content'];
        $subject=$email_templates['template_subject'];

        $body = str_replace('{{site_url}}', base_url(), $body);
        $body = str_replace('{{site_logo}}',!empty(base_url().settings("logo_front"))?base_url()."assets/img/logo1.png":base_url()."assets/img/logo.png", $body);
        $body = str_replace('{{doctor_name}}', ucfirst($data['doctor_name']), $body);
        $body = str_replace('{{website_name}}', !empty(settings("website_name"))?settings("website_name"):"DiBest Spot", $body);
        $body = str_replace('{{patient_name}}', $data['patient_name'], $body);
        $body = str_replace('{{date}}', date('Y'), $body);

        $message=$body;

        if($this->mail_type=='smtp'){

            $this->ci->email->to($data['patient_email']); 
            $this->ci->email->subject($subject);
            $this->ci->email->message($message);
            $this->ci->email->send();

        }else{

            $email = new \SendGrid\Mail\Mail();
            $email->setFrom(!empty(settings("email_address"))?settings("email_address"):"info@doccure.com",!empty(settings("email_tittle"))?settings("email_tittle"):"DiBest Spot");
            $email->setSubject($subject);
            $email->addTo($data['patient_email']);
            $email->addContent(
                "text/html", $message
            );
            $sendgrid = new \SendGrid($this->sendgrid);
            try {
                $response = $sendgrid->send($email);
            } catch (Exception $e) {

            }

        }

    }

    public function send_bill_notify_email($data)
    {    
        $message='';

        $email_templates=email_template(6);
        $body=$email_templates['template_content'];
        $subject=$email_templates['template_subject'];

        $subject = str_replace('{{website_name}}', !empty(settings("website_name"))?settings("website_name"):"DiBest Spot", $subject);
        $subject = str_replace('{{invoice_no}}', $data['bill_no'], $subject);
        $subject = str_replace('{{doctor_name}}', ucfirst($data['doctor_name']), $subject);

        $body = str_replace('{{site_url}}', base_url(), $body);
        $body = str_replace('{{site_logo}}',!empty(base_url().settings("logo_front"))?base_url()."assets/img/logo1.png":base_url()."assets/img/logo.png", $body);
        $body = str_replace('{{doctor_name}}', ucfirst($data['doctor_name']), $body);
        $body = str_replace('{{website_name}}', !empty(settings("website_name"))?settings("website_name"):"DiBest Spot", $body);
        $body = str_replace('{{patient_name}}', $data['patient_name'], $body);
        $body = str_replace('{{invoice_no}}', $data['bill_no'], $body);
        $body = str_replace('{{bill_no}}', $data['billno'], $body);
        $body = str_replace('{{invoice_url}}', base_url().'invoice/', $body);
        $body = str_replace('{{date}}', date('Y'), $body);

        $message=$body;

        if($this->mail_type=='smtp'){

            $this->ci->email->to($data['patient_email']); 
            $this->ci->email->subject($subject);
            $this->ci->email->message($message);
            $this->ci->email->send();

        }else{

            $email = new \SendGrid\Mail\Mail();
            $email->setFrom(!empty(settings("email_address"))?settings("email_address"):"info@doccure.com",!empty(settings("email_tittle"))?settings("email_tittle"):"DiBest Spot");
            $email->setSubject($subject);
            $email->addTo($data['patient_email']);
            $email->addContent(
                "text/html", $message
            );
            $sendgrid = new \SendGrid($this->sendgrid);
            try {
                $response = $sendgrid->send($email);
            } catch (Exception $e) {

            }

        }
                
    }

    public function send_bill_paid_notify_email($data)
    {    
        $message='';

        $email_templates=email_template(7);
        $body=$email_templates['template_content'];
        $subject=$email_templates['template_subject'];

        $subject = str_replace('{{website_name}}', !empty(settings("website_name"))?settings("website_name"):"DiBest Spot", $subject);
        $subject = str_replace('{{invoice_no}}', $data['bill_no'], $subject);
        $subject = str_replace('{{patient_name}}', ucfirst($data['patient_name']), $subject);

        $body = str_replace('{{site_url}}', base_url(), $body);
        $body = str_replace('{{site_logo}}',!empty(base_url().settings("logo_front"))?base_url()."assets/img/logo1.png":base_url()."assets/img/logo.png", $body);
        $body = str_replace('{{doctor_name}}', ucfirst($data['doctor_name']), $body);
        $body = str_replace('{{website_name}}', !empty(settings("website_name"))?settings("website_name"):"DiBest Spot", $body);
        $body = str_replace('{{patient_name}}', $data['patient_name'], $body);
        $body = str_replace('{{invoice_no}}', $data['bill_no'], $body);
        $body = str_replace('{{bill_no}}', $data['billno'], $body);
        $body = str_replace('{{payment_date}}', $data['bill_paid_on'], $body);
        $body = str_replace('{{invoice_url}}', base_url().'invoice/', $body);
        $body = str_replace('{{date}}', date('Y'), $body);

        $message=$body;

        if($this->mail_type=='smtp'){

            $this->ci->email->to($data['doctor_email']); 
            $this->ci->email->subject($subject);
            $this->ci->email->message($message);
            $this->ci->email->send();

        }else{

            $email = new \SendGrid\Mail\Mail();
            $email->setFrom(!empty(settings("email_address"))?settings("email_address"):"info@doccure.com",!empty(settings("email_tittle"))?settings("email_tittle"):"DiBest Spot");
            $email->setSubject($subject);
            $email->addTo($data['doctor_email']);
            $email->addContent(
                "text/html", $message
            );
            $sendgrid = new \SendGrid($this->sendgrid);
            try {
                $response = $sendgrid->send($email);
            } catch (Exception $e) {

            }

        }
                
    }

}



/* End of file Common.php */

