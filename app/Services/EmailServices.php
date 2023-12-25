<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Http\Request;

use App\Mail\EmailNotification;

use DB;
use Mail;

## Models
use App\Models\LeadsModel;
use App\Models\ReportsModel;
use App\Models\SiteSettingModel;
use App\Models\EmailTemplateModel;
use App\Models\TransactionsModel;

class EmailServices
{ 
    public function __construct()
    {
        $this->siteSetting = SiteSettingModel::select(['email_setting','site_name'])->find(1);
    }

    public function send_email($type,$id,$senderEmailId,$buyType)
    {
        //dd($type,$id,$senderEmailId,$buyType);
        $emailTemplate  = EmailTemplateModel::where('name',$type)->where('status','0')->first();
        
        if(!empty($emailTemplate))
        {
            if($buyType == 'enquiry')
            {
                $arrObject = DB::table('tbl_enquiry')
                            ->leftjoin('tbl_reports', 'tbl_enquiry.report_id', '=', 'tbl_reports.id')
                            ->leftjoin('tbl_publisher', 'tbl_reports.fk_publisher_id', '=', 'tbl_publisher.id')
                            ->leftjoin('users', 'tbl_enquiry.fk_sale_id', '=', 'users.id')
                            ->where('tbl_reports.status','0')
                            ->where('tbl_enquiry.id',$id)
                            ->select('tbl_enquiry.name','tbl_enquiry.email_id','tbl_enquiry.contact_no','tbl_enquiry.company_name','tbl_enquiry.job_title','tbl_enquiry.sample_type','tbl_reports.id as report_id','tbl_reports.report_title','tbl_reports.id as report_id','tbl_publisher.name as publisher_name','users.name as sales_name','users.email as sales_email')
                            ->first();

                switch ($arrObject->sample_type)
                {
                    case '1':
                        $sample_type = 'Request sample email';
                    break;
                    case '2':
                        $sample_type = 'Ask for discount email';
                    break;
                    case '3':
                        $sample_type = 'Enquiry before buying email';
                    break;
                }

                $name           = $arrObject->name;
                $report_id      = $arrObject->report_id;
                $report_title   = $arrObject->report_title;
                $publisher_name = $arrObject->publisher_name;
                $job_title      = $arrObject->job_title;
                $email_id       = $arrObject->email_id;
                $contact_no     = $arrObject->contact_no;
                $company_name   = $arrObject->company_name;
                $sales_name     = $arrObject->sales_name;
            }
            else
            {
                $arrObject = DB::table('tbl_transaction')
                            ->leftjoin('tbl_reports', 'tbl_transaction.report_id', '=', 'tbl_reports.id')
                            ->leftjoin('tbl_publisher', 'tbl_reports.fk_publisher_id', '=', 'tbl_publisher.id')
                            ->leftjoin('users', 'tbl_reports.fk_publisher_id', '=', 'users.id')
                            ->where('tbl_reports.status','0')
                            ->where('tbl_transaction.id',$id)
                            ->select('tbl_transaction.name','tbl_transaction.email_id','tbl_transaction.contact_no','tbl_transaction.company_name','tbl_transaction.job_title','tbl_reports.id as report_id','tbl_reports.report_title','tbl_reports.id as report_id','tbl_publisher.name as publisher_name','users.name as sales_name','users.email as sales_email')
                            ->first();

                $name           = $arrObject->name;
                $report_id      = $arrObject->report_id;
                $report_title   = $arrObject->report_title;
                $publisher_name = $arrObject->publisher_name;
                $job_title      = $arrObject->job_title;
                $email_id       = $arrObject->email_id;
                $contact_no     = $arrObject->contact_no;
                $company_name   = $arrObject->company_name;
                $sales_name     = $arrObject->sales_name;
            }

            $ip_address = '5.62.24.59';
            //$ip_address = \Request::ip();
            
            $variables = ["{{ip_address}}","{{reportId}}","{{reportTitle}}","{{publisher}}","{{job_title}}","{{date}}","{{customer_name}}","{{customer_email}}","{{customer_contact_no}}","{{company_name}}","{{sales_person_name}}","{{site_url}}",];
            $values   = [$ip_address,$report_id,$report_title,$publisher_name,$job_title,date('Y-m-d'),$name,$email_id,$contact_no,$company_name,$sales_name,url('/')];

            ## Create Data
            $arrEmailData = json_decode($this->siteSetting->email_setting);
            
            $arrData                 = [];
            $arrData['site_name']     = $this->siteSetting->site_name;
            $arrData['template_name'] = $emailTemplate->name;
            $arrData['from_email']    = !empty($arrEmailData) ? $arrEmailData->email_from : '';
            $arrData['email_to']      = !empty($senderEmailId) ? $senderEmailId : $arrEmailData->email_to;
            $arrData['email_subject'] = $emailTemplate->subject;
            $arrData['email_html']    = str_replace($variables, $values, $emailTemplate->description);
            $arrData['email_cc']      = !empty($arrEmailData->email_cc) ? $arrEmailData->email_cc : ''; 
            
            if(!empty($arrData['email_to']) && !empty($arrData['email_cc']))
            {
                Mail::to($arrData['email_to'])->cc($arrData['email_cc'])->send(new EmailNotification($arrData));
            }
        }
    }

