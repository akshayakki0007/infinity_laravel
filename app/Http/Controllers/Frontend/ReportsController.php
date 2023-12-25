<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

use Stripe;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

## Services
use App\Services\EmailServices;

## Models
use App\Models\User;
use App\Models\LeadsModel;
use App\Models\ReportsModel;
use App\Models\CountryModel;
use App\Models\CategoryModel;
use App\Models\PublisherModel;
use App\Models\SiteSettingModel;
use App\Models\TransactionsModel;
use App\Models\RegionsCountryModel;

class ReportsController extends Controller
{
    public function __construct()
    {
        $this->ViewData = [];
        $this->JsonData = [];

        $this->ModuleTitle = 'Reports';
        $this->ModuleView  = 'Frontend/';

        $this->siteSetting = SiteSettingModel::find(1);
        $this->emailServices = new EmailServices();
    }

    public function index()
    {
        $this->ViewData['moduleTitle']  = $this->ModuleTitle;
        $this->ViewData['moduleAction'] = $this->ModuleTitle;
        $this->ViewData['arrCategory']  = CategoryModel::where('status','0')->limit(4)->inRandomOrder()->get(['id','name','slug']);
        $this->ViewData['arrReports']   = DB::table('tbl_reports')
                                                ->leftjoin('tbl_category', 'tbl_reports.fk_category_id', '=', 'tbl_category.id')
                                                ->leftjoin('tbl_publisher', 'tbl_reports.fk_publisher_id', '=', 'tbl_publisher.id')
                                                ->where('tbl_reports.status','0')
                                                ->select('tbl_reports.*','tbl_category.id as catId','tbl_category.id as cat_id','tbl_category.name as cat_name','tbl_category.slug as cat_slug','tbl_publisher.id as publisher_id','tbl_publisher.name as publisher_name')
                                                ->get();
        
        return view($this->ModuleView.'/all_reports', $this->ViewData);
    }

    public function report($slug)
    {
        $arrUrl = explode("-",$slug);
        $reportId = end($arrUrl);

        $this->ViewData['moduleTitle']  = $this->ModuleTitle;
        $this->ViewData['moduleAction'] = $this->ModuleTitle;
        $this->ViewData['arrReports']   = $arrReports = getSingleReport($reportId);
        
        if(empty($this->ViewData['arrReports']))
        {
            return redirect()->route('home');
        }

        if(!empty($arrReports->cat_id))
        {
            $this->ViewData['arrRelatedReports'] = DB::table('tbl_reports')
                                                        ->leftjoin('tbl_category', 'tbl_reports.fk_category_id', '=', 'tbl_category.id')
                                                        ->leftjoin('tbl_publisher', 'tbl_reports.fk_publisher_id', '=', 'tbl_publisher.id')
                                                        ->where('tbl_reports.id','!=',$reportId)
                                                        ->where('tbl_reports.fk_category_id',$arrReports->cat_id)
                                                        ->where('tbl_reports.status','0')
                                                        ->limit('4')
                                                        ->inRandomOrder()
                                                        ->select('tbl_reports.*','tbl_category.id as catId','tbl_category.id as cat_id','tbl_category.name as cat_name','tbl_category.slug as cat_slug','tbl_publisher.id as publisher_id','tbl_publisher.name as publisher_name')
                                                        ->get();
        }
        
        return view($this->ModuleView.'/single_report', $this->ViewData);
    }

