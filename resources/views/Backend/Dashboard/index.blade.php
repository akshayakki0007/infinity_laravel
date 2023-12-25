@extends('Backend.Partials.master')

@section('title')
   {{ $moduleAction }}
@stop

@section('styles')
<style type="text/css">
  .bg-white {
    background-color: #fff !important;
    color: #000 !important;
    border: 2px solid #3c8dbc;
  }

  .small-box-footer
  {
    background: #3c8dbc !important;
  }

  .filterDiv {

  }

  .filterDiv .col-md-12
  {
    padding: 0;
  }
  .col-md-12.btnDiv {
      margin-left: 18px;
      padding: 0;
  }
</style>
@stop

@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Dashboard
      <small>Control panel</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Dashboard</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">
      @if($user_role == 'super_admin')
        <div class="filterDiv">
          <form action="{{url($modulePath)}}" method="POST" >
            @csrf
            <div class="col-md-12">
              <div class="col-md-6">
                  <div class="form-group">
                    <label for="searchDate">Search Date range:</label>
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" class="form-control searchDate" name="search_date" id="searchDate" autocomplete="off" value="{{ $search_date }}">
                    </div>
                </div>
              </div>
            </div>
            <div class="col-md-12 btnDiv">
              <button type="submit" class="btn btn-primary" id="btn_action_search">Search</button>
              <a href="{{ url($modulePath) }}" class="btn btn-danger">Reset</a>
            </div>
          </form>
        </div>
        <!-- Leads Stats -->
        <div class="col-md-12">
          <h3><b>Leads Stats</b></h3>
          <hr/>
          <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-white">
              <div class="inner">
                <h3>{{ $total_leads }}</h3>
                <p>Total Leads Generated</p>
              </div>
              <a href="{{ url('admin/leads') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-white">
              <div class="inner">
                <h3>{{ $workable_leads }}</h3>
                <p>Total Workable</p>
              </div>
              <a href="{{ url('admin/leads') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-white">
              <div class="inner">
                <h3>{{ $non_workable_leads }}</h3>
                <p>Total Non Workable</p>
              </div>
              <a href="{{ url('admin/leads') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
        </div>

        <!-- Payment Stats -->
        <div class="col-md-12">
          <h3><b>Payment Stats</b></h3>
          <hr/>
          <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-white">
              <div class="inner">
                <h3>{{ $total_purchase }}</h3>
                <p>Total Payment</p>
              </div>
              <a href="{{ url('admin/transactions') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-white">
              <div class="inner">
                <h3>{{ $purchase_failed_count }}</h3>
                <p>Total Failed Payment</p>
              </div>
              <a href="{{ url('admin/transactions') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-white">
              <div class="inner">
                <h3>{{ $purchase_cancelled_count }}</h3>
                <p>Total Cancelled Payment</p>
              </div>
              <a href="{{ url('admin/transactions') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-white">
              <div class="inner">
                <h3>{{ $purchase_stripe_count }}</h3>
                <p>Total Stripe Payment</p>
              </div>
              <a href="{{ url('admin/transactions') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-white">
              <div class="inner">
                <h3>{{ $purchase_paypal_count }}</h3>
                <p>Total Paypal Payment</p>
              </div>
              <a href="{{ url('admin/transactions') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
        </div>

        <!-- Sales Person Stats -->
        <div class="col-md-12">
          <h3><b>Sales Person Stats</b></h3>
          <hr/>
          @if(count($arrSalesObj) > 0)
            @foreach($arrSalesObj as $key => $val)
              <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-white">
                  <div class="inner">
                    <h3><?php echo $dashbaordModel->getSalesPerCount($val->id); ?></h3>
                    <p>{{ $val->name }}</p>
                  </div>
                  <a href="{{ url('admin/users') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
              </div>
            @endforeach
          @endif
        </div>

        <div class="col-md-12">
          <h3><b>Sales Status Stats</b></h3>
          <hr/>
          <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-white">
              <div class="inner">
                <h3><?php echo $dashbaordModel->getPiplineStatusCount('in_process'); ?></h3>
                <p>Total In-Process Count</p>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-white">
              <div class="inner">
                <h3><?php echo $dashbaordModel->getPiplineStatusCount('replay'); ?></h3>
                <p>Total Reply Count</p>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-white">
              <div class="inner">
                <h3><?php echo $dashbaordModel->getPiplineStatusCount('not_interested'); ?></h3>
                <p>Total Not-Interested Count</p>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-white">
              <div class="inner">
                <h3><?php echo $dashbaordModel->getPiplineStatusCount('invoice'); ?></h3>
                <p>Total Invoice Count</p>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-white">
              <div class="inner">
                <h3><?php echo $dashbaordModel->getPiplineStatusCount('forwarded'); ?></h3>
                <p>Total Forwarded Count</p>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-white">
              <div class="inner">
                <h3><?php echo $dashbaordModel->getPiplineStatusCount('close'); ?></h3>
                <p>Total Close Count</p>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-white">
              <div class="inner">
                <h3><?php echo $dashbaordModel->getPiplineStatusCount('interested'); ?></h3>
                <p>Total Not interested Count</p>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-white">
              <div class="inner">
                <h3><?php echo $dashbaordModel->getPiplineStatusCount('non_responsive'); ?></h3>
                <p>Total Non Responsive Count</p>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-white">
              <div class="inner">
                <h3><?php echo $dashbaordModel->getPiplineStatusCount('in_conversion'); ?></h3>
                <p>Total In Conversion Count</p>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div> 
        </div>

        <div class="col-md-12">
          <h3><b>Leads Source Stats</b></h3>
          <hr/>
          @if(count($arrSourceObj) > 0)
            @foreach($arrSourceObj as $key => $row)
              <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-white">
                  <div class="inner">
                    <h3><?php echo $dashbaordModel->getSourceCount($row->id); ?></h3>
                    <p>{{ $row->name }}</p>
                  </div>
                  <a href="{{ url('admin/source') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
              </div>
            @endforeach
          @endif
        </div>
      @else
        <div class="col-md-12">
          <h3>Welcome {{ $user_name }}</h3>
        </div>
      @endif

    </div>
  </section>
  <!-- /.content -->
</div>
@stop

@section('scripts')
<script type="text/javascript">
  $('.searchDate').daterangepicker({
      timePicker: false,
      startDate: moment().startOf('hour'),
      endDate: moment().startOf('hour').add(48, 'hour'),
      locale: {
        format: 'DD/MM/YYYY'
      }
  });
  $('#searchDate').val("{{ $search_date }}");
</script>
@stop