    public function send_email_to_admin($type,$id,$buyType)
    {
        $emailTemplate  = EmailTemplateModel::where('name',$type)->where('status','0')->first();
        
        if(!empty($emailTemplate))
        {
            if($buyType == 'enquiry')
            {
                $arrObject = DB::table('tbl_enquiry')
                            ->leftjoin('tbl_reports', 'tbl_enquiry.report_id', '=', 'tbl_reports.id')
                            ->leftjoin('tbl_publisher', 'tbl_reports.fk_publisher_id', '=', 'tbl_publisher.id')
                            ->leftjoin('users', 'tbl_enquiry.fk_sale_id', '=', 'users.id')
                            ->where('tbl_reports.status','0')
                            ->where('tbl_enquiry.id',$id)
                            ->select('tbl_enquiry.name','tbl_enquiry.email_id','tbl_enquiry.contact_no','tbl_enquiry.company_name','tbl_enquiry.job_title','tbl_enquiry.sample_type','tbl_reports.id as report_id','tbl_reports.report_title','tbl_reports.id as report_id','tbl_publisher.name as publisher_name','users.name as sales_name','users.email as sales_email')
                            ->first();

                switch ($arrObject->sample_type)
                {
                    case '1':
                        $sample_type = 'Request sample email';
                    break;
                    case '2':
                        $sample_type = 'Ask for discount email';
                    break;
                    case '3':
                        $sample_type = 'Enquiry before buying email';
                    break;
                }

                $report_id      = $arrObject->report_id;
                $report_title   = $arrObject->report_title;
                $publisher_name = $arrObject->publisher_name;
                $job_title      = $arrObject->job_title;
                $email_id       = $arrObject->email_id;
                $contact_no     = $arrObject->contact_no;
                $company_name   = $arrObject->company_name;
                $sales_name     = $arrObject->sales_name;
                $message        = '';
            }
            elseif ($buyType == 'contactus')
            {
                $arrObject = DB::table('tbl_contactus')
                            ->where('tbl_contactus.id',$id)
                            ->first();

                $report_id      = '';
                $report_title   = '';
                $publisher_name = '';
                $name           = $arrObject->name;
                $job_title      = $arrObject->job_title;
                $email_id       = $arrObject->email_id;
                $contact_no     = $arrObject->contact_no;
                $company_name   = $arrObject->company_name;
                $sales_name     = '';
                $sample_type    = '';
                $message        = $arrObject->description;
            }
            else
            {
                $arrObject = DB::table('tbl_transaction')
                            ->leftjoin('tbl_reports', 'tbl_transaction.report_id', '=', 'tbl_reports.id')
                            ->leftjoin('tbl_publisher', 'tbl_reports.fk_publisher_id', '=', 'tbl_publisher.id')
                            ->leftjoin('users', 'tbl_reports.fk_publisher_id', '=', 'users.id')
                            ->where('tbl_reports.status','0')
                            ->where('tbl_transaction.id',$id)
                            ->select('tbl_transaction.name','tbl_transaction.email_id','tbl_transaction.contact_no','tbl_transaction.company_name','tbl_transaction.job_title','tbl_reports.id as report_id','tbl_reports.report_title','tbl_reports.id as report_id','tbl_publisher.name as publisher_name','users.name as sales_name','users.email as sales_email')
                            ->first();

                $report_id      = $arrObject->report_id;
                $report_title   = $arrObject->report_title;
                $publisher_name = $arrObject->publisher_name;
                $job_title      = $arrObject->job_title;
                $email_id       = $arrObject->email_id;
                $contact_no     = $arrObject->contact_no;
                $company_name   = $arrObject->company_name;
                $message        = $arrObject->message;
                $sales_name     = '';
                $sample_type    = '';
            }
            

            $ip_address = '5.62.24.59';
            //$ip_address = \Request::ip();
            
            $variables = ["{{ip_address}}","{{request_type}}","{{reportId}}","{{reportTitle}}","{{publisher}}","{{job_title}}","{{date}}","{{customer_name}}","{{customer_email}}","{{customer_contact_no}}","{{company_name}}","{{sales_person_name}}","{{site_url}}","{{message}}",];
            $values   = [$ip_address,$sample_type,$report_id,$report_title,$publisher_name,$job_title,date('Y-m-d'),'Admin',$email_id,$contact_no,$company_name,$sales_name,url('/'),$message];

            ## Create Data
            $arrEmailData = json_decode($this->siteSetting->email_setting);
            
            $arrData = [];
            $arrData['site_name']     = $this->siteSetting->site_name;
            $arrData['template_name'] = $emailTemplate->name;
            $arrData['from_email']    = !empty($arrEmailData) ? $arrEmailData->email_from : '';
            $arrData['email_to']      = !empty($arrEmailData->email_to) ? $arrEmailData->email_to : '';
            $arrData['email_subject'] = $emailTemplate->subject;
            $arrData['email_html']    = str_replace($variables, $values, $emailTemplate->description);
            $arrData['email_cc']      = !empty($arrEmailData->email_cc) ? $arrEmailData->email_cc : ''; 
            
            if(!empty($arrData['email_to']) && !empty($arrData['email_cc']))
            {
                Mail::to($arrData['email_to'])->cc($arrData['email_cc'])->send(new EmailNotification($arrData));
            }
        }
    }
}