    public function category($slug)
    {
        $category = CategoryModel::where('slug',$slug)->first(['id','name']);
        if(!empty($category))
        {
            $this->ViewData['moduleTitle']  = $category->name;
            $this->ViewData['moduleAction'] = $category->name;
            $this->ViewData['arrCategory']  = $category;
            $this->ViewData['arrReports']   = DB::table('tbl_reports')
                                                ->leftjoin('tbl_category', 'tbl_reports.fk_category_id', '=', 'tbl_category.id')
                                                ->leftjoin('tbl_publisher', 'tbl_reports.fk_publisher_id', '=', 'tbl_publisher.id')
                                                ->where('tbl_reports.fk_category_id',$category->id)
                                                ->where('tbl_reports.status','0')
                                                ->limit('4')
                                                ->inRandomOrder()
                                                ->select('tbl_reports.*','tbl_category.id as catId','tbl_category.id as cat_id','tbl_category.name as cat_name','tbl_category.slug as cat_slug','tbl_publisher.id as publisher_id','tbl_publisher.name as publisher_name')
                                                ->get();

            return view($this->ModuleView.'/category', $this->ViewData);
        }
        else
        {
            return redirect()->route('home');
        }
    }

    public function request_sample()
    {
        $this->ViewData['moduleTitle']  = 'Request Sample';
        $this->ViewData['moduleAction'] = 'Request Sample';
        $this->ViewData['sample_type']  = '1';
        $this->ViewData['arrCountry']   = CountryModel::get(['id','name','nicename','phonecode']);
        $this->ViewData['arrReports']   = getSingleReport($_GET['id']);

        if(empty($this->ViewData['arrReports']))
        {
            return redirect()->route('home');
        }

        return view($this->ModuleView.'/sample_form', $this->ViewData);
    }

    public function enquiry_before_buying()
    {
        $this->ViewData['moduleTitle']  = 'Enquiry Before Buying';
        $this->ViewData['moduleAction'] = 'Enquiry Before Buying';
        $this->ViewData['sample_type']  = '2';
        $this->ViewData['arrReports']   = getSingleReport($_GET['id']);
        $this->ViewData['arrCountry']   = CountryModel::get(['id','name','nicename','phonecode']);

        if(empty($this->ViewData['arrReports']))
        {
            return redirect()->route('home');
        }

        return view($this->ModuleView.'/sample_form', $this->ViewData);
    }

    public function ask_for_discount()
    {
        $this->ViewData['moduleTitle']  = 'Ask For Discount';
        $this->ViewData['moduleAction'] = 'Ask For Discount';
        $this->ViewData['sample_type']  = '3';
        $this->ViewData['arrCountry']   = CountryModel::get(['id','name','nicename','phonecode']);
        $this->ViewData['arrReports']   = getSingleReport($_GET['id']);
        
        if(empty($this->ViewData['arrReports']))
        {
            return redirect()->route('home');
        }

        return view($this->ModuleView.'/sample_form', $this->ViewData);
    }

    public function thankyou()
    {
        $this->ViewData['moduleTitle']  = 'Thank You';
        $this->ViewData['moduleAction'] = 'Thank You';
        $this->ViewData['siteSetting']  = $this->siteSetting;

        $reportId = isset($_GET['id']) ? $_GET['id'] : 0;
        $this->ViewData['arrReports'] = getSingleReport($reportId);
        
        if(empty($this->ViewData['arrReports']))
        {
            return redirect()->route('home');
        }

        Session::flush();
        
        return view($this->ModuleView.'/thankyou', $this->ViewData);
    }

    public function payment_failed()
    {
        $this->ViewData['moduleTitle']  = $this->ViewData['moduleAction'] = 'Payment failed';
        $this->ViewData['siteSetting']  = $this->siteSetting;
        
        $arrTransactions = TransactionsModel::where('order_no',Session::get('session_id'))->first(['id','report_id']);
        if(!empty($arrTransactions))
        {
            ## Update report payment status
            $transactionsModel = TransactionsModel::find($arrTransactions->id);
            $transactionsModel->payment_status = 'failed';
            $transactionsModel->save();

            Session::flush();
        }

        return view($this->ModuleView.'/payment_failed', $this->ViewData);
    }

