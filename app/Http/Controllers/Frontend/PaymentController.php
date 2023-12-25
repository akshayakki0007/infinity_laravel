<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

## Services
use App\Services\EmailServices;

use Stripe;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

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

class PaymentController extends Controller
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

    /*public function thankyou()
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
    }*/

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

    public function checkout(Request $request)
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
        $arrStripeSetting = json_decode($this->siteSetting->stripe_setting);
        
        $order_no = 0;
        $arrRegionsCountry = RegionsCountryModel::with(['regions'])->where('name',$request->country_name)->first();

        if($request->payment_type == 'stripe')
        {
            $stripe = new \Stripe\StripeClient($arrStripeSetting->api_key);
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
                            "value" => $request->licence_price
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

    public function stripeCancel(Request $request)
    {
        $session_id = $request->session()->get('session_id');
        $arrTransactions = TransactionsModel::where('order_no',$session_id)->first(['id','report_id']);
        
        if(!empty($arrTransactions))
        {
            ## Update report payment status
            $transactionsModel = TransactionsModel::find($arrTransactions->id);
            $transactionsModel->payment_status = 'cancelled';
            $transactionsModel->save();
        }

        return redirect('payment_thank_you.php?mode=stripe&id='.$arrTransactions->report_id.'&response=cancel');
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
            Session::flush();
            return redirect('payment_thank_you.php?mode=paypal&id='.$arrTransactions->report_id.'&response=success');
        }
        else
        {
            return redirect('payment_failed.php?mode=paypal&response=failed');
        }
    }

    public function paypalCancel(Request $request)
    {
        $session_id = $request->session()->get('session_id');
        $arrTransactions = TransactionsModel::where('order_no',$session_id)->first(['id','report_id']);
        
        if(!empty($arrTransactions))
        {
            ## Update report payment status
            $transactionsModel = TransactionsModel::find($arrTransactions->id);

            $transactionsModel->payment_status = 'cancelled';
            $transactionsModel->save();
        }

        return redirect('payment_thank_you.php?mode=paypal&id='.$arrTransactions->report_id.'&response=cancel');
    }
}