    public function request_form(Request $request)
    {
        $arrRegionsCountry = RegionsCountryModel::with(['regions'])->where('name',$request->country_name)->first();

        $leadsModel               = new LeadsModel();
        $leadsModel->name         = $request->name;
        $leadsModel->email_id     = $request->email_id;
        $leadsModel->contact_no   = $request->contact_no;
        $leadsModel->company_name = $request->company_name;
        $leadsModel->job_title    = $request->job_title;
        $leadsModel->country      = $request->country_name;
        $leadsModel->regions      = !empty($arrRegionsCountry->regions) ? $arrRegionsCountry->regions->name : '-';
        $leadsModel->sample_type  = $request->sample_type;
        $leadsModel->description  = $request->description;
        $leadsModel->report_id    = $request->report_id;

        if($leadsModel->save())
        {
            switch ($request->sample_type)
            {
                case '1':
                    $sample_type = 'Request sample email';
                    $redirectUrl = url('request_sample_thankyou.php?id='.$request->report_id);
                break;
                case '2':
                    $sample_type = 'Ask for discount email';
                    $redirectUrl = url('ask_for_discount_thankyou.php?id='.$request->report_id);
                break;
                case '3':
                    $sample_type = 'Enquiry before buying email';
                    $redirectUrl = url('enquiry_before_buying_thankyou.php?id='.$request->report_id);
                break;
            }
            
            ## Send email to customer
            $this->emailServices->send_email($sample_type,$leadsModel->id,$request->email_id,'enquiry');
            
            ## Send email to admin
            $this->emailServices->send_email_to_admin('Admin Lead email',$leadsModel->id,'enquiry');
            
            return redirect($redirectUrl);
        }
    }

    /*public function checkout(Request $request)
    {
        $this->ViewData['moduleTitle']  = $this->ViewData['moduleAction'] = 'Checkout';
        $this->ViewData['arrCountry']   = CountryModel::get(['id','name','nicename','phonecode']);
        $this->ViewData['arrReports']   = getSingleReport($_GET['id']);
        $this->ViewData['discount']     = isset($_GET['discount']) ? $_GET['discount'] : 0;
        $this->ViewData['price']        = isset($_GET['price']) ? $_GET['price'] : 0;
        $this->ViewData['siteSetting']  = $this->siteSetting;

        if(empty($this->ViewData['arrReports']))
        {
            return redirect()->route('home');
        }

        return view($this->ModuleView.'/checkout_form', $this->ViewData);
    }

    public function checkout_form(Request $request)
    {
        $order_no = 0;
        $arrRegionsCountry = RegionsCountryModel::with(['regions'])->where('name',$request->country_name)->first();

        if($request->payment_type == 'stripe')
        {
            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
            $stripeResponse =  $stripe->checkout->sessions->create([
                'success_url'    => route('stripeSuccess'),
                'cancel_url'     => route('stripeCancel'),
                'customer_email' => $request->email_id,
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data'  => [
                        'product_data' => [
                            'name' => $request->report_title,
                            'images' => ["https://www.infinitybusinessinsights.com/logo.png"],
                        ],
                        'unit_amount'  => (int)$request->licence_price* 100,
                        'currency'     => 'USD',
                    ],
                    'quantity' => 1
                ],],
                'mode' => 'payment',
                'allow_promotion_codes' => true
            ]);
            
            $order_no = $stripeResponse->id;

            $request->session()->put('session_id', $stripeResponse->id);

            $this->JsonData['status']        = 'success';
            $this->JsonData['session_id']    = $stripeResponse->id;
            $this->JsonData['publisher_key'] = env('STRIPE_KEY');
        }
        else
        {
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $paypalToken = $provider->getAccessToken();
            $response = $provider->createOrder([
                "intent" => "CAPTURE",
                "application_context" => [
                    "return_url" => url('payment/paypal_success'),
                    "cancel_url" => url('payment/paypal_cancel'),
                ],
                "purchase_units" => [
                    0 => [
                        "amount" => [
                            "currency_code" => "USD",
                            "value" => "100.00"
                        ]
                    ]
                ]
            ]);

            if(isset($response['id']) && $response['id'] != null)
            {
                $order_no = $response['id'];
                foreach ($response['links'] as $links)
                {
                    if ($links['rel'] == 'approve')
                    {
                        $request->session()->put('session_id', $response['id']);

                        $this->JsonData['status'] = 'success';
                        $this->JsonData['url']    = $links['href'];
                    }
                }

                //$this->JsonData['status']  = 'error';
                //$this->JsonData['message'] = 'Something went wrong.';
            }
            else
            {
                $this->JsonData['status']  = 'error';
                $this->JsonData['message'] = $response['message'] ?? 'Something went wrong.';
            }
        }

        $transactionsModel                 = new TransactionsModel();
        $transactionsModel->order_no       = $order_no;
        $transactionsModel->name           = $request->name;
        $transactionsModel->email_id       = $request->email_id;
        $transactionsModel->contact_no     = $request->contact_no;
        $transactionsModel->company_name   = $request->company_name;
        $transactionsModel->job_title      = $request->job_title;
        $transactionsModel->country        = $request->country_name;
        $transactionsModel->city           = $request->city;
        $transactionsModel->regions        = !empty($arrRegionsCountry->regions) ? $arrRegionsCountry->regions->name : '-';
        $transactionsModel->report_id      = $request->report_id;
        $transactionsModel->zip_code       = $request->zip_code;
        $transactionsModel->licence_price  = $request->licence_price;
        $transactionsModel->discount       = $request->discount;
        $transactionsModel->payment_type   = $request->payment_type;
        $transactionsModel->payment_status = 'pending';

        if($transactionsModel->save())
        {
            $this->JsonData['payment_type'] = $request->payment_type;

            //return redirect('thankyou.php?id='.$request->report_id);
            return response()->json($this->JsonData);
        }
    }

    public function stripeSuccess(Request $request)
    {
        $session_id = $request->session()->get('session_id');
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        $arrSession = $stripe->checkout->sessions->retrieve($session_id);
        if(!empty($arrSession))
        {
            $arrTransactions = TransactionsModel::where('order_no',$session_id)->first(['id','report_id']);
            
            if(!empty($arrTransactions))
            {
                ## Update report payment status
                $transactionsModel = TransactionsModel::find($arrTransactions->id);

                $transactionsModel->payment_status = 'success';
                $transactionsModel->transaction_id  = $arrSession->payment_intent;
                $transactionsModel->save();
                
                ## Send email to customer
                $this->emailServices->send_email('Lead mail',$transactionsModel->id,$transactionsModel->email_id,'purchase');
                
                ## Send email to admin
                $this->emailServices->send_email_to_admin('Admin buyer details email',$transactionsModel->id,'purchase');
            }
            
            return redirect('payment_thank_you.php?mode=stripe&id='.$arrTransactions->report_id.'&response=success');
        }
    }

    public function paypalSuccess(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        $arrResponse = $provider->capturePaymentOrder($request['token']);
        if (isset($arrResponse['status']) && $arrResponse['status'] == 'COMPLETED')
        {
            $session_id = $request->session()->get('session_id');
            $arrTransactions = TransactionsModel::where('order_no',$session_id)->first(['id','report_id']);
            
            if(!empty($arrTransactions))
            {
                ## Update report payment status
                $transactionsModel = TransactionsModel::find($arrTransactions->id);

                $transactionsModel->payment_status = 'success';
                $transactionsModel->transaction_id  = $arrResponse['id'];
                $transactionsModel->save();
                
                ## Send email to customer
                $this->emailServices->send_email('Lead mail',$transactionsModel->id,$transactionsModel->email_id,'purchase');
                
                ## Send email to admin
                $this->emailServices->send_email_to_admin('Admin buyer details email',$transactionsModel->id,'purchase');
            }

            return redirect('payment_thank_you.php?mode=paypal&id='.$arrTransactions->report_id.'&response=success');
        }
        else
        {
            return redirect('payment_failed.php?mode=paypal&response=failed');
        }
    }*/
